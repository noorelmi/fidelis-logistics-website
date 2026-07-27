<?php
/**
 * Quote form handler - sends submissions via SMTP (PHPMailer).
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

$firstName         = clean($_POST['firstName'] ?? '');
$lastName          = clean($_POST['lastName'] ?? '');
$email             = clean($_POST['email'] ?? '');
$phone             = clean($_POST['phone'] ?? '');
$company           = clean($_POST['company'] ?? '');
$origin            = clean($_POST['origin'] ?? '');
$destination       = clean($_POST['destination'] ?? '');
$weight            = clean($_POST['weight'] ?? '');
$type              = clean($_POST['type'] ?? '');
$shipmentFrequency = clean($_POST['shipmentFrequency'] ?? '');
$message           = clean($_POST['message'] ?? '');
$smsConsent        = !empty($_POST['smsConsent']) && $_POST['smsConsent'] != '0';

// Honeypot
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true, 'message' => 'Thank you.']);
    exit;
}

$errors = [];
if ($firstName === '')                                              $errors[] = 'First name is required.';
if ($lastName === '')                                               $errors[] = 'Last name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = 'A valid email is required.';
if ($phone === '')                                                  $errors[] = 'Phone number is required.';
if ($origin === '')                                                 $errors[] = 'Origin location is required.';
if ($destination === '')                                            $errors[] = 'Destination location is required.';
if ($type === '')                                                   $errors[] = 'Type is required.';
if ($shipmentFrequency === '')                                      $errors[] = 'Shipment frequency is required.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$emailSubject = 'New Quote Request - ' . $firstName . ' ' . $lastName;

$bodyHtml = '
<h2 style="color:#0052CC;">New Quote Request</h2>
<table cellpadding="8" style="border-collapse:collapse;font-family:Arial,sans-serif;">
  <tr><td style="font-weight:bold;">Name:</td><td>' . $firstName . ' ' . $lastName . '</td></tr>
  <tr><td style="font-weight:bold;">Email:</td><td>' . $email . '</td></tr>
  <tr><td style="font-weight:bold;">Phone:</td><td>' . $phone . '</td></tr>
  <tr><td style="font-weight:bold;">Company:</td><td>' . ($company !== '' ? $company : 'Not provided') . '</td></tr>
  <tr><td style="font-weight:bold;">From:</td><td>' . $origin . '</td></tr>
  <tr><td style="font-weight:bold;">Destination:</td><td>' . $destination . '</td></tr>
  <tr><td style="font-weight:bold;">Weight:</td><td>' . ($weight !== '' ? $weight : 'Not specified') . '</td></tr>
  <tr><td style="font-weight:bold;">Type:</td><td>' . $type . '</td></tr>
  <tr><td style="font-weight:bold;">Monthly Shipment Count:</td><td>' . $shipmentFrequency . '</td></tr>
  <tr><td style="font-weight:bold;vertical-align:top;">Message:</td><td>' . ($message !== '' ? nl2br($message) : 'None') . '</td></tr>
  <tr><td style="font-weight:bold;">SMS Consent:</td><td>' . ($smsConsent ? 'Yes' : 'No') . '</td></tr>
  <tr><td style="font-weight:bold;">Submitted:</td><td>' . date('Y-m-d H:i:s') . '</td></tr>
</table>
<p style="color:#666;font-size:12px;">Sent from the Fidelis Logistics website quote request form.</p>
';

$bodyText = "New Quote Request\n\n"
          . "Name: $firstName $lastName\n"
          . "Email: $email\n"
          . "Phone: $phone\n"
          . "Company: " . ($company !== '' ? $company : 'Not provided') . "\n"
          . "From: $origin\n"
          . "Destination: $destination\n"
          . "Weight: " . ($weight !== '' ? $weight : 'Not specified') . "\n"
          . "Type: $type\n"
          . "Monthly Shipment Count: $shipmentFrequency\n"
          . "Message: " . ($message !== '' ? $message : 'None') . "\n"
          . "SMS Consent: " . ($smsConsent ? 'Yes' : 'No') . "\n"
          . "Submitted: " . date('Y-m-d H:i:s') . "\n";

$result = send_form_email($emailSubject, $bodyHtml, $bodyText, $email, $firstName . ' ' . $lastName);

if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your quote request has been sent successfully. We will contact you within 24 hours.']);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, we could not send your request right now. Please email us at info@fidelistexas.com or call +1 682-346-3590.',
        'debug'   => $result['message']
    ]);
}
