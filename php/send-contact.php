<?php
/**
 * Contact form handler - sends submissions via SMTP (PHPMailer).
 */

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once __DIR__ . '/mailer.php';

function clean($v) {
    return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8');
}

$name           = clean($_POST['name'] ?? '');
$email          = clean($_POST['email'] ?? '');
$phone          = clean($_POST['phone'] ?? '');
$subject        = clean($_POST['subject'] ?? '');
$message        = clean($_POST['message'] ?? '');
$smsConsent     = !empty($_POST['smsConsent']) && $_POST['smsConsent'] != '0';
$privacyConsent = !empty($_POST['privacyConsent']) && $_POST['privacyConsent'] != '0';

// Honeypot
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true, 'message' => 'Thank you.']);
    exit;
}

$errors = [];
if ($name === '')                                                   $errors[] = 'Name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = 'A valid email is required.';
if ($phone === '')                                                  $errors[] = 'Phone number is required.';
if ($subject === '')                                                $errors[] = 'Subject is required.';
if ($message === '')                                                $errors[] = 'Message is required.';
if (!$privacyConsent)                                               $errors[] = 'You must accept the Privacy Policy.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$emailSubject = 'New Contact Form Submission: ' . $subject;

$bodyHtml = '
<h2 style="color:#0052CC;">New Contact Form Submission</h2>
<table cellpadding="8" style="border-collapse:collapse;font-family:Arial,sans-serif;">
  <tr><td style="font-weight:bold;">Name:</td><td>' . $name . '</td></tr>
  <tr><td style="font-weight:bold;">Email:</td><td>' . $email . '</td></tr>
  <tr><td style="font-weight:bold;">Phone:</td><td>' . $phone . '</td></tr>
  <tr><td style="font-weight:bold;">Subject:</td><td>' . $subject . '</td></tr>
  <tr><td style="font-weight:bold;vertical-align:top;">Message:</td><td>' . nl2br($message) . '</td></tr>
  <tr><td style="font-weight:bold;">SMS Consent:</td><td>' . ($smsConsent ? 'Yes' : 'No') . '</td></tr>
  <tr><td style="font-weight:bold;">Submitted:</td><td>' . date('Y-m-d H:i:s') . '</td></tr>
</table>
<p style="color:#666;font-size:12px;">Sent from the Fidelis Logistics website contact form.</p>
';

$bodyText = "New Contact Form Submission\n\n"
          . "Name: $name\n"
          . "Email: $email\n"
          . "Phone: $phone\n"
          . "Subject: $subject\n"
          . "Message:\n$message\n\n"
          . "SMS Consent: " . ($smsConsent ? 'Yes' : 'No') . "\n"
          . "Submitted: " . date('Y-m-d H:i:s') . "\n";

$result = send_form_email($emailSubject, $bodyHtml, $bodyText, $email, $name);

if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, we could not send your message right now. Please email us at info@fidelistexas.com or call +1 682-346-3590.',
        'debug'   => $result['message']
    ]);
}
