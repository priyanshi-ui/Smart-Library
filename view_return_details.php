<?php
include('database.php');

if (isset($_GET['book_id']) && is_numeric($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);

    // Fetch book details
    $sql_book = "SELECT book_title, book_image FROM book WHERE book_id = ?";
    $stmt = $conn->prepare($sql_book);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result_book = $stmt->get_result();
    $book = $result_book->fetch_assoc();

    // Fetch return details along with fines
    $sql_return = "SELECT member.m_name, return_book.date_of_return, fines.amount 
                   FROM return_book 
                   JOIN member ON return_book.member_id = member.member_id 
                   LEFT JOIN fines ON return_book.member_id = fines.fine_id 
                   WHERE return_book.book_id = ? 
                   LIMIT ?, ?";

    $results_per_page = 10;
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($current_page - 1) * $results_per_page;

    $stmt = $conn->prepare($sql_return);
    $stmt->bind_param("iii", $book_id, $start_from, $results_per_page);
    $stmt->execute();
    $result_return = $stmt->get_result();

    // Get total returned books count
    $sql_total_return = "SELECT COUNT(*) AS total FROM return_book WHERE book_id = ?";
    $stmt = $conn->prepare($sql_total_return);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result_total_return = $stmt->get_result();
    $row_total_return = $result_total_return->fetch_assoc();
    $total_returned_books = $row_total_return['total'];
    $total_pages_return = ceil($total_returned_books / $results_per_page);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Return Details</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <style>

.return_book{
    background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        width: 1500px; /* Adjust width as needed */
        text-align: center;
}
.return_book h1 {
    font-size: 24px;
    color: #4b0082;
    text-align: center;
    margin-bottom: 20px;
}

.return_book img {
    display: block;
    margin: 0 auto 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.return_book table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.return_book table th,
.return_book table td {
    padding: 15px;
    text-align: center;
}

.return_book table th {
    background-color: #4b0082;
    color: white;
    font-weight: bold;
}

.return_book table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.return_book table tr:hover {
    background-color: #f1f1f1;
}



.pagination {
    display: flex;
    justify-content: center;
   
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

.close-btn-container {
    position: absolute; /* Ensures positioning is relative to the nearest positioned ancestor */
    top: 20px; /* Adjust distance from the top */
    right: 30px; /* Adjust distance from the right */
}

.close-btn {
    font-size: 24px; /* Adjusts size for visibility */
    color: #ff0000; /* Red color for visibility */
    text-decoration: none; /* Removes default underline */
    font-weight: bold;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 50%; /* Circular button */
    transition: all 0.3s ease-in-out;
}

.close-btn:hover {
    background-color: #ff0000; /* Red background on hover */
    color: white; /* White text on hover */
    text-decoration: none;
    transform: scale(1.1); /* Slight zoom effect */
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

        
        <div class="return_book">

    <h1> <?php echo htmlspecialchars($book['book_title']); ?></h1>
    <div class="close-btn-container">
    <a href="book.php" class="close-btn">&times;</a>
</div>

    <table>
        <thead>
            <tr>
                <th>Member Name</th>
                <th>Return Date</th>
                <th>Fine Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_return->num_rows > 0)
             {
                while ($row = $result_return->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['m_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_of_return']) . "</td>";
                    echo "<td>" . (!empty($row['amount']) ? htmlspecialchars($row['amount']) : "No Fine") . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No return records found for this book.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php
        if ($current_page > 1) {
            echo "<a href='?book_id=$book_id&page=" . ($current_page - 1) . "'>&laquo; Previous</a>";
        }

        for ($i = 1; $i <= $total_pages_return; $i++) {
            if ($i == $current_page) {
                echo "<a class='active' href='?book_id=$book_id&page=$i'>$i</a>";
            } else {
                echo "<a href='?book_id=$book_id&page=$i'>$i</a>";
            }
        }

        if ($current_page < $total_pages_return) {
            echo "<a href='?book_id=$book_id&page=" . ($current_page + 1) . "'>Next &raquo;</a>";
        }
        ?>
    </div>
    </div>
   
    <script src="dashboard.js"></script>

</body>
</html>

