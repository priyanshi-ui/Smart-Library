<?php
include('database.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['status' => 'error', 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = filter_input(INPUT_POST, 'complaint_id', FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    if (!$complaint_id || !$new_status || !in_array($new_status, ['solved', 'pending'])) {
        $response['message'] = 'Invalid input.';
        echo json_encode($response);
        exit;
    }

    // Fetch member email
    $stmt = $conn->prepare("SELECT member_id FROM complaints WHERE complaint_id = ?");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['message'] = 'Complaint not found.';
        echo json_encode($response);
        exit;
    }

    $member_id = $result->fetch_assoc()['member_id'];
    $stmt->close();

    $stmt = $conn->prepare("SELECT m_email FROM member WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['message'] = 'Member not found.';
        echo json_encode($response);
        exit;
    }

    $member_email = $result->fetch_assoc()['m_email'];
    $stmt->close();

    // Update status in the database
    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);

    if ($stmt->execute()) {
        $stmt->close();

        // If the complaint is marked as "solved", send an email
        if ($new_status === 'solved') {
            if (sendEmailNotification($member_email)) {
                $response = ['status' => 'success', 'message' => 'Complaint status updated & email sent.', 'new_status' => $new_status];
            } else {
                $response = ['status' => 'success', 'message' => 'Complaint status updated, but email failed.', 'new_status' => $new_status];
            }
        } else {
            // If changed to "pending", just return success message without email
            $response = ['status' => 'success', 'message' => 'Complaint status updated.', 'new_status' => $new_status];
        }
    } else {
        $response['message'] = 'Database update failed.';
    }
}

echo json_encode($response);
exit;

// Function to send an email using PHPMailer
function sendEmailNotification($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'smartlibrary25@gmail.com';
        $mail->Password = 'psuk ugup wwgv vaps'; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('smartlibrary25@gmail.com', 'Library Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Complaint Resolved';
        $mail->Body = "Dear Member,<br>Your complaint has been resolved. Thank you for your patience.<br><br>Library Admin";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>