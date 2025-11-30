<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
    $institute = filter_input(INPUT_POST, 'institute', FILTER_SANITIZE_STRING);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format';
        exit;
    }

    // Check if email exists in the member table
    $query = "
    SELECT m_email AS email, 'member' AS user_type FROM member WHERE m_email = '$email'
    UNION 
    SELECT l_email AS email, 'librarian' AS user_type FROM librarian WHERE l_email = '$email'
";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        header("Location: send_otp.php"); // Redirect to OTP verification
            exit();
        } else {
            // Email is available
            $_SESSION['email'] = $email;
            // You can proceed to the next step
            echo 'Email is available';
        }
    }

    $conn->close();

?>
