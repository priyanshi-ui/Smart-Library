<?php
include ('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $institute_name = $_POST['institute_name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO member (m_name, m_email, m_address, m_contact, m_gender,institute_name, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $address, $contact,$gender, $institute_name, $password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Member added successfully!');
                window.location.href = 'member.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard_style.css">
    <title>Add Members</title>
    <style>
       .member-form {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
    margin: 0 auto;
}

.member-form h2 {
    margin-bottom: 20px;
    text-align:center;
    font-size:30px;
	color: #570987;
	font-weight: bold;
}

.input-container {
    position: relative;
    margin-bottom: 25px;
}

.input-container input[type="text"],
.input-container input[type="password"],
.input-container input[type="number"] {
    width: 100%;
    padding: 10px;
    padding-top: 10px; /* Extra space for the label */
    border: 2px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background: none;
    box-sizing: border-box;
}

.input-container label {
    position: absolute;
    top: 10px;
    left: 12px;
    color: #999;
    font-size: 16px;
    transition: 0.3s ease all;
    pointer-events: none; /* Makes the label unclickable */
}

.input-container input:focus + label,
.input-container input:not(:placeholder-shown) + label {
    top: -25px;
    font-size: 18px;
    color: #570987;
}

.input-container input:focus {
    border-color: #570987;
}

.member-form input[type="submit"] {
    background-color: #570987;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-left:45%;
}

.member-form input[type="submit"]:hover{
    background-color: #45076a;
}

.button {
        background-color: #D22B2B;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        margin-left:2%;
        border-radius: 4px;
        cursor: pointer;
    }
    .button:hover {
        background-color: #960018;
    }
.member-form label {
    font-weight: bold;
}

span {
    color: red;
}

.gender-container {
    margin-bottom: 15px;
}
    </style>
</head>
<body>
<header class="head">
            <div class="logosec">
                <div class="logo">
                    <img src="images/logo.png" class="logo" style="margin-top:20px;padding:2px; width: 150px; height: auto;">
                </div>
                <img src="images/Menu.png" class="icn menuicn" id="menuicn" alt="menu-icon">
            </div>
            <div class="dp">
                    <a href="profilepage.php">
                        <img src="images/profile.jpg" class="dpicn" alt="dp">
                    </a>
                </div>
        </header>

        <div class="main-container">
            <div class="navcontainer">
                <nav class="nav">
                <div class="nav-upper-options">
                    <a href="librarian_dashboard.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option1">
                            <img src="images/dashboard.png" class="nav-img" alt="Dashboard">
                            <h3>Dashboard</h3>
                        </div>
                    </a>
                    <a href="book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="Books">
                            <h3>Books</h3>
                        </div>
                    </a>
                    <a href="member.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option3 dropdown">
                            <img src="images/member.png" class="nav-img" alt="Members">
                            <h3>Members</h3>
                        </div>
                    </a>
                    <a href="category.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="Catalogue">
                            <h3>Catalogue</h3>
                        </div>
                    </a>
 
                    <div class="nav-option option6 dropdown">
                        <img src="images/member.png" class="nav-img" alt="report">
                        <h3> Issue/Return</h3>
                        <div class="dropdown-content">
                            <a href="view_issue_book.php">Issue</a>
                            <a href="view_return_book.php"> Return</a>
                        </div>
                    </div>

                    <a href="reserve_book.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="Catalogue">
                            <h3>Reserve</h3>
                        </div>
                    </a>

                    <a href="view_fine.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option5">
                        <img src="images/fines.png" class="nav-img" alt="blog">
                        <h3> Fine</h3>
                    </div></a>

                    <a href="report.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option5">
                        <img src="images/analytics.png" class="nav-img" alt="blog">
                        <h3> Report Generate</h3>
                    </div></a>
                    
                    <a href="view_complaints.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option6">
                        <img src="images/Complains.png" class="nav-img" alt="Complain">
                        <h3>Complain</h3>
                    </div>
</a>

<a href="index.php" style="text-decoration:none; color:black;">
                        <div class="nav-option logout">
                            <img src="images/log-out.png" class="nav-img" alt="logout">
                            <h3>Logout</h3>
                        </div>
                    </a>
             
                    </div>
                </nav>
            </div>
        
        <div class="main">
            <div class="member-form">
            <form id="memberForm" method="POST">
            <h2>Add Member</h2>

            <div class="input-container">
                <input type="text" id="name" name="name" placeholder=" ">
                <label for="name">Name:</label>
                <span id="nameErr"></span>
            </div>

            <div class="input-container">
                <input type="text" id="email" name="email" placeholder=" ">
                <label for="email">Email:</label>
                <span id="emailErr"></span>
            </div>

            <div class="input-container">
                <input type="text" id="address" name="address" placeholder=" ">
                <label for="address">Address:</label>
                <span id="addressErr"></span>
            </div>

            <div class="input-container">
                <input type="text" id="contact" name="contact" maxlength="10" placeholder=" ">
                <label for="contact">Contact:</label>
                <span id="mobileErr"></span>
            </div>

            <div class="input-container">
                <input type="text" id="institute_name" name="institute_name" placeholder=" ">
                <label for="institute_name">Institute Name:</label>
                <span id="instituteErr"></span>
            </div>

            <div class="input-container">
                <input type="password" id="password" name="password" placeholder=" ">
                <label for="password">Password:</label>
                <span id="passwordErr"></span>
            </div>

            <div class="input-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder=" ">
                <label for="confirm_password">Confirm Password:</label>
                <span id="confirmPasswordErr"></span>
            </div>

            <div class="gender-container">
                <label>Gender:</label>&nbsp;&nbsp;
                <input type="radio" id="male" name="gender" value="male"> 
                <label for="male">Male</label>&nbsp;
                <input type="radio" id="female" name="gender" value="female"> 
                <label for="female">Female</label>&nbsp;
                <input type="radio" id="other" name="gender" value="other"> 
                <label for="other">Other</label>&nbsp;
                <span id="genderErr"></span>
            </div>

            <input type="submit" value="Add">
            <a href="member.php">
                <input type="button" class="button" value="Cancel">
            </a>
        </form>
            </div>
        </div>
    </div>
    <script  src="dashboard.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("memberForm").addEventListener("submit", function(event) {
                event.preventDefault();

                var name = document.getElementById("name").value;
                var email = document.getElementById("email").value;
                var address = document.getElementById("address").value;
                var contact = document.getElementById("contact").value;
                var institute_name = document.getElementById("institute_name").value;
                var password = document.getElementById("password").value;

                var nameErr = document.getElementById("nameErr");
                var emailErr = document.getElementById("emailErr");
                var mobileErr = document.getElementById("mobileErr");
                var addressErr = document.getElementById("addressErr");
                var instituteErr = document.getElementById("instituteErr");
                var passwordErr = document.getElementById("passwordErr");

                nameErr.innerHTML = "";
                emailErr.innerHTML = "";
                mobileErr.innerHTML = "";
                addressErr.innerHTML = "";
                instituteErr.innerHTML = "";
                passwordErr.innerHTML = "";

                var isValid = true;

                // Name validation
                if (name.trim() === "") {
                    nameErr.innerHTML = "Please enter your name.";
                    isValid = false;
                } else if (!/^[a-zA-Z ]*$/.test(name)) {
                    nameErr.innerHTML = "Only letters are allowed";
                    isValid = false;
                }

                // Email validation
                if (email.trim() === "") {
                    emailErr.innerHTML = "Please enter a valid email address.";
                    isValid = false;
                } else if (!/^\S+@\S+\.\S+$/.test(email)) {
                    emailErr.innerHTML = "Invalid email format";
                    isValid = false;
                }

                // Contact validation
                if (contact.trim() === "") {
                    mobileErr.innerHTML = "Please enter your phone number.";
                    isValid = false;
                } else if (!/^(?![0-5])\d{10}$/.test(contact)) {
                    mobileErr.innerHTML = "Invalid mobile number format (must start with 6, 7, 8, or 9)";
                    isValid = false;
                }

                // Address validation
                if (address.trim() === "") {
                    addressErr.innerHTML = "Please enter your address.";
                    isValid = false;
                }

                // Institute name validation
                if (institute_name.trim() === "") {
                    instituteErr.innerHTML = "Please enter your institute name.";
                    isValid = false;
                }

                // Password validation
                if (password.trim() === "") {
                    passwordErr.innerHTML = "Please enter a password.";
                    isValid = false;
                } else if (password.length < 8 || password.length > 15) {
                    passwordErr.innerHTML = "Password must be between 8 and 15 characters long";
                    isValid = false;
                }

                if (isValid) {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>
