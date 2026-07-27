<?php
/**
 * Shared SMTP mailer helper using PHPMailer.
 * Returns array: ['success' => bool, 'message' => string]
 */

require_once __DIR__ . '/mail-config.php';
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email via SMTP.
 *
 * @param string $subject      Email subject line
 * @param string $bodyHtml     HTML body
 * @param string $bodyText     Plain-text body
 * @param string $replyToEmail Reply-to email (the visitor's email)
 * @param string $replyToName  Reply-to name (the visitor's name)
 * @return array
 */
function send_form_email($subject, $bodyHtml, $bodyText, $replyToEmail = '', $replyToName = '')
{
    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE; // 'ssl' or 'tls'
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        if (defined('SMTP_DEBUG') && SMTP_DEBUG) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        // From / To
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO, MAIL_TO_NAME);

        // Reply directly to the visitor
        if (!empty($replyToEmail)) {
            $mail->addReplyTo($replyToEmail, $replyToName ?: $replyToEmail);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;
        $mail->AltBody = $bodyText;

        $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully.'];
    } catch (Exception $e) {
        // Mailer error message is in $mail->ErrorInfo
        return [
            'success' => false,
            'message' => 'Mailer Error: ' . $mail->ErrorInfo
        ];
    }
}
