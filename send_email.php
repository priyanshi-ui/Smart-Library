<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Start session or use existing data source for user emails
session_start();
$userEmails = $_SESSION['email']; // Ensure this is an array

if (is_array($userEmails)) {
    foreach ($userEmails as $userEmail) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = $userEmail['email']; // Individual user's email
            $mail->Password = $userEmail['password']; // Individual user's app-specific password
            $mail->SMTPSecure = 'tls'; // or 'ssl' if applicable
            $mail->Port = 587; // or 465 for SSL

            // Sender and recipient details
            $mail->setFrom($userEmail['email'], 'User Name');
            $mail->addAddress($adminEmail, 'Admin');

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'User Email Notification';
            $mail->Body = 'This email was sent from the user: ' . $userEmail['email'];

            $mail->send();
            echo "Message from {$userEmail['email']} has been sent successfully.<br>";
        } catch (Exception $e) {
            echo "Message from {$userEmail['email']} could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
        }

        $mail->clearAllRecipients();
    }
} else {
    echo "No user emails found or the data format is incorrect.";
}

?>