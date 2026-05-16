<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class EmailNotificationService
{
    public static function sendEmail(string $recipient, string $subject, string $body): bool
    {
        $mail = new PHPMailer(false);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dzdanielsaavedra@gmail.com';
        $mail->Password = 'oebgksjlsdqnoerd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('dzdanielsaavedra@gmail.com', 'NORSU System');
        $mail->addAddress($recipient);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    }
}
