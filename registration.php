<?php
session_start();
include("database.php");

// Display success message for password reset
if (isset($_GET['status']) && $_GET['status'] === 'PasswordResetSuccess') {
    $success = "Your password has been successfully reset.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User Registration
    if (isset($_POST['register'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
        $institute = filter_input(INPUT_POST, 'institute', FILTER_SANITIZE_STRING);
        $password = $_POST["password"];
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);

        // Validate password strength
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter and one number.'); window.location.href='registration.php';</script>";
            exit();
        }

        // Secure password hashing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if email is already registered
        $emailCheckSql = "SELECT 1 FROM member WHERE m_email = ? UNION SELECT 1 FROM librarian WHERE l_email = ?";
        $stmt = $conn->prepare($emailCheckSql);
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Email already registered. Use a different email.'); window.location.href='registration.php';</script>";
            exit();
        } else {
            $_SESSION['register'] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'user_type' => $user_type,
                'institute' => $institute,
                'password' => $hashed_password, // Store hashed password
                'gender' => $gender
            ];

            $_POST['otp_type'] = 'registration';
            require 'send_otp.php'; // Send OTP for email verification
            exit();
        }
    }

    // Login Logic
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $loginError = "Invalid email format.";
    } else {
        // Secure login query
        $sql = "SELECT member_id as id, m_password as password, status, last_login, 'Member' AS user_type 
                FROM member WHERE m_email = ? 
                UNION 
                SELECT librarian_id as id, l_password as password, 'active' AS status, NULL AS last_login, 'Librarian' AS user_type 
                FROM librarian WHERE l_email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            $status = $row['status'];
            $user_type = $row['user_type'];
            $id = $row['id'];
            $last_login = $row['last_login'];

            // Ensure stored password is hashed
            if (!password_get_info($hashed_password)['algo']) {
                error_log("WARNING: Password is not hashed! Rehashing required.");
                $hashed_password = password_hash($hashed_password, PASSWORD_BCRYPT);
                $update_password = "UPDATE member SET m_password = ? WHERE member_id = ?";
                $stmt = $conn->prepare($update_password);
                $stmt->bind_param("si", $hashed_password, $id);
                $stmt->execute();
            }

            // Auto-disable inactive users (1+ year of inactivity)
            if ($last_login !== null && strtotime($last_login) < strtotime('-1 year')) {
                $update_status = "UPDATE member SET status = 0 WHERE member_id = ?";
                $stmt = $conn->prepare($update_status);
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                }
                $status = 0;
            }

            // Check account status
            if ($user_type == 'Member' && $status == 0) {
                $loginError = "Your account is deactivated. Please contact the librarian.";
            } elseif (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["email"] = $email;
                $_SESSION["user_type"] = $user_type;

                // Rehash password if needed
                if (password_needs_rehash($hashed_password, PASSWORD_BCRYPT)) {
                    $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $update_password = "UPDATE member SET m_password = ? WHERE member_id = ?";
                    $stmt = $conn->prepare($update_password);
                    $stmt->bind_param("si", $new_hashed_password, $id);
                    $stmt->execute();
                }

                // Update last login time
                $update_last_login = "UPDATE member SET last_login = NOW() WHERE member_id = ?";
                $stmt = $conn->prepare($update_last_login);
                $stmt->bind_param("i", $id);
                $stmt->execute();

                // Redirect based on user type
                header("Location: " . ($user_type == 'Member' ? "member_dashboard.php" : "librarian_dashboard.php"));
                exit();
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "Invalid credentials.";
        }
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & SignUp Form</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <style>* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

html, body {
  display: grid;
  width: 100%;
  height: 100%;
}

.video-background {
  position: fixed;
  right: 0;
  bottom: 0;
  min-width: 100%;
  min-height: 100%;
  z-index: -1;
}

.form1 {
  position: relative;
  z-index: 1;
  background: rgba(255, 255, 255, 0.7);
  max-width: 500px;
  margin: 50px auto;
  padding: 30px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0px 0px 10px 0px #bc9cc8;
}

.form1 .title-text,
.form-box .form-inner {
  display: flex;
  width: 200%;
}

.form1 .title-text .title {
  width: 50%;
  font-size: 35px;
  font-weight: 600;
  text-align: center;
  transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.form-box {
  width: 100%;
  overflow: hidden;
}

.slide-controls {
  position: relative;
  display: flex;
  height: 50px;
  width: 100%;
  overflow: hidden;
  border-radius: 5px;
  margin: 20px 0 7px;
  justify-content: space-between;
  border: none;
}

.slide {
  color: #fff;
  height: 100%;
  width: 100%;
  z-index: 1;
  font-size: 22px;
  font-weight: 500;
  text-align: center;
  line-height: 50px;
  cursor: pointer;
  transition: all 0.6s ease;
}

.signup {
  color: #000;
}

.slide-tab {
  position: absolute;
  height: 100px;
  width: 50%;
  left: 0;
  border-radius: 5px;
  z-index: 0;
  background: -webkit-linear-gradient(left, #b4a7d6, #6a329f);
  transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

input[type="checkb"] {
  display: none;
}

#signup:checked ~ .slide-tab {
  left: 50%;
}

#signup:checked ~ .signup {
  color: #fff;
}

#signup:checked ~ .login {
  color: #000;
}

.form-box .form-inner form {
  width: 50%;
  transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.form-inner form .field {
  height: auto; /* Fixed height for fields */
  width: 100%; /* Full width */
  margin-top: 20px;
  box-sizing: border-box; /* Ensures padding doesn't affect width */
  transition: margin-top 0.3s ease; /* Smooth transition when adjusting */
}
.form-inner form .field input,
.form-inner form .field select {
  width: 100%;
  height: 35px;
  outline: none;
  font-size: 17px;
  padding-left: 15px;
  border-radius: 5px;
  border: 1px solid lightgrey;
  border-bottom-width: 2px;
  transition: all 0.4s ease;
}

.form-inner form .field input:focus {
  border-color: #6a329f;
}

.pass-link,
.signup-link {
  margin-top: 5px;
}

.pass-link a,
.signup-link a {
  color: #6a329f;
  text-decoration: none;
}

.signup-link {
  text-align: center;
  margin-top: 30px;
}

.pass-link a:hover,
.signup-link a:hover {
  text-decoration: underline;
}

form .field input[type="submit"] {
  color: #fff;
  font-size: 25px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  background: -webkit-linear-gradient(left, #b4a7d6, #6a329f);
}

form .field input[type="radio"] {
  width: 18px;
  height: 12px;
  cursor: pointer;
}

.radio {
  visibility: hidden;
  display: none;
}

span {
  color: red;
}

#userImage {
  display: block;
  margin: 0 auto; /* Center the image horizontally */
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #6a329f;
  margin-bottom: 0; /* Removes any bottom margin */
}

 /* Header with logo */
 header {
  width: 100%;
  padding: 20px;
  background-color: #fff;
  display: flex;
  justify-content: flex-start; /* Aligns items to the right */
  align-items: center;
  height:60px;
  opacity:0.7;
}

header img {
  width: 120px;
  height: auto;
}

/* Adjust for smaller screens */
@media screen and (max-width: 500px) {
  #userImage {
    width: 80px;
    height: 80px;
  }
}
  </style>
</head>

<body>

<header>
    <img src="images/logo.png" alt="System Logo">
  </header>
<video autoplay muted loop id="myVideo" class="video-background">
    <source src="images/book.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
  </video>

  <div class="form1">
   <div class="title-text">
    <div class="title login">Login</div>
    <div class="title signup">Signup</div>
  </div>

    <div class="form-box">
      <div class="slide-controls">
        <input class="radio" type="radio" name="slider" id="login" checked>
        <input class="radio" type="radio" name="slider" id="signup">
        <label for="login" class="slide login">Login</label>
        <label for="signup" class="slide signup">Signup</label>
        <div class="slide-tab"></div>
      </div>

      <div class="form-inner">
        <form action="#" method="post" class="login">
          <div class="field">
            <input type="text" placeholder="Email Address" name="email" >
          </div>
          <div class="field">
            <input type="password" placeholder="Password" name="password" >
          </div>
          <div class="field">
                <a href="forgot_password.php">Forgot Password??</a>
          </div>
          <div class="field">
            <input type="submit" value="Login">
          </div>
          <?php if (isset($loginError)): ?>
    <p style="color: red;"><?php echo $loginError; ?></p>
<?php endif; ?>
          <div class="signup-link">
            Not a member? <a href="#">Signup now</a>
          </div>
        </form>
        
        <form action="registration.php" method="post" onsubmit="return validateForm()" class="signup">
  <!-- Add the image before fields -->
  <div class="field">
    <img id="userImage" alt="User Image" style="display: none;">
  </div>
  
  <!-- Remaining form fields -->
  <div class="field">
    <input type="text" placeholder="First Name" name="name" id="name">
    <span id="nameErr"></span>
  </div>
  <div class="field">
    <input type="text" placeholder="Email Address" name="email" id="email">
    <span id="emailErr"></span>
  </div>
  <div class="field">
    <input type="text" placeholder="Phone Number" name="phone" id="phone" maxlength="10">
    <span id="mobileErr"></span>
  </div>
  <div class="field">
    <input type="text" placeholder="Address" name="address">
  </div>
  
  <!-- User Type Field -->
  <div class="field">
    <select id="user_type" name="user_type" onchange="showInstituteField(this.value)">
      <option value="">Select User Type</option>
      <option value="Member">Member</option>
      <option value="Librarian">Librarian</option>
    </select>
    <span id="user_typeErr"></span>
  </div>
  
  <!-- Institute field for Members -->
  <div class="field" id="instituteField" style="display: none;">
    <input type="text" placeholder="Institute Name" name="institute" id="institute">
    <span id="instituteErr"></span>
  </div>
  
  <!-- Gender Selection -->
  <div class="field" style="font-size:18px; font-weight: bold; padding: 3px;">
    <label>Gender:</label>&nbsp;
   <input type="radio" value="M" name="gender"> Male&emsp;
    <input type="radio" value="F" name="gender">Female&emsp;
    <input type="radio" value="O" name="gender">Other
  </div>
  <span id="genderErr"></span>
  
  <!-- Password fields -->
  <div class="field">
    <input type="password" placeholder="Password" name="password" id="password">
    <span id="passwordErr"></span>
  </div>
  <div class="field">
    <input type="password" placeholder="Confirm password" name="cpassword" id="cpassword">
    <span id="cpasswordErr"></span>
  </div>
  
  <!-- Submit button -->
  <div class="field">
    <input type="submit" name="register" value="Signup">
  </div>
</form>

      </div>
    </div>
  </div>
</body>
<script>
function showInstituteField(userType) {
  if (userType === "Member") {
    document.getElementById("instituteField").style.display = "block";
  } else {
    document.getElementById("instituteField").style.display = "none";
  }
}

const loginForm = document.querySelector("form.login");
const signupForm = document.querySelector("form.signup");
const loginBtn = document.querySelector("label.login");
const signupBtn = document.querySelector("label.signup");
const signupLink = document.querySelector(".signup-link a");
const loginText = document.querySelector(".title-text .login");
const signupText = document.querySelector(".title-text .signup");

signupBtn.onclick = () => {
  loginForm.style.marginLeft = "-50%";
  loginText.style.marginLeft = "-50%";
};

loginBtn.onclick = () => {
  loginForm.style.marginLeft = "0%";
  loginText.style.marginLeft = "0%";
};

signupLink.onclick = () => {
  signupBtn.click();
};

$(document).ready(function(){
  $('#registrationForm').submit(function(event){
    event.preventDefault();
    
    var formData = $(this).serialize();
    
    $.ajax({
      url: 'check_email.php',
      method: 'POST',
      data: formData,
      success: function(response) {
        if(response == 'Email already exists') {
          alert(response);
        } else {
          $('#registrationForm')[0].submit();
        }
      }
    });
  });
});

// Validate input
function validateForm() {
  var name = document.getElementById("name").value;
  var email = document.getElementById("email").value;
  var phone = document.getElementById("phone").value;
  var gender = document.querySelector('input[name="gender"]:checked');
  var password = document.getElementById("password").value;
  var cpassword = document.getElementById("cpassword").value;
  var user_type = document.getElementById("user_type").value;
  var institute = document.getElementById("institute").value;

  var nameErr = document.getElementById("nameErr");
  var emailErr = document.getElementById("emailErr");
  var mobileErr = document.getElementById("mobileErr");
  var genderErr = document.getElementById("genderErr");
  var passwordErr = document.getElementById("passwordErr");
  var cpasswordErr = document.getElementById("cpasswordErr");
  var user_typeErr = document.getElementById("user_typeErr");
  var instituteErr = document.getElementById("instituteErr");

 var isValid = true;
/*
 // Reset error messages
 nameErr.innerHTML = "";
  emailErr.innerHTML = "";
  mobileErr.innerHTML = "";
  genderErr.innerHTML = "";
  passwordErr.innerHTML = "";
  cpasswordErr.innerHTML = "";
  user_typeErr.innerHTML = "";
  instituteErr.innerHTML = "";

 if (name.trim() === "" && email.trim() === "" && phone.trim() === "" && !gender && password.trim() === "" && cpassword.trim() === "" && user_type === "" && (user_type === "Member" && institute.trim() === "")) {
    alert("Please fill in all the required fields.");
    return false; // Prevent form submission
  }*/
  // Name validation
  if (name.trim() === "") {
    nameErr.innerHTML = "*Name is required";
    isValid = false;
  } else if (!/^[a-zA-Z ]*$/.test(name)) {
    nameErr.innerHTML = "*Only letters are allowed";
    isValid = false;
  } else {
    nameErr.innerHTML = "";
  }

  // Email validation
  if (email.trim() === "") {
    emailErr.innerHTML = "*Email is required";
    isValid = false;
  } else if (!/^\S+@\S+\.\S+$/.test(email)) {
    emailErr.innerHTML = "*Invalid email format";
    isValid = false;
  } else {
    emailErr.innerHTML = "";
  }

  // Mobile number validation
  if (phone.trim() === "") {
    mobileErr.innerHTML = "*Phone number is required";
    isValid = false;
  } else if (!/^(?![0-5])\d{10}$/.test(phone)) {
    mobileErr.innerHTML = "*Invalid mobile number format (must start with 6, 7, 8, or 9)";
    isValid = false;
  } else {
    mobileErr.innerHTML = "";
  }

  // Gender validation
  if (!gender) {
    genderErr.innerHTML = "*Gender is required";
    isValid = false;
  } else {
    genderErr.innerHTML = "";
  }

  // Password validation
  if (password.trim() === "") {
    passwordErr.innerHTML = "*Password is required";
    isValid = false;
  } else if (password.length < 6) {
    passwordErr.innerHTML = "*Password must be at least 6 characters";
    isValid = false;
  } else {
    passwordErr.innerHTML = "";
  }

  // Confirm password validation
  if (cpassword.trim() === "") {
    cpasswordErr.innerHTML = "*Confirm password is required";
    isValid = false;
  } else if (password !== cpassword) {
    cpasswordErr.innerHTML = "*Passwords do not match";
    isValid = false;
  } else {
    cpasswordErr.innerHTML = "";
  }

  // User type validation
  if (user_type === "") {
    user_typeErr.innerHTML = "*User type is required";
    isValid = false;
  } else {
    user_typeErr.innerHTML = "";
  }

  // Institute name validation
  if (user_type === "Member" && institute.trim() === "") {
    instituteErr.innerHTML = "*Institute name is required for Members";
    isValid = false;
  } else {
    instituteErr.innerHTML = "";
  }

  return isValid;
}
function showInstituteField(userType) {
  var genderInputs = document.querySelectorAll('input[name="gender"]');
  var userImage = document.getElementById("userImage");

  // Show the institute field and user image only when "Member" is selected
  if (userType === "Member") {
    document.getElementById("instituteField").style.display = "block";
    userImage.src = "uploads/user.png";
    userImage.style.display = "block"; // Show the image when the user type is "Member"
  } else {
    document.getElementById("instituteField").style.display = "none";
    userImage.style.display = "none"; // Hide the image for non-members
  }

  // Attach change event listeners for gender inputs only when user type is "Member"
  genderInputs.forEach(function(input) {
    input.addEventListener('change', function() {
      if (userType === "Member" && input.checked) {
        if (input.value === "M") {
          userImage.src = "uploads/man.png"; // Path to male image
        } else {
          userImage.src = "uploads/woman.png"; // Path to female image
        } 
      }
    });
  });
}

// Initially hide the image
document.getElementById("userImage").style.display = "none";

</script>
</html>
