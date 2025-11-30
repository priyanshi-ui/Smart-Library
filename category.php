<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: registration.php"); // Redirect to login/registration page
    exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="dashboard_style.css">
	<link rel="stylesheet" href="manage.css">

<title>View Books by Domain</title>
<style>


    h2 {
        margin-top: 20px;
        text-align: center;
        color: #570987;
	font-weight: bold;
    }

    form {
        width: 100%;
        text-align:center;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom:30px;
        color: #570987;
        font-size:25px;
    }

    select, input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 20px;
        box-sizing: border-box; /* Ensure padding and border are included in width */
    }

select option:selected {
    background-color: #570987; /* Purple color for selected option */
    color: white; /* Text color for selected option */
}

    input[type="submit"] {
        background-color: #570987;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #7d0dc3;
    }
</style>
</head>
<body>
 	<header class="head">
		<div class="logosec">
		<div class="logo">
			<img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;" ></div>
			<img src="images/Menu.png" class="icn menuicn" id="menuicn" alt="menu-icon">
		</div>

		<div class="searchbar">
			<input type="text" placeholder="Search">
			<div class="searchbtn">
			<img src="images/search.png" class="icn srchicn" alt="search-icon">
			</div>
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

			<div>

<form method="post" action="">
    <label for="domain">Select Category:</label>
    <select id="domain" name="domain">
        <option value="">Select Category</option>
        <?php
        include ('database.php');
        $sql = "SELECT DISTINCT domain_name FROM book";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["domain_name"] . "'>" . $row["domain_name"] . "</option>";
            }
        } else {
            echo "<option value='' disabled>No domains found</option>";
        }

        $conn->close();
        ?>
    </select><br>
    <input type="submit" name="submit" value="View Books">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['domain'])) {
        echo "<p style='color:red; text-align:center;'>Please select a domain.</p>";
    } else {
        $selectedDomain = $_POST['domain'];

        include ('database.php');

        function displayBooksByDomain($conn, $domain) {
            $sql = "SELECT * FROM book WHERE domain_name='$domain'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Books in $domain</h2>";
                echo "<table>";
                echo "<tr><th>Book Title</th><th>Edition</th><th>ISBN</th><th>Author</th><th>No of Copy</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["book_title"] . "</td>";
                    echo "<td>" . $row["book_edition"] . "</td>";
                    echo "<td>" . $row["book_isbn"] . "</td>";
                    echo "<td>" . $row["author_name"] . "</td>";
                    echo "<td>" . $row["no_of_copy"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No books found in $domain.</p>";
            }
        }

        displayBooksByDomain($conn, $selectedDomain);

        $conn->close();
    }
}
?>
 <script src="dashboard.js"></script>
</div>
</div>
</body>
</html>
