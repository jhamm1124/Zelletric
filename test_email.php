<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.zellectric.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@zellectric.com'; // your email
    $mail->Password = 'BDZ15Bzell!';          // email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];




    $mail->setFrom('contact@zellectric.com', 'Test');
    $mail->addAddress('contact@zellectric.com');

    $mail->Subject = 'SMTP Test';
    $mail->Body = 'This is a test email.';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
