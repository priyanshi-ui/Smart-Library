<?php
include('database.php');
session_start();

if (!isset($_SESSION['librarian_id'])) {
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reserved Books</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 18px;
            text-align: left;
        }

        .reservation-table th, .reservation-table td {
            padding: 12px 15px;
            
        }

        .reservation-table th {
            background-color: #570987; /* Medium Purple */
            color: white;
        }

        .reservation-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-btn{
            padding: 5px 10px;
                margin: 3px;
                border: none;
                font-size:18px;
                background-color: #570987;
                color: white;
                cursor: pointer;
                border-radius: 3px;
        }

        .status-btn:hover{
            background-color: #45076a;
        }
        .status-cell.Reserved {
            color: green;
            font-weight: bold;
        }

        .status-cell.Unreserved {
            color: red;
            font-weight: bold;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
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

        h1 {
            color: #4B0082;
            text-align:center; /* Dark purple for headings */
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
            <h1>Reserved Books</h1>
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Book Title</th>
                        <th>Reserve Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php


$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records count
$total_sql = "SELECT COUNT(*) AS total FROM book_reserve";
$result = $conn->query($total_sql);
$total_row = $result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// If search is performed, adjust the query to fetch search results
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT r.reserve_id, r.reserve_date, r.status, m.m_name, b.book_title
            FROM book_reserve r
            JOIN member m ON r.member_id = m.member_id
            JOIN book b ON r.book_id = b.book_id
            WHERE m.m_name LIKE '%$search%' OR b.book_title LIKE '%$search%' 
            ORDER BY r.status = 'Reserved' ASC
            LIMIT $offset, $limit";
} else {
    $sql = "SELECT r.reserve_id, r.reserve_date, r.status, m.m_name, b.book_title
            FROM book_reserve r
            JOIN member m ON r.member_id = m.member_id
            JOIN book b ON r.book_id = b.book_id
            ORDER BY r.status = 'Reserved' ASC
            LIMIT $offset, $limit";
}

$result = $conn->query($sql);


                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_class = ($row['status'] == 'Reserved') ? 'Reserved' : 'Unreserved';
                            $status_display = ($row['status'] == 'Reserved') ? 'Reserved' : 'Unreserved';

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['m_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['book_title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reserve_date']) . "</td>";
                            echo "<td class='status-cell $status_class'>" . $status_display . "</td>";
                            echo "<td>";
                            // Action Button based on status
                            echo "<form action='update_reservation_status.php' method='post' class='reservation-form' style='display:inline-block;'>";
                            echo "<input type='hidden' name='reserve_id' value='" . $row['reserve_id'] . "'>";
                            echo "<input type='hidden' name='status' value='" . ($row['status'] == 'Reserved' ? 'Unreserved' : 'Reserved') . "'>";
                            echo "<button type='submit' class='status-btn' data-status='" . $row['status'] . "'>";
                            echo ($row['status'] == 'Reserved' ? 'Unreserve' : 'Reserve');
                            echo "</button>";
                            echo "</form>";
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No Reserved books found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>
<script src="dashboard.js"></script>
<script>

// Event listener for the reservation forms
document.querySelectorAll('.reservation-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        let button = this.querySelector('.status-btn');
        let currentStatus = button.getAttribute('data-status');
        let newStatus = currentStatus === 'Reserved' ? 'Unreserved' : 'Reserved';

        // Show confirmation alert
        if (confirm(`Are you sure you want to set the status to ${newStatus}?`)) {
            let formData = new FormData(this);

            // Disable the button during the request
            button.disabled = true;

            // Send an AJAX request to the server to update the status
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json()) // Assuming the response is JSON
                .then(data => {
                    if (data.success) {
                        // Toggle button text and status attribute
                        button.textContent = newStatus === 'Reserved' ? 'Unreserve' : 'Reserve';
                        button.setAttribute('data-status', newStatus);

                        // Update the status cell in the table row
                        let row = button.closest('tr');
                        let statusCell = row.querySelector('td:nth-child(4)'); // Assuming status is in the 4th column
                        statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                        // Change the cell's color based on status
                        statusCell.className = `status-cell ${newStatus}`;

                        alert(`Status successfully updated to ${newStatus}.`); // Success message
                    } else {
                        alert('Status update failed. Please try again.'); // Error message
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.'); // Error message
                })
                .finally(() => {
                    // Re-enable the button after the request
                    button.disabled = false;
                });
        }
    });
});

$('#searchInput').keydown(function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('#searchForm').submit();
                }
            })

    </script>
</body>
</html>