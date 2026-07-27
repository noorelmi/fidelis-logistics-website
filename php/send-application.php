<?php
/**
 * Application form handler - sends submissions via SMTP (PHPMailer).
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

$firstName = clean($_POST['firstName'] ?? '');
$lastName = clean($_POST['lastName'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$applicationType = clean($_POST['applicationType'] ?? '');

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
if ($applicationType === '')                                        $errors[] = 'Application type is required.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$emailSubject = 'New Career Application - ' . $applicationType . ' - ' . $firstName . ' ' . $lastName;
$bodyHtml = build_email_content($_POST, $applicationType);
$bodyText = build_email_text($_POST, $applicationType);

$result = send_form_email($emailSubject, $bodyHtml, $bodyText, $email, $firstName . ' ' . $lastName);

if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your application has been submitted successfully. We will contact you soon.']);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, we could not send your application right now. Please email us at info@fidelistexas.com or call +1 682-346-3590.',
        'debug'   => $result['message']
    ]);
}

// Function to build HTML email content
function build_email_content($data, $type) {
    $firstName = clean($data['firstName'] ?? '');
    $lastName = clean($data['lastName'] ?? '');
    $email = clean($data['email'] ?? '');
    $phone = clean($data['phone'] ?? '');
    
    $html = '<h2 style="color:#0052CC;">New Career Application - ' . $type . '</h2>
    <table cellpadding="8" style="border-collapse:collapse;font-family:Arial,sans-serif;">
      <tr><td style="font-weight:bold;">Name:</td><td>' . $firstName . ' ' . $lastName . '</td></tr>
      <tr><td style="font-weight:bold;">Email:</td><td>' . $email . '</td></tr>
      <tr><td style="font-weight:bold;">Phone:</td><td>' . $phone . '</td></tr>';
    
    // Add type-specific fields
    if ($type === 'Company Drivers') {
        $cdlNumber = clean($data['cdlNumber'] ?? '');
        $experience = clean($data['experience'] ?? '');
        $dotCertificate = clean($data['dotCertificate'] ?? '');
        $availability = clean($data['availability'] ?? '');
        $message = clean($data['message'] ?? '');
        
        $html .= '
      <tr><td style="font-weight:bold;">CDL Number:</td><td>' . $cdlNumber . '</td></tr>
      <tr><td style="font-weight:bold;">Years of Driving Experience:</td><td>' . $experience . '</td></tr>
      <tr><td style="font-weight:bold;">DOT Medical Certificate Valid Until:</td><td>' . $dotCertificate . '</td></tr>
      <tr><td style="font-weight:bold;">Availability:</td><td>' . $availability . '</td></tr>
      <tr><td style="font-weight:bold;vertical-align:top;">Additional Information:</td><td>' . nl2br($message) . '</td></tr>
      <tr><td style="font-weight:bold;">Submitted:</td><td>' . date('Y-m-d H:i:s') . '</td></tr>';
    } else if ($type === 'Logistics Specialists') {
        $position = clean($data['position'] ?? '');
        $experience = clean($data['experience'] ?? '');
        $education = clean($data['education'] ?? '');
        $availability = clean($data['availability'] ?? '');
        $skills = clean($data['skills'] ?? '');
        $message = clean($data['message'] ?? '');
        
        $html .= '
      <tr><td style="font-weight:bold;">Position Interested In:</td><td>' . $position . '</td></tr>
      <tr><td style="font-weight:bold;">Years of Logistics Experience:</td><td>' . $experience . '</td></tr>
      <tr><td style="font-weight:bold;">Education Level:</td><td>' . $education . '</td></tr>
      <tr><td style="font-weight:bold;">Availability:</td><td>' . $availability . '</td></tr>
      <tr><td style="font-weight:bold;vertical-align:top;">Key Skills:</td><td>' . nl2br($skills) . '</td></tr>
      <tr><td style="font-weight:bold;vertical-align:top;">Cover Letter:</td><td>' . nl2br($message) . '</td></tr>
      <tr><td style="font-weight:bold;">Submitted:</td><td>' . date('Y-m-d H:i:s') . '</td></tr>';
    }
    
    $html .= '</table>
    <p style="color:#666;font-size:12px;">Sent from the Fidelis Logistics website career application form.</p>';
    
    return $html;
}

// Function to build plain-text email content
function build_email_text($data, $type) {
    $firstName = clean($data['firstName'] ?? '');
    $lastName = clean($data['lastName'] ?? '');
    $email = clean($data['email'] ?? '');
    $phone = clean($data['phone'] ?? '');
    
    $text = "New Career Application - $type\n\n"
          . "Name: $firstName $lastName\n"
          . "Email: $email\n"
          . "Phone: $phone\n";
    
    if ($type === 'Company Drivers') {
        $cdlNumber = clean($data['cdlNumber'] ?? '');
        $experience = clean($data['experience'] ?? '');
        $dotCertificate = clean($data['dotCertificate'] ?? '');
        $availability = clean($data['availability'] ?? '');
        $message = clean($data['message'] ?? '');
        
        $text .= "CDL Number: $cdlNumber\n"
              . "Years of Driving Experience: $experience\n"
              . "DOT Medical Certificate Valid Until: $dotCertificate\n"
              . "Availability: $availability\n"
              . "Additional Information: $message\n";
    } else if ($type === 'Logistics Specialists') {
        $position = clean($data['position'] ?? '');
        $experience = clean($data['experience'] ?? '');
        $education = clean($data['education'] ?? '');
        $availability = clean($data['availability'] ?? '');
        $skills = clean($data['skills'] ?? '');
        $message = clean($data['message'] ?? '');
        
        $text .= "Position Interested In: $position\n"
              . "Years of Logistics Experience: $experience\n"
              . "Education Level: $education\n"
              . "Availability: $availability\n"
              . "Key Skills: $skills\n"
              . "Cover Letter: $message\n";
    }
    
    $text .= "\nSubmitted: " . date('Y-m-d H:i:s') . "\n";
    
    return $text;
}
?>
