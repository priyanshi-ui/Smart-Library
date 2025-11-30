<?php
session_start();

// Include your database connection file (adjust this path to match your project structure)
require 'database.php'; // Assumes this file contains the database connection logic

// Check if the OTP type is correct for resetting the password
if (!isset($_SESSION['otp_type']) || $_SESSION['otp_type'] != 'forgot_password') {
    // If the OTP type is incorrect, redirect the user
    header('Location: otp_verification.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new password and confirm password fields
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate that the passwords match
    if ($new_password === $confirm_password) {
        // Hash the password (adjust as needed for security)
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Retrieve the email from the session
        $email = $_SESSION['otp_email'];

        // Check whether the email belongs to a member or librarian
        // Query for checking the email in the member table first
        $sql_member = "SELECT m_email FROM member WHERE m_email = ?";
        $is_member = false;

        if ($stmt = $conn->prepare($sql_member)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $is_member = true;
            }
            $stmt->close();
        }

        // If the email belongs to a member, update the member's password
        if ($is_member) {
            $table = "member";
            $email_field = "m_email";
            $password = "m_password";
        } else {
            // If not a member, assume it's a librarian
            $table = "librarian";
            $email_field = "l_email";
            $password = "l_password";
        }

        // Prepare SQL query to update the password in the correct table
        $sql = "UPDATE $table SET $password = ? WHERE $email_field = ?";

        // Execute the query (assuming you are using MySQLi for database interaction)
        if ($stmt = $conn->prepare($sql)) {
            // Bind the hashed password and email to the query
            $stmt->bind_param("ss", $hashed_password, $email);

            // Execute the query
            if ($stmt->execute()) {
                // Password successfully updated
                // Destroy OTP session data after password reset
                unset($_SESSION['otp']);
                unset($_SESSION['otp_email']);
                unset($_SESSION['otp_type']);
                unset($_SESSION['register']['user_type']); // Clear user type

                // Redirect to the login/registration page with success status
                header('Location: registration.php?status=PasswordResetSuccess');
               // $success = "Your password has been successfully reset.";
                exit();
            } else {
                $error = "Failed to update password. Please try again.";
            }

            // Close the statement
            $stmt->close();
        } else {
            $error = "Database error: Unable to prepare statement.";
        }

        // Close the database connection
        $conn->close();
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
         /* Header with logo */
 header {
  width: 100%;
  padding: 20px;
  background-color: #fff;
  display: flex;
  justify-content: flex-start; /* Aligns items to the right */
  align-items: center;
  height:60px;
  opacity:0.8;
}

header img {
  width: 120px;
  height: auto;
}

/* Styles for the reset password form */
.container {
    max-width: 70%;
    margin: 20px auto;
}

.form {
    background-color: #ffffff; /* White background */
    padding: 30px;
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.form h2 {
    font-size: 1.5rem; /* Slightly larger heading for emphasis */
    color: #570987; /* Lavender color */
    margin-bottom: 20px;
}

.form .form-group {
    margin-bottom: 15px;
}

.form .form-control {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #cccccc; /* Light gray border */
    border-radius: 4px; /* Slightly rounded input edges */
    box-sizing: border-box; /* Make padding inside the input box */
}

.form .form-control:focus {
    border-color: #570987; /* Lavender border on focus */
    outline: none; /* Remove default focus outline */
}

.form .btn {
    background-color: #570987;
    color: #ffffff;
    font-size: 1rem;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form .btn:hover {
    background-color: #45076a; /* Darker purple on hover */
}

.form .text-danger {
    margin-top: 10px;
}
    </style>
</head>
<body>
<header>
    <img src="images/logo.png" alt="System Logo">
</header>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form method="POST">
                    <h2 class="text-center">Reset Password</h2>
                    <?php if (isset($error)) { echo '<p class="text-danger text-center">'.$error.'</p>'; } ?>
                    <div class="form-group">
                        <input class="form-control" type="password" name="new_password" placeholder="New Password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control btn" type="submit" value="Reset Password">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
