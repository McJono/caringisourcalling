<?php
/**
 * mail.php — SMTP Contact Form for Caring Is Our Calling
 * 
 * SETUP:
 *  1. Install PHPMailer via Composer in the project root:
 *       composer require phpmailer/phpmailer
 *  2. Fill in your SMTP credentials below (lines 17-25)
 *  3. Register at https://www.google.com/recaptcha/admin/create
 *     to get your reCAPTCHA v2 "I'm not a robot" site + secret keys.
 *     Put the SITE KEY in index.html and the SECRET KEY below.
 *  4. Upload to any PHP 7.4+ hosting with SSL (HTTPS required for reCAPTCHA).
 * 
 * NOTE: This file MUST live on the server — it will NOT work if opened directly
 *       from your computer (file://). It needs a web server (Apache/Nginx).
 */

// ─── CONFIGURATION ────────────────────────────────────────────────────────────

// reCAPTCHA secret key (from https://www.google.com/recaptcha/admin)
define('RECAPTCHA_SECRET', 'YOUR_RECAPTCHA_SECRET_KEY');

// Email recipients
define('TO_EMAIL',   'hello@caringisourcalling.com.au');
define('TO_NAME',    'Caring Is Our Calling');
define('FROM_EMAIL', 'noreply@caringisourcalling.com.au');
define('FROM_NAME',  'Caring Is Our Calling Website');

// SMTP settings (Gmail example — use an App Password, NOT your real password)
// Or use Mailgun, SendGrid, Amazon SES, or your hosting provider's SMTP
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     587);
define('SMTP_USERNAME', 'your@gmail.com');
define('SMTP_PASSWORD', 'your_app_password');   // ← Gmail: use an App Password
define('SMTP_FROM',     'your@gmail.com');
define('SMTP_FROM_NAME','Caring Is Our Calling');
define('SMTP_SECURE',   'tls');                 // 'tls' or 'ssl'

// ─── DO NOT EDIT BELOW ─────────────────────────────────────────────────────────

header('Content-Type: text/plain; charset=utf-8');
header('X-Content-Type-Options: nosniff');

/* ── 1. Validate reCAPTCHA ──────────────────────────────────────────────────── */
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    http_response_code(400);
    echo 'reCAPTCHA token missing.';
    exit;
}

$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$verifyData = [
    'secret'   => RECAPTCHA_SECRET,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
];

$ch = curl_init($verifyUrl);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS      => http_build_query($verifyData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);
$verifyResult = curl_exec($ch);
curl_close($ch);

$verify = json_decode($verifyResult, true);

if (!($verify['success'] ?? false)) {
    http_response_code(400);
    echo 'reCAPTCHA verification failed. Please reload the page and try again.';
    exit;
}

/* ── 2. Sanitise & validate inputs ─────────────────────────────────────────── */
$name    = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES, 'UTF-8'));
$email   = trim(filter_var($_POST['email']   ?? '', FILTER_VALIDATE_EMAIL));
$phone   = trim(htmlspecialchars($_POST['phone']   ?? '', ENT_QUOTES, 'UTF-8'));
$subject = trim(htmlspecialchars($_POST['subject'] ?? '', ENT_QUOTES, 'UTF-8'));
$message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

$subjectMap = [
    'new-referral'       => 'New Referral Enquiry',
    'support-coordination'=> 'Support Coordination Enquiry',
    'behaviour-support'  => 'Behaviour Support Enquiry',
    'general-enquiry'    => 'General Enquiry',
    'other'              => 'Other Enquiry',
];

$subjectLabel = $subjectMap[$subject] ?? 'Website Contact';

if (!$name || !$email || !$subject || !$message) {
    http_response_code(400);
    echo 'All required fields must be filled in.';
    exit;
}

if (strlen($name) > 200 || strlen($message) > 5000) {
    http_response_code(400);
    echo 'Input too long. Please shorten your message.';
    exit;
}

/* ── 3. Build email ──────────────────────────────────────────────────────────── */
$emailBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body  { font-family: Arial, sans-serif; color: #1e293b; line-height: 1.7; margin: 0; padding: 0; }
    .wrap { max-width: 600px; margin: 0 auto; padding: 40px 32px; }
    .hdr  { background: #0b3d91; padding: 32px; text-align: center; border-radius: 12px 12px 0 0; }
    .hdr h1 { color: #fff; font-size: 1.4rem; margin: 0; font-weight: 700; }
    .body { background: #f8fafc; padding: 36px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; }
    .row  { margin-bottom: 20px; }
    .lbl  { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 4px; }
    .val  { font-size: 1rem; color: #1e293b; }
    .msg  { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; white-space: pre-wrap; }
    .ftr  { margin-top: 28px; font-size: 0.8rem; color: #94a3b8; text-align: center; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="hdr"><h1>New Message — Caring Is Our Calling</h1></div>
    <div class="body">
      <div class="row">
        <div class="lbl">Name</div>
        <div class="val">{$name}</div>
      </div>
      <div class="row">
        <div class="lbl">Email</div>
        <div class="val"><a href="mailto:{$email}">{$email}</a></div>
      </div>
      <div class="row">
        <div class="lbl">Phone</div>
        <div class="val">{$phone}</div>
      </div>
      <div class="row">
        <div class="lbl">Subject</div>
        <div class="val">{$subjectLabel}</div>
      </div>
      <div class="row">
        <div class="lbl">Message</div>
        <div class="msg">{$message}</div>
      </div>
    </div>
    <div class="ftr">Sent via caringisourcalling.com.au</div>
  </div>
</body>
</html>
HTML;

/* ── 4. Send via PHPMailer (SMTP) ───────────────────────────────────────────── */
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->Port       = SMTP_PORT;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;

    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress(TO_EMAIL, TO_NAME);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->CharSet    = 'UTF-8';
    $mail->Subject    = "[Website] {$subjectLabel} — {$name}";
    $mail->Body       = $emailBody;
    $mail->AltBody    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$subjectLabel}\n\nMessage:\n{$message}";

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    http_response_code(500);
    // Don't expose SMTP details publicly
    error_log('PHPMailer error: ' . $mail->ErrorInfo);
    echo 'Sorry, we could not send your message right now. Please try again later or email us directly.';
}
