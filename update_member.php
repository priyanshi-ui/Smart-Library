<?php
include ('database.php');

$member_id = "";
$member_name = "";
$member_email = "";
$member_contact = "";
$member_address = "";
$institute_name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $member_name = $_POST['member_name'];
    $member_email = $_POST['member_email'];
    $member_contact = $_POST['member_contact'];
    $member_address = $_POST['member_address'];
    $institute_name = $_POST['institute_name'];

    $stmt = $conn->prepare("UPDATE member SET m_name = ?, m_email = ?, m_contact = ?, m_address = ?, institute_name = ? WHERE member_id = ?");
    $stmt->bind_param("sssssi", $member_name, $member_email, $member_contact, $member_address, $institute_name, $member_id);

    if ($stmt->execute()) {
        echo "<script>
                        alert('Member record Updated successfully!');
                        window.location.href = 'member.php';
                      </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} elseif (isset($_GET['id'])) {
    $member_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM member WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $member_id = $row['member_id'];
        $member_name = $row['m_name'];
        $member_email = $row['m_email'];
        $member_contact = $row['m_contact'];
        $member_address = $row['m_address'];
        $institute_name = $row['institute_name'];
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Member</title>
    <link rel="stylesheet" href="dashboard_style.css">
    
    <style>
.edit-btn {
    background-color: #570987;
    color: #fff;
    border: none;
    padding: 8px 20px;
    font-size: 14px;
    cursor: pointer;
}

.edit-btn:hover{
    background-color: #45076a;
}

.delete-btn, .return-btn {
    background-color: #570987;
    color: #fff;
    border: none;
    padding: 8px 20px;
    font-size: 14px;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: #45076a;
}

.btn-link {
    text-decoration: none;

}

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
    text-size:30px;
	color: #570987;
	font-weight: bold;
}

.member-form input[type="text"],
.member-form input[type="password"]{
    width: 100%;
    padding: 10px;
    margin-top:10px;
    margin-bottom: 25px ;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 5px;
}

.member-form input[type="submit"]  {
    background-color: #570987;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 5px; 
    font-size: 16px;
    margin-left:45%;
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
    
.member-form input[type="submit"]:hover {
    background-color: #45076a;
}

.member-form label {
    font-weight: bold;
}

span {
    color: red;
}}

    </style>
</head>

<body>
    <header class="head">
        <div class="logosec">
            <div class="logo">
                <img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;">
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
                            <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                            <h3> Dashboard</h3>
                        </div>
                    </a>

                    <a href="book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option ">
                            <img src="images/book.png" class="nav-img" alt="articles">
                            <h3>Books</h3>
                        </div>
                    </a>

                    <a href="member.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option3 dropdown">
                        <img src="images/member.png" class="nav-img" alt="report">
                        <h3> Members</h3>
                    </div>
                 </a>

                    <a href="category.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="institution">
                            <h3> Catalogue</h3>
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

                    <div class="nav-option option6">
                        <img src="images/Complains.png" class="nav-img" alt="settings">
                        <h3> Complain</h3>
                    </div>

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
            <form id="memberForm" method="POST" action="#" onsubmit="return validateForm()">
                    <h2>Update Member</h2>
                    
                    <input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id; ?>">

                    <label for="member_name">Member Name:</label><br>
                    <input type="text" id="member_name" name="member_name" value="<?php echo $member_name; ?>" placeholder="Enter Member Name">
                    <span class="error" id="nameError"></span>

                    <label for="member_email">Member Email:</label><br>
                    <input type="text" id="member_email" name="member_email" value="<?php echo $member_email; ?>" placeholder="Enter Member Email">
                    <span class="error" id="emailError"></span>

                    <label for="member_contact">Member Contact:</label><br>
                    <input type="text" id="member_contact" name="member_contact" value="<?php echo $member_contact; ?>" placeholder="Enter Member Contact">
                    <span class="error" id="contactError"></span>

                    <label for="member_address">Member Address:</label><br>
                    <input type="text" id="member_address" name="member_address" value="<?php echo $member_address; ?>" placeholder="Enter Member Address">
                    <span class="error" id="addressError"></span>

                    <label for="institute_name">Institute Name:</label><br>
                    <input type="text" id="institute_name" name="institute_name" value="<?php echo $institute_name; ?>" placeholder="Enter Institute Name">
                    <span class="error" id="instituteError"></span>

                        <input type="submit" name="submit" value="Update">
                        <a href="member.php">
                            <input type="button" class="button" value="Cancel">
                        </a>
                    
                </form>
            </div>
        </div>

        <script src="dashboard.js"></script>
        <script>
               

        function validateForm() {
            var name = document.getElementById("member_name").value.trim();
            var email = document.getElementById("member_email").value.trim();
            var contact = document.getElementById("member_contact").value.trim();
            var address = document.getElementById("member_address").value.trim();
            var institute = document.getElementById("institute_name").value.trim();

            var isValid = true;

            // Name validation
            if (name === "") {
                document.getElementById("nameError").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("nameError").style.display = "none";
            }

            // Email validation
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === "" || !emailPattern.test(email)) {
                document.getElementById("emailError").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("emailError").style.display = "none";
            }

            // Contact validation
            if (contact === "" || isNaN(contact)) {
                document.getElementById("contactError").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("contactError").style.display = "none";
            }

            // Address validation
            if (address === "") {
                document.getElementById("addressError").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("addressError").style.display = "none";
            }

            // Institute validation
            if (institute === "") {
                document.getElementById("instituteError").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("instituteError").style.display = "none";
            }

            return isValid;
        }

        document.getElementById("memberForm").addEventListener("submit", function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
        </script>
</body>

</html>
