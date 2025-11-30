<?php
include('database.php'); // Include your database connection
session_start(); // Start session to access logged-in user details

// Ensure the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    //header("Location: login.php");
    //exit();
}

// Get the logged-in user's ID
$member_id = $_SESSION['user_id']; // Make sure to use the correct session variable

// Pagination settings
$limit = 5; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch total number of reserved books
$sql_count = "SELECT COUNT(*) as total FROM book_reserve WHERE member_id = '$member_id'";
$result_count = $conn->query($sql_count);
$total_books = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_books / $limit); // Calculate total pages

// Fetch reserved books for the logged-in member, including book image
$sql = "SELECT book.book_id, book.book_title, book.book_isbn, book.author_name, book.book_image, book_reserve.reserve_date 
        FROM book 
        INNER JOIN book_reserve ON book.book_id = book_reserve.book_id 
        WHERE book_reserve.member_id = '$member_id'
        LIMIT $limit OFFSET $offset"; // Apply pagination
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Books</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    <style>
        /* Your existing styles remain here... */

        h1 {
            text-align: center;
            color: #45076a;  /* Heading color */
        }

        table {
            width: 100%; /* Slightly wider table */
            margin: 10px ;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #570987; /* Header color */
            color: white; /* Text color for headers */
        }

        tr:hover {
            background-color: #f5f5f5; /* Hover effect for rows */
        }

        .no-books {
            text-align: center;
            font-size: 18px;
            color: red;
            margin-top: 20px;
        }

        .book-image {
            width: 40%; /* Adjust width as needed */
            height: auto; /* Maintain aspect ratio */
            border-radius: 5px; /* Slightly round the corners */
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #45076a;
            color: white;
            border: 1px solid #45076a;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
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
            <h1>My Reserved Books</h1>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th> <!-- New column for images -->
                            <th>Title</th>
                            <th>ISBN</th>
                            <th>Author</th>
                            <th>Reservation Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo $row['book_image']; ?>" alt="Book Image" class="book-image"></td> <!-- Display book image -->
                                <td><?php echo $row['book_title']; ?></td>
                                <td><?php echo $row['book_isbn']; ?></td>
                                <td><?php echo $row['author_name']; ?></td>
                                <td><?php echo $row['reserve_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='member_reserve_book.php?page=" . ($page - 1) . "'>&laquo; Previous</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='member_reserve_book.php?page=" . $i . "' class='" . ($page == $i ? "active" : "") . "'>" . $i . "</a>";
                }

                if ($page < $total_pages) {
                    echo "<a href='member_reserve_book.php?page=" . ($page + 1) . "'>Next &raquo;</a>";
                }
                ?>
                </div>

            <?php else: ?>
                <p class="no-books">You have not reserved any books yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
<script src="dashboard.js"></script>
</html>

<?php
$conn->close(); // Close database connection
?>
