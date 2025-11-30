<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Check if email, OTP type, and user type are set
if (isset($_POST['email'], $_POST['otp_type'])){
    $email = trim($_POST['email']);
    $otp_type = $_POST['otp_type'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format.';
        exit();
    }

    // Generate a random OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_type'] = $otp_type; // Store OTP type to differentiate the process
    $_SESSION['register']['user_type'] = $user_type; // Store the user type in the session

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings for PHPMailer
        $mail->SMTPDebug = 0; // Disable verbose debug output (set to 0 for production)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'smartlibrary25@gmail.com'; // Your email address
        $mail->Password = 'psuk ugup wwgv vaps';        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Set email sender and recipient
        $mail->setFrom('smartlibrary25@gmail.com', 'Smart Library');
        $mail->addAddress($email); // Send email to user

        // Check if the OTP type is for forgot password or registration
        if ($otp_type == 'forgot_password') {
            // Email content for forgot password
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body    = "<p>Your OTP for resetting your password is: <strong>{$otp}</strong></p>
                              <p>Please enter this OTP to complete the process.</p>
                              <p>Best regards,<br>Smart Library Team</p>";
        } elseif ($otp_type == 'registration') {
            // Email content for registration
            $mail->isHTML(true);
            $mail->Subject = 'Your Smart Library OTP';
            $mail->Body    = "<p>Dear " . ucfirst($user_type) . ",</p>
                              <p>Your OTP for Smart Library registration is: <strong>{$otp}</strong></p>
                              <p>Please enter this OTP to complete your registration.</p>
                              <p>Best regards,<br>Smart Library Team</p>";
        }

        // Send the email
        if ($mail->send()) {
            $_SESSION['status'] = "OTP sent successfully to your email.";
            
            // Redirect to respective OTP verification page
            if ($otp_type == 'forgot_password') {
                header('Location: otp_verification_forgot_password.php');
            } else {
                header('Location: otp_verification_registration.php');
            }
            exit();
        } else {
            throw new Exception('Failed to send OTP.');
        }

    } catch (Exception $e) {
        // Log the error for debugging
        error_log("Mailer Error: " . $mail->ErrorInfo); // Log the error message
        $_SESSION['status'] = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
        header('Location: registration.php?error=Failed to send OTP');
        exit();
    }
} else {
    echo 'Email, OTP type, or user type not provided.';
}
?>
