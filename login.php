<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in the member table
    $query = "SELECT * FROM member WHERE Email_ID = '$email' AND Password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = 'Member';

        // Redirect to member dashboard
        header('Location: student_dashboard.php');
        exit();
    } else {
        // Check in the librarian table
        $query = "SELECT * FROM librarian WHERE L_Email = '$email' AND Password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = 'Librarian';

            // Redirect to librarian dashboard
            header('Location: librarian_dashboard.php');
            exit();
        } else {
            $loginError = "Invalid email or password.";
        }
    }
}
?>




<?php
include('database.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = filter_input(INPUT_POST, 'complaint_id', FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    if (!$complaint_id || !$new_status || !in_array($new_status, ['solved', 'pending'])) {
        echo 'Invalid input.';
        exit;
    }

    // Fetch member email
    $stmt = $conn->prepare("SELECT member_id FROM complaints WHERE complaint_id = ?");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo 'Complaint not found.';
        exit;
    }
    $member_id = $result->fetch_assoc()['member_id'];

    $stmt = $conn->prepare("SELECT m_email FROM member WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo 'Member not found.';
        exit;
    }
    $member_email = $result->fetch_assoc()['m_email'];

    // Update status
    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);
    if ($stmt->execute()) {
        echo "Complaint status updated successfully.";
        $stmt->close();

        // Send email if solved
        if ($new_status === 'solved') {
            if (sendEmailNotification($member_email)) {
                echo " Email sent successfully!";
            } else {
                echo " Email failed!";
            }
        }
    } else {
        echo 'Database update failed.';
    }
}

// Function to send an email using PHPMailer
function sendEmailNotification($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2; // Enable debug output (set to 0 when working)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username   = 'smartlibrary25@gmail.com'; // Your email address
        $mail->Password = 'psuk ugup wwgv vaps'; // Your App Password (NOT your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('smartlibrary25@gmail.com', 'Library Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Complaint Resolved';
        $mail->Body = "Dear Member,<br>Your complaint has been resolved. Thank you for your patience.<br><br>Library Admin";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo); // Log error for debugging
        return false;
    }
}
?>
