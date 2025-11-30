<?php
session_start();
require 'database.php'; // Include your database connection file

// Check if the request is POST and the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if both passwords match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Get the user's email or user ID from the session (assuming it's stored during the OTP process)
        $email = $_SESSION['email']; // Or $_SESSION['user_id'] if you store user ID

        // Update the password in the database
        $sql = "UPDATE member SET password = ? WHERE m_email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $hashed_password, $email);

            // Execute the query and check if the update was successful
            if ($stmt->execute()) {
                // Password updated successfully
                $_SESSION['status'] = "Password has been updated successfully.";
                // Redirect to the login page or show a success message
                header("Location: registration.php");
                exit();
            } else {
                // Error updating password
                echo "Error updating password. Please try again.";
            }
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    } else {
        // Passwords do not match
        header("Location: reset_password.php?error=Passwords do not match");
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
