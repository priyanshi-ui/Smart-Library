<?php
session_start();
include('database.php');

if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

$logged_in_member_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Books</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
</head>
<body>
    <header class="head">
        <div class="logosec">
            <div class="logo">
                <img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;">
            </div>
            <img src="images/Menu.png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>
        
        <div class="searchbar">
            <form method="post" id="searchForm">
                <input type="text" name="search" id="searchInput" placeholder="Search">
            </form>
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
                    <a href="member_dashboard.php" style="text-decoration:none; color:black;">
                        <div class="nav-option">
                            <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                            <h3>Dashboard</h3>
                        </div>
                    </a>

                    <a href="member_book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="articles">
                            <h3>Books</h3>
                        </div>
                    </a>

                    <a href="member_reserve_book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="articles">
                            <h3>Reserve Book</h3>
                        </div>
                    </a>

                    <a href="member_issue_book.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option3">
                            <img src="images/issue_book.png" class="nav-img" alt="report">
                            <h3>Issue Book</h3>
                        </div>
                    </a>

                    <a href="member_return_book.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="institution">
                            <h3>Return Book</h3>
                        </div>
                    </a>

                    <a href="member_fine.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option5">
                        <img src="images/fines.png" class="nav-img" alt="blog">
                        <h3>Fine</h3>
                    </div>
                </a>
                <a href="submit_complaint.php" style="text-decoration:none; color:black;">
                    <div class="nav-option option6">
                        <img src="images/Complains.png" class="nav-img" alt="settings">
                        <h3>Complain</h3>
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
            <div class="issued-books">
                <h2>Books Returned by You</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Book Title</th>
                            <th>Date of Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch books returned by the logged-in member
                        $fetch_returned_books_query = "SELECT book.book_title, book.book_image, return_book.date_of_return 
                            FROM book 
                            JOIN return_book ON book.book_id = return_book.book_id 
                            WHERE return_book.member_id = '$logged_in_member_id'";
                        $returned_books_result = $conn->query($fetch_returned_books_query);

                        if ($returned_books_result->num_rows > 0) {
                            while ($row = $returned_books_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><img src='" . $row['book_image'] . "' alt='Book Image' style='width: 100px; height: 100px;'></td>";
                                echo "<td>" . $row['book_title'] . "</td>";
                                echo "<td>" . $row['date_of_return'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No books returned</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
