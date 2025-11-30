<?php 
if (isset($_GET['error'])) { 
    // Display error message as an alert using JavaScript
    echo "<script>alert('{$_GET['error']}');</script>";
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Set the background image and apply blur */
        body {
            background-image: url('background/6.jpg'); /* Update with your image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            height: 100vh; /* Ensure the body takes full height */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
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

        .container {
            width: 100%;
            max-width: 500px;
        }

        .form {
            background-color: rgba(255, 255, 255, 0.9); /* Add some transparency */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h2 {
            color: #570987;
        }

        p {
            color: #7a7a7a;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #dcdcdc;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #570987;
            box-shadow: 0 0 5px rgba(106, 90, 205, 0.5);
        }

        .button {
            background-color: #570987;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #45076a;
        }

        .text-center {
            margin-bottom: 15px;
        }

        .header {
        position: fixed; /* Keeps the header at the top while scrolling */
        top: 0;
        width: 100%;
        background-color: #ffffff; /* White background */
        padding: 5px 20px;
        display: flex;
        justify-content: space-between; /* Space between logo and links */
        align-items: center;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
        opacity: 0.7; /* Slight transparency */
        z-index: 1000; /* Ensures it stays above other elements */
    }

    .header img {
        height: 70px; /* Logo size */
    }

    .header .nav-links {
        display: flex; /* Align links horizontally */
        gap: 20px; /* Spacing between links */
    }

    .header a {
        color: #570987; /* Lavender color for links */
        text-decoration: none;
        font-weight: bold;
        font-size: 1rem; /* Adjust font size */
        transition: color 0.3s ease; /* Smooth color change on hover */
    }

    .header a:hover {
        color: #483d8b; /* Darker purple for hover */
        text-decoration:underline;
    }
    </style>
</head>

<body>
    <!-- Header with logo and login link -->
    <div class="header">
        <a href="login.php">
            <img src="images/logo.png" alt="System Logo"> <!-- Update with your logo path -->
        </a>
        <a href="registration.php">Back to Login</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form">
                    <form action="send_otp.php" method="POST">
                        <h2 class="text-center">Forgot Password</h2>
                        <b><p class="text-center" style="color:black;">Enter your email address</p></b>
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" placeholder="Enter email address" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="otp_type" value="forgot_password">
                            <input class="form-control button" type="submit" value="Continue">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
