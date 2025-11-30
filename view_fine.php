<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Fine</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
            
            .fines-table {
                width: 100%;
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 18px;
                text-align: left;
            }

            .fines-table th, .fines-table td {
                padding: 12px 15px;
               
            }

            .fines-table th {
                background-color: #570987;
                color: white;
            }

            .fines-table tr:hover {
                background-color: #f5f5f5;
            }

            .fines-table td button {
                padding: 5px 10px;
                margin: 5px;
                border: none;
                background-color: #28a745;
                color: white;
                cursor: pointer;
                border-radius: 3px;
            }
       

.fines-table th {
    background-color: #570987; /* Medium Purple */
    color: white;
}


            .fines-table td button:hover {
                background-color: #218838;
            }

            .fines-table td button.delete-btn {
                background-color: #dc3545;
            }

            .fines-table td button.delete-btn:hover {
                background-color: #c82333;
            }

            .fines-table td button.edit-btn {
                background-color: #007bff;
            }

            .fines-table td button.edit-btn:hover {
                background-color: #0056b3;
            }

            h1 {
                color: #570987;
                text-align:center;
            }

            p {
                font-size: 18px;
            }
                
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin: 20px 0;
            text-align: center;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
        <?php
        include('database.php');
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// If search is performed, adjust the query to fetch search results
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT f.fine_id, f.amount, f.paid_date,f.status, m.m_name, b.book_title
    FROM fines f
    JOIN member m ON f.member_id = m.member_id
    JOIN book b ON f.book_id = b.book_id
    WHERE m.m_name LIKE '%$search%' 
       OR b.book_title LIKE '%$search%' 
       OR f.amount LIKE '%$search%'
       OR f.paid_date LIKE '%$search%'
        OR f.status LIKE '%$search%'
        ORDER BY f.status ASC, f.paid_date DESC
    LIMIT $offset, $limit";

} else {
    $sql = "SELECT f.fine_id, f.amount, f.paid_date, f.status, m.m_name, b.book_title 
            FROM fines f 
            JOIN member m ON f.member_id = m.member_id 
            JOIN book b ON f.book_id = b.book_id 
            ORDER BY f.status ASC, f.paid_date DESC
            LIMIT $offset, $limit";
}
$sql_total = "SELECT COUNT(*) AS total FROM fines";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_books = $row_total['total'];
$total_pages = ceil($total_books / $limit);
$result = $conn->query($sql);

function renderActions($fine_id, $status) {
    $deleteBtn = $status == 'unpaid' ? "<button class='delete-btn' onclick='deleteFine($fine_id)'><i class='fas fa-trash'></i></button>" : ""; 
    $statusBtn = $status == 'unpaid' ? "<button class='status-btn' onclick='updateFineStatus($fine_id, \"paid\")'>Mark as Paid</button>" : "<button class='status-btn' onclick='updateFineStatus($fine_id, \"unpaid\")'>Mark as Unpaid</button>";
    $editBtn = $status == 'unpaid' ? "<button class='edit-btn' onclick='window.location.href=\"update_fine.php?edit_fine_id=$fine_id\"'><i class='fas fa-edit'></i></button>" : ""; 

    return $editBtn . " " . $deleteBtn . " " . $statusBtn;
}
?>

<h1>Fines Management</h1>
<table class="fines-table">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Book Title</th>
                        <th>Fine Amount</th>
                        <th>Paid Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['m_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['book_title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
        echo "<td>" . ($row['paid_date'] ? htmlspecialchars($row['paid_date']) : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . renderActions($row['fine_id'], $row['status']) . "</td>";
        echo "</tr>";
                     } ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                <?php } ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php } ?>
                <?php if ($page < $total_pages) { ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php } ?>
            </div>
        </div>
        </div>

        <script src="dashboard.js"></script>
        <script>
        function deleteFine(fineId) {
            if (confirm('Are you sure you want to delete this fine?')) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_fine.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (this.status === 200) {
                        alert(this.responseText);
                        window.location.reload();
                    }
                };
                xhr.send("fine_id=" + fineId);
            }
        }
        function updateFineStatus(fineId, newStatus) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_fine_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (this.status === 200) {
            alert(this.responseText); // Display server response
            window.location.reload(); // Reload the page to reflect updates
        }
    };
    xhr.send("fine_id=" + encodeURIComponent(fineId) + "&new_status=" + encodeURIComponent(newStatus));
}
$('#searchInput').keydown(function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('#searchForm').submit();
                }
            })

        </script>
</body>

</html>
