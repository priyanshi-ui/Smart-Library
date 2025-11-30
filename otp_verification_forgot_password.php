<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if the entered OTP matches the generated one
    if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
        
        // Redirect to reset password page for forgot password
        header('Location: reset_password.php');
        exit();
    } else {
        // If OTP doesn't match, show error and redirect back to OTP page
        header('Location: otp_verification_forgot_password.php?error=Invalid OTP');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP for Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Function to show an alert if there's an error message in the URL
        function showErrorAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get('error');
            if (errorMessage) {
                alert(errorMessage);
            }
        }
    </script>
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
/* Styles for OTP Verification Section */
.otp-verify-section {
    width: 100%;
    max-width: 500px; /* Maximum width for better centering */
    margin: 0 auto; /* Center align the form */
    padding: 20px;
    background-color: #ffffff; /* White background */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    /*opacity: 0.9;  Slight transparency */
    text-align: center;
}

.otp-verify-section h2 {
    font-size: 1.5rem; /* Slightly larger heading for emphasis */
    color: #570987; /* Lavender color */
    margin-bottom: 10px;
}

.otp-verify-section p {
    font-size: 1rem; /* Standard paragraph font size */
    color: #555555; /* Dark gray text */
    margin-bottom: 20px;
}

.otp-verify-section .form-group {
    margin-bottom: 15px;
}

.otp-verify-section .form-control {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #cccccc; /* Light gray border */
    border-radius: 4px; /* Slightly rounded input edges */
    box-sizing: border-box; /* Make padding inside the input box */
}

.otp-verify-section .form-control:focus {
    border-color: #570987; /* Lavender border on focus */
    outline: none; /* Remove default focus outline */
}

.otp-verify-section .btn {
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

.otp-verify-section .btn:hover {
    background-color: #45076a; /* Darker purple on hover */
}

.otp-verify-section .cancel-btn {
    background-color: #D22B2B;/* Red color for cancel button */
    /*margin-top: 10px;*/
}

.otp-verify-section .cancel-btn:hover {
    background-color: #960018; /* Darker red on hover */
}
    </style>
</head>
<body onload="showErrorAlert()">
<header>
    <img src="images/logo.png" alt="System Logo">
  </header>
    <div class="container">
        <div class="row">
            <div class="otp-verify-section">
                <h2 class="text-center">Verify OTP for Forgot Password</h2>
                <p class="text-center" style="color:black;"><b>Enter the OTP sent to your email</b></p>
                <form action="otp_verification_forgot_password.php" method="post">
                    <div class="form-group">
                        <input class="form-control" type="text" name="otp" placeholder="Enter OTP" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn" value="Verify OTP" style="margin-right:20px;">
                        <a href="registration.php" class="btn cancel-btn">Cancel</a>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</body>
</html>
