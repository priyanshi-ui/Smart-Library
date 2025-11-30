<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: registration.php"); // Redirect to login/registration page
    exit();
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Book Details of the issued book</title>
        <link rel="stylesheet" href="dashboard_style.css">
        <link rel="stylesheet" href="manage.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
 .book-details-card {
            width:100%;
            margin: 30px 40px 30px 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f9f9f9;
            position: relative;
        }
        .book-title {
            text-align: center;
            font-size: 18px;
            color: #570987;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .book-info-container {
            display: flex;
            align-items: flex-start;
            gap: 30px;
        }
        .book-image-container {
            flex: 1;
            text-align: center;
        }
        .book-image {
            max-width: 150px;
            border-radius: 5px;
        }
        .issued-info {
            flex: 2;
        }
        .issued-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .issued-info table th, .issued-info table td {
            padding: 10px;
            text-align: center;
        }
        .issued-info table th {
            background-color: #4b0082;
            color: white;
            font-weight: bold;
        }
        .issued-info table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .issued-info table tr:hover {
            background-color: #f1f1f1;
        }
        .close-btn-container {
    position: absolute; /* Position the container absolutely */
    top: 10px; /* Distance from the top */
    right: 45px; /* Distance from the right */
}

.close-btn {
    font-size: 28px; /* Adjust size as needed */
    color: #ff0000; /* Red color for visibility */
    text-decoration: none; /* Remove underline */
}

.close-btn:hover {
    color: #cc0000; /* Darker red on hover */
}

        </style>
        </head>
    <body>
        <header class="head">
            <div class="logosec">
                <div class="logo">
                    <img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;" >
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

            <div class="book-details-card">
    <?php
    include('database.php');

    if (isset($_GET['book_id']) && !empty($_GET['book_id'])) {
        $book_id = mysqli_real_escape_string($conn, $_GET['book_id']);
        $sqlBookDetails = "SELECT book_id, book_title, book_image, no_of_copy, author_name FROM book WHERE book_id = '$book_id'";
        $bookResult = $conn->query($sqlBookDetails);
        
        if ($bookResult->num_rows > 0) {
            $bookRow = $bookResult->fetch_assoc();
    ?>
    <div class='close-btn-container'>
        <a href="view_issue_book.php" class="close-btn" title="Close">✖</a>
    </div>
    <div class='book-title'>
        <h1><?php echo htmlspecialchars($bookRow['book_title']); ?></h1>
    </div>
    <div class='book-info-container'>
        <div class='book-image-container'>
            <img src='<?php echo htmlspecialchars($bookRow['book_image']); ?>' alt='Book Image' class='book-image'>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($bookRow['author_name']); ?></p>
            <p><strong>Total Copies:</strong> <?php echo htmlspecialchars($bookRow['no_of_copy']); ?></p>
        </div>
        <div class='issued-info'>
            <h3>Issued to:</h3>
            <table>
                <tr>
                    <th>Member Name</th>
                    <th>Member ID</th>
                    <th>Date of Issue</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php
                $sqlIssuedBooks = "SELECT i.member_id, i.date_of_issue, i.due_date, i.status, m.m_name FROM issue_book i LEFT JOIN member m ON i.member_id = m.member_id WHERE i.book_id = '$book_id' AND i.status = 'issued'";
                $issueResult = $conn->query($sqlIssuedBooks);
                
                if ($issueResult->num_rows > 0) {
                    while ($issueRow = $issueResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($issueRow['m_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($issueRow['member_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($issueRow['date_of_issue']) . "</td>";
                        echo "<td>" . htmlspecialchars($issueRow['due_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($issueRow['status']) . "</td>";
                        echo "<td><button class='return-btn' onclick='confirmReturn(" . $book_id . ", " . $issueRow['member_id'] . ", \"" . $issueRow['due_date'] . "\")'>Return</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No book issued to any member.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <?php
        } else {
            echo "<p>Book not found.</p>";
        }
    } else {
        echo "<p>Invalid book ID.</p>";
    }
    ?>
</div>

    <script src="dashboard.js"></script>
        <script>
         
            function confirmReturn(bookId, memberId, dueDate) {
    // Use the due date passed to calculate fines
    const today = new Date();
    const due = new Date(dueDate);
    const finePerDay = 1; // Fine amount per day
    let fine = 0;

    // Calculate fine if due date has passed
    if (today > due) {
        const daysOverdue = Math.ceil((today - due) / (1000 * 60 * 60 * 24));
        fine = daysOverdue * finePerDay;
    }

    // Confirmation dialog
    const message = fine > 0 
        ? `This book is overdue. You will be charged a fine of ₹${fine}. Do you want to return the book?`
        : 'Do you want to return this book?';

    // Show alert dialog to confirm return
    if (confirm(message)) {
        // Redirect to return_book.php with book_id, member_id, and fine as query parameters
        window.location.href = `return_book.php?book_id=${bookId}&member_id=${memberId}&fine=${fine}&return_date=${today.toISOString().split('T')[0]}`;
    }
}


        </script>
    </body>
    </html>


    
