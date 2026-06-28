# Caring Is Our Calling

NDIS registered behaviour support services — small by design, exceptional by commitment.

**Caring Is Our Calling** provides specialist NDIS behaviour support services, with a deliberate commitment to quality over quantity: a maximum of 8 clients at any time, a dedicated consistent team, and a founder who built this because they refused to accept the status quo.

## Tech Stack

| Layer | Choice |
|---|---|
| Site generator | Eleventy (11ty) v3 |
| Templates | Nunjucks (.njk) |
| Styles | Plain CSS |
| Scripts | Vanilla JS |
| Forms | PHP mailer (SMTP + reCAPTCHA v2) |
| Hosting | GitHub Pages (static output in `_site/`) |

## Quick Start

```bash
npm install
npm run dev       # dev server with live reload
npm run build     # production build → _site/
```

The dev server runs at `http://localhost:8080`.

## Editing Content

All content lives in JSON files in `src/_data/`. **You do not need to edit HTML.**

| File | What it controls |
|---|---|
| `src/_data/site.json` | Business name, contact details, reCAPTCHA key |
| `src/_data/nav.json` | Navigation links |
| `src/_data/about.json` | About section text and stats |
| `src/_data/services.json` | Service cards |
| `src/_data/whyus.json` | Why Us section |
| `src/_data/team.json` | Team section |
| `src/_data/values.json` | Values strip icons/labels |
| `src/_data/contact.json` | Contact section text |
| `src/_data/footer.json` | Footer columns |

**Example — change a phone number:**
```bash
# Just edit one line in site.json:
"phone": "0412 345 678"   ← change here
```

**Example — add a new service card:**
```json
// add to src/_data/services.json → items array:
{
  "icon": "fa-star",
  "title": "My New Service",
  "desc": "Description here."
}
```

## Project Structure

```
caringisourcalling/
├── .eleventy.js          ← Eleventy config
├── package.json
├── mail.php              ← SMTP + reCAPTCHA form processor (in _site/)
├── src/
│   ├── index.njk         ← Main page template (the only HTML file)
│   ├── _data/            ← All content in JSON — edit these!
│   │   ├── site.json
│   │   ├── nav.json
│   │   ├── about.json
│   │   ├── services.json
│   │   ├── whyus.json
│   │   ├── team.json
│   │   ├── values.json
│   │   ├── contact.json
│   │   └── footer.json
│   ├── _includes/
│   │   └── layouts/
│   │       └── base.njk  ← Base HTML shell
│   └── assets/
│       ├── css/styles.css
│       ├── js/script.js
│       └── img/favicon.svg
└── _site/                ← Compiled output (what GitHub Pages serves)
```

## Setting Up the Contact Form

The contact form uses PHP + PHPMailer for SMTP delivery and Google reCAPTCHA v2 for bot protection.

### 1. Install PHPMailer (in project root)

```bash
composer require phpmailer/phpmailer
```

### 2. Get reCAPTCHA Keys

1. Go to [https://www.google.com/recaptcha/admin/create](https://www.google.com/recaptcha/admin/create)
2. Select **reCAPTCHA v2** → "I'm not a robot" checkbox
3. Add your domain(s)
4. Put the **Site Key** in `src/_data/site.json` → `recaptchaSiteKey`
5. Put the **Secret Key** in `mail.php` (`RECAPTCHA_SECRET`)

### 3. Configure SMTP

Edit the SMTP constants in `mail.php`:

```php
define('SMTP_HOST',     'smtp.gmail.com');   // or mailgun/sendgrid/ses
define('SMTP_PORT',     587);                // 587 for TLS, 465 for SSL
define('SMTP_USERNAME', 'you@gmail.com');
define('SMTP_PASSWORD', 'your_app_password'); // Gmail: use an App Password
define('SMTP_SECURE',   'tls');
```

**Gmail:** Generate an [App Password](https://myaccount.google.com/apppasswords) — not your real password.

**Other providers:**
- Mailgun: `smtp.mailgun.org`, port 587, TLS
- SendGrid: `smtp.sendgrid.net`, port 587, TLS
- Amazon SES: your SMTP endpoint, port 587, TLS

### 4. Update the form action (for GitHub Pages)

GitHub Pages does not run PHP, so `mail.php` must live elsewhere. Options:
- Your domain registrar's hosting
- Netlify Functions
- Vercel Serverless Functions
- Any cPanel/CPH host

Update `src/_data/site.json`:
```json
"mailPhpUrl": "https://yourhost.com/mail.php"
```

Then rebuild: `npm run build` and upload the `_site/` folder (or keep `mail.php` outside `_site/` and host it separately).

## GitHub Pages Deployment

1. Enable Pages: **Settings → Pages → Source: GitHub Actions**
2. The Eleventy build runs during deployment; `npm run build` outputs to `_site/`
3. The site URL will be: `https://McJono.github.io/caringisourcalling`

Or use the legacy approach: push `_site/` contents to a `gh-pages` branch.

## NDIS Services

- **Behaviour Support Plans (BSP)** — Comprehensive, person-centred behaviour support plans
- **Behaviour Management** — Evidence-based strategies: proactive approaches, skill-building, environmental adjustments
- **Support Coordination** — Navigating NDIS plans and connecting with the right services
- **Allied Health Liaison** — Working alongside psychologists, OTs, and speech pathologists
- **Daily Living Support** — In-home and community support to build independence
- **Training & Advice** — For support workers and families on positive behaviour support

## Key Commitments

- Maximum 8 clients at a time — always
- Same consistent team at every visit
- 100% NDIS registered
- Extremely high client satisfaction as the primary measure of success
