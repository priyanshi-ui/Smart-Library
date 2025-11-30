<?php

// Include database connection
include ('database.php');

// Fetch total members
$queryTotalMembers = "SELECT COUNT(*) AS total_members FROM member";
$resultTotalMembers = mysqli_query($conn, $queryTotalMembers);

if (!$resultTotalMembers) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalMembers = mysqli_fetch_assoc($resultTotalMembers);
$totalMembers = $rowTotalMembers['total_members'];

// Fetch total books
$queryTotalBooks = "SELECT COUNT(*) AS total_books FROM book";
$resultTotalBooks = mysqli_query($conn, $queryTotalBooks);

if (!$resultTotalBooks) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalBooks = mysqli_fetch_assoc($resultTotalBooks);
$totalBooks = $rowTotalBooks['total_books'];

// Fetch total issued books
$queryTotalIssue = "SELECT COUNT(*) AS total_issue FROM issue_book where status='issued'";
$resultTotalIssue = mysqli_query($conn, $queryTotalIssue);

if (!$resultTotalIssue) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalIssue = mysqli_fetch_assoc($resultTotalIssue);
$totalIssued = $rowTotalIssue['total_issue'];

// Fetch total fine amount
$queryTotalFine = "SELECT SUM(amount) AS total_fine FROM fines";
$resultTotalFine = mysqli_query($conn, $queryTotalFine);

if (!$resultTotalFine) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalFine = mysqli_fetch_assoc($resultTotalFine);
$totalFine = $rowTotalFine['total_fine'] ?: 0; // Default to 0 if no fine records found

// Fetch recent book issues
$query = "SELECT 
    m.m_name AS member,  -- Fetch member name
    b.book_title AS book,  -- Fetch book title
    i.status AS status  -- Fetch the issue status
FROM issue_book i
JOIN member m ON i.member_id = m.member_id  -- Join with member table using the correct id
JOIN book b ON i.book_id = b.book_id  -- Join with book table using the correct id
WHERE i.status = 'issued'  -- Filter for issued status
ORDER BY i.date_of_issue DESC  -- Order by issue date in descending order
LIMIT 5;
";

$result1 = $conn->query($query);

$query1 = $query1 = "SELECT 
m.m_name AS member,  -- Fetch member name
b.book_title AS book  -- Fetch book title
FROM return_book r
JOIN member m ON r.member_id = m.member_id  -- Join with member table using the correct id
JOIN book b ON r.book_id = b.book_id  -- Join with book table using the correct id
ORDER BY r.date_of_return DESC  -- Order by return date in descending order
LIMIT 5;
";

$result2 = $conn->query($query1);
$query2  = "SELECT 
    m.m_name AS member, 
    b.book_title AS book  
FROM book_reserve r
JOIN member m ON r.member_id = m.member_id
JOIN book b ON r.book_id = b.book_id
WHERE r.status = 'reserved'
ORDER BY r.reserve_date DESC
LIMIT 5";

$result3 = $conn->query($query2);


// Fetch members with overdue books, book names, and overdue days
$queryOverdueMembers = "SELECT DISTINCT m.member_id, m.m_name, ib.due_date, b.book_title, 
                        DATEDIFF(CURDATE(), ib.due_date) AS overdue_days
                        FROM issue_book ib
                        JOIN member m ON ib.member_id = m.member_id
                        JOIN book b ON ib.book_id = b.book_id  -- Joining book table
                        WHERE ib.due_date < CURDATE() AND ib.status != 'returned'";

$resultOverdueMembers = mysqli_query($conn, $queryOverdueMembers);

if (!$resultOverdueMembers) {
    die("Query failed: " . mysqli_error($conn));
}

$overdueMembers = [];
while ($row = mysqli_fetch_assoc($resultOverdueMembers)) {
    $overdueMembers[] = "<li><strong>" . htmlspecialchars($row['m_name']) . "</strong> - 
                         <em>" . htmlspecialchars($row['book_title']) . "</em> - 
                         Overdue by <strong>" . $row['overdue_days'] . "</strong> days</li>";
}

$overdueCount = count($overdueMembers);

// Format overdue members list
$memberSentence = ($overdueCount > 0) 
    ? "<ul style='margin: 0; padding: 0; list-style-type: none;'>" . implode("", $overdueMembers) . "</ul>"
    : "<p style='margin: 0; text-align: center;'>No overdue books.</p>";

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
/* Report Container Styling */
.report-container, .report-container1 {
    width: 48%;
    min-height: 320px;
    margin: 80px 1%; /* Center alignment and spacing */
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: inline-block;
    vertical-align: top;
    overflow: hidden;
}

/* Header Styling */
.report-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 3px solid #6a329f;
    padding-bottom: 10px;
}

.recent-Articles {
    font-size: 20px;
    font-weight: bold;
    color: #6a329f;
    margin: 0;
}

.view {
    background-color: #6a329f;
    color: white;
    padding: 8px 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}

.view:hover {
    background-color: #512a7a;
}

/* Table Styling */
.report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    min-width: 100%;
    text-align: left;
}

.report-table th, .report-table td {
    padding: 12px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.report-table th {
    background-color: #6a329f;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

.report-table tbody tr:nth-child(even) {
    background-color: #f8f8f8;
}

.report-table tbody tr:hover {
    background-color: #e6e6e6;
    transition: 0.3s ease;
}
.notification-btn {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .notification-btn img {
            width: 40px;
            height: 30px;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 50%;
        }

.notification-dropdown {
    max-height: 500px; /* set height limit */
    overflow-y: auto;  /* add vertical scroll when needed */
    background: white;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
    position: absolute;
    top: 60px; /* adjust as per your design */
    right: 10px; /* adjust as per your design */
    width: 300px; /* optional: control width */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    z-index: 100;
}

/* Optional: Nice scrollbar */
.notification-dropdown::-webkit-scrollbar {
    width: 6px;
}
.notification-dropdown::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 6px;
}
.notification-dropdown::-webkit-scrollbar-thumb:hover {
    background: #555;
}


        .notification-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 5px;
            text-align: center;
        }

        .notification-dropdown ul {
            padding: 0;
        }

        .notification-dropdown li {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        .notification-dropdown li:last-child {
            border-bottom: none;
        }
/* Responsive Design */
@media (max-width: 1024px) {
    .report-container, .report-container1 {
        width: 100%;
        display: block;
        margin: 20px auto;
    }
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
        
        <div class="message">
    <div class="circle"></div>

    <div class="notification-btn" onclick="toggleDropdown()">
    <img src="images/notification.png" class="icn" alt="Notification">
    <?php if ($overdueCount > 0): ?>
        <span class="badge">
            <?= ($overdueCount > 9) ? '9+' : $overdueCount; ?>
        </span>
    <?php endif; ?>
</div>

<div id="notificationDropdown" class="notification-dropdown">
    <?= $memberSentence; ?>
</div>


    <div class="dp">
        <a href="profilepage.php">
            <img src="images/profile.jpg" class="dpicn" alt="Profile">
        </a>
    </div>
</div>
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
            <div class="box-container">
                <a href="book.php">
                    <div class="box box1">
                        <div class="text">
                            <h2 class="topic-heading"><?= $totalBooks; ?></h2>
                            <h2 class="topic">Total Books</h2>
                        </div>
                        <img src="images/Books.png" alt="Books">
                    </div>
                </a>

                <a href="member.php">
                    <div class="box box2">
                        <div class="text">
                            <h2 class="topic-heading"><?= $totalMembers; ?></h2>
                            <h2 class="topic">Members</h2>
                        </div>
                        <img src="images/Members.png" alt="Members">
                    </div>
                </a>

                <a href="issue_book.php">
                    <div class="box box3">
                        <div class="text">
                            <h2 class="topic-heading"><?= $totalIssued; ?></h2>
                            <h2 class="topic">Issued Books</h2>
                        </div>
                        <img src="images/Complain.png" alt="Complain">
                    </div>
                </a>
                <a href="view_fine.php">
                <div class="box box4">
                    <div class="text">
                        <h2 class="topic-heading">₹<?= $totalFine; ?></h2>
                        <h2 class="topic">Fine</h2>
                    </div>
                    <img src="images/Fine.png" alt="Fine">
                </div>
</a>
            </div>
            <div style="display:flex">
            <!-- Recent Book Issues -->
            <div class="report-container">
    <div class="report-header">
        <h1 class="recent-Articles">Recent Book Issues</h1>
        <a href="view_issue_book.php">
        <button class="view">View All</button></a>
    </div>
    <div class="report-body">
        <div class="report-topic-heading">
        <table class="report-table">
            <thead>
                <tr> 
                <th><h3 class="t-op">Members</h3></th>
                <th>  <h3 class="t-op">Books</h3></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result1->num_rows > 0): ?>
                    <?php while($row = $result1->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['member']); ?></td>
                            <td><?= htmlspecialchars($row['book']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No recent data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<div class="report-container1">
    <div class="report-header">
        <h1 class="recent-Articles">Recent Book Return</h1>
        <a href="view_return_book.php">
        <button class="view">View All</button></a>
    </div>
    <div class="report-body">
        <div class="report-topic-heading">
        <table class="report-table">
            <thead>
                <tr> 
                <th><h3 class="t-op">Members</h3></th>
                <th>  <h3 class="t-op">Books</h3></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result2->num_rows > 0): ?>
                    <?php while($row = $result2->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['member']); ?></td>
                            <td><?= htmlspecialchars($row['book']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No recent data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
                </div>

    </div>
</div>

    </div>
</div>


  </div>
    </div>

    <script src="dashboard.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var button = document.querySelector(".notification-btn");
        var dropdown = document.getElementById("notificationDropdown");

        // Ensure dropdown is hidden by default
        dropdown.style.display = "none";

        // Toggle dropdown on notification button click
        button.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent closing from document click
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        });

        // Close dropdown if user clicks outside of it and the button
        document.addEventListener("click", function (event) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });

        // Prevent closing if user clicks inside the dropdown
        dropdown.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });
</script>

 
</body>
</html>
