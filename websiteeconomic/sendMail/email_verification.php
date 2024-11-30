<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoload

function sendVerificationEmail($adminEmail, $newUserId) {
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP server của bạn
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phanquocthinh004@gmail.com'; // Email admin của bạn
        $mail->Password   = 'rvkv tbip rcjb ztpb'; // Mật khẩu email admin
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('phanquocthinh004@gmail.com', 'QT Store');
        $mail->addAddress($adminEmail); // Admin nhận email xác nhận

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New User Registration Confirmation';
        $verificationLink = "http://localhost:81/websiteeconomic/confirm_registration.php?user_id=" . $newUserId;

        $mail->Body    = "A new user has registered. Please <a href='$verificationLink'>click here</a> to verify their email and complete the registration.";
        $mail->AltBody = "A new user has registered. Please visit this link to verify their email: $verificationLink";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
