<?php
session_start();
include 'database.php'; // Assume this connects to your database

if (!isset($_SESSION['otp_purpose']) || $_SESSION['otp_purpose'] != 'registration') {
    // If the session doesn't have the right OTP purpose, redirect back
    header("Location: otp_verification.php");
    exit();
}

// Get registration data from the session
$data = $_SESSION['registration_data'];

$name = $data["name"];
$email = $data["email"];
$phone = $data["phone"];
$address = $data["address"];
$user_type = $data["user_type"];
$institute = $data["institute"];
$password = password_hash($data["password"], PASSWORD_BCRYPT);
$gender = $data["gender"];

// Insert the user into the database based on user_type
if ($user_type == "Member") {
    $sql = "INSERT INTO member (m_name, m_email, m_address, m_contact, m_gender, institute_name, password) 
            VALUES ('$name', '$email', '$address', '$phone', '$gender', '$institute', '$password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["user_id"] = $conn->insert_id;
        $_SESSION["email"] = $email;
        $_SESSION["user_type"] = $user_type;
        header("Location: member_dashboard.php");
        exit();
    }
} else {
    $sql = "INSERT INTO librarian (l_name, l_email, l_address, l_contact, l_gender, password) 
            VALUES ('$name', '$email', '$address', '$phone', '$gender', '$password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["user_id"] = $conn->insert_id;
        $_SESSION["email"] = $email;
        $_SESSION["user_type"] = $user_type;
        header("Location: librarian_dashboard.php");
        exit();
    }
}

// Close connection and clear session data
$conn->close();
unset($_SESSION['otp'], $_SESSION['registration_data'], $_SESSION['otp_purpose']);
?>
