<?php
session_start();
include 'database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];

        // Check if OTP matches session OTP
        if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
            // Retrieve registration data from session
            if (!isset($_SESSION['register'])) {
                echo "Session expired. Please register again.";
                exit();
            }

            $data = $_SESSION['register'];
            $email = $data['email'];
            $name = $data["name"];
            $phone = $data["phone"];
            $address = $data["address"];
            $user_type = $data["user_type"];
            $institute = isset($data["institute"]) ? $data["institute"] : "";
            $password = $data["password"]; // Already hashed
            $gender = $data["gender"];

            if ($user_type == "Member") {
                $sql = "INSERT INTO member (m_name, m_email, m_address, m_contact, m_gender, institute_name, m_password, last_login, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1)";
            } else {
                $sql = "INSERT INTO librarian (l_name, l_email, l_address, l_contact, l_gender, l_password, last_login) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
            }

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                if ($user_type == "Member") {
                    $stmt->bind_param("sssssss", $name, $email, $address, $phone, $gender, $institute, $password);
                } else {
                    $stmt->bind_param("ssssss", $name, $email, $address, $phone, $gender, $password);
                }

                if ($stmt->execute()) {
                    $_SESSION["user_id"] = $stmt->insert_id;
                    $_SESSION["email"] = $email;
                    $_SESSION["user_type"] = $user_type;

                    // Cleanup session variables
                    unset($_SESSION['otp'], $_SESSION['register']);

                    // Redirect user
                    header("Location: " . ($user_type == "Member" ? "member_dashboard.php" : "librarian_dashboard.php"));
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            $otpError = "Invalid OTP. Please try again.";
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #c49fe8, #8a63d2);
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column; /* Stack the header and OTP container vertically */
            background-image: url('background/6.jpg');
        }

        /* Apply the blur effect to the background */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit; /* Inherit background from body */
            filter: blur(8px); /* Apply blur effect */
            z-index: -1; /* Make sure it stays behind the content */
        }
        header {
            width: 100%;
            padding: 20px;
            background-color: #fff;
            display: flex;
            justify-content: flex-end; /* Aligns the logo to the right */
            align-items: center;
        }

        header img {
            width: 120px;
            height: auto;
        }

        .otp-container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: 20px auto; /* Ensures the container is centered and not too close to the top */
        }

        .otp-container h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #570987;
        }

        .otp-container .form-control {
            margin-bottom: 20px;
            border-color: #45076a;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .otp-container .btn-primary {
            width: 50%;
            background-color: #570987;
            border-color: #570987;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }

        .otp-container .btn-primary:hover {
            background-color: #45076a;
        }

        .otp-container .error {
            color: red;
            margin-top: 10px;
            font-weight: bold;
        }

        .btn-secondary {
            width: 50%;
            background-color: #D22B2B;
            border-color: #D22B2B;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            margin-top:5px;
        }

        .btn-secondary:hover {
            background-color: #960018;
        }

    </style>
</head>
<body>

   
    <header>
        <img src="images/logo.png" alt="System Logo">
    </header>

    <div class="otp-container">
        <h2>OTP Verification</h2>
        <form action="otp_verification_registration.php" method="post">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP:</label>
                <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP">
                 <?php if (isset($otpError)) { echo '<p class="error">' . $otpError . '</p>'; } ?> 
            </div>

            <button type="submit" class="btn btn-primary" name="verify_otp">Verify OTP</button>
            <a href="registration.php">
                <button type="button" class="btn btn-secondary">Back</button>
            </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 