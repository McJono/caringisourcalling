# Caring Is Our Calling

NDIS registered behaviour support services — small by design, exceptional by commitment.

**Caring Is Our Calling** provides specialist NDIS behaviour support services, with a deliberate commitment to quality over quantity: a maximum of 8 clients at any time, a dedicated consistent team, and a founder who built this because they refused to accept the status quo.

## Tech Stack

| Layer | Choice |
|---|---|
| Website | Static HTML · CSS · Vanilla JS |
| Forms | PHP mailer (SMTP + reCAPTCHA v2) |
| Hosting | GitHub Pages (static) |
| Domain | `caringisourcalling.com.au` |
| Email | SMTP (Gmail / SendGrid / Mailgun / SES) |

## Quick Start

```bash
# Open in browser
open index.html

# Or serve locally (any static server works)
npx serve .
python3 -m http.server 8080
```

## Setting Up the Contact Form

The contact form uses PHP + PHPMailer for SMTP delivery and Google reCAPTCHA v2 for bot protection.

### 1. Install PHPMailer

```bash
composer require phpmailer/phpmailer
```

### 2. Get reCAPTCHA Keys

1. Go to [https://www.google.com/recaptcha/admin/create](https://www.google.com/recaptcha/admin/create)
2. Select **reCAPTCHA v2** → "I'm not a robot" checkbox
3. Add your domain(s)
4. Copy the **Site Key** into `index.html** (the `data-sitekey` attribute on the `.g-recaptcha` div)
5. Copy the **Secret Key** into `mail.php` (`RECAPTCHA_SECRET`)

### 3. Configure SMTP

Edit the SMTP constants in `mail.php`:

```php
define('SMTP_HOST',     'smtp.gmail.com');   // or mailgun/ses/sendgrid
define('SMTP_PORT',     587);                // 587 for TLS, 465 for SSL
define('SMTP_USERNAME', 'you@gmail.com');
define('SMTP_PASSWORD', 'your_app_password'); // Gmail: use an App Password
define('SMTP_SECURE',   'tls');
```

**Gmail users:** You need an [App Password](https://myaccount.google.com/apppasswords), not your regular password. Generate one for "Mail" → "Other (Custom name)".

**Other providers:**
- Mailgun: use `smtp.mailgun.org`, port 587, TLS
- SendGrid: use `smtp.sendgrid.net`, port 587, TLS
- Amazon SES: use your SMTP endpoint, port 587, TLS

### 4. Upload to PHP Hosting

The static files (`index.html`, `styles.css`, `script.js`, `favicon.svg`) can be hosted on GitHub Pages.

The PHP mailer (`mail.php` + `vendor/`) needs PHP 7.4+ hosting with SSL. Options:
- Your domain registrar's hosting
- Netlify (PHP functions)
- Vercel (serverless functions)
- Any cPanel/CPH hosting

Update the `<form action="...">` in `index.html` to point to your PHP endpoint.

## Project Structure

```
caringisourcalling/
├── index.html        ← Main website (static)
├── styles.css        ← All styles
├── script.js         ← Interactivity + form handling
├── mail.php          ← SMTP + reCAPTCHA form processor
├── README.md
└── .github/
    └── workflows/
        └── deploy.yml   ← GitHub Pages auto-deploy
```

## NDIS Services

- **Behaviour Support Plans (BSP)** — Comprehensive, person-centred behaviour support plans developed collaboratively
- **Behaviour Management** — Evidence-based strategies focused on proactive approaches, skill-building, and environmental adjustments
- **Support Coordination** — Navigating NDIS plans and connecting with the right services
- **Allied Health Liaison** — Working alongside psychologists, OTs, and speech pathologists
- **Daily Living Support** — In-home and community support to build independence
- **Training & Advice** — For support workers and families on positive behaviour support

## Key Commitments

- Maximum 8 clients at a time — always
- Same consistent team at every visit
- 100% NDIS registered
- Extremely high client satisfaction as the primary measure of success
