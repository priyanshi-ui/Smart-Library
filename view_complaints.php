<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard_style.css">
    <title>View Complaints</title>
    <style>
        /* General Styling */
        h1 {
            color: #570987;
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 12px 15px;
        }
        th {
            background-color: #570987;
            color: white;
        }
        td:hover, tr:hover {
            background-color: #f5f5f5;
        }

        /* Button Styling */
        button, td button {
            padding: 10px 15px;
            background-color: #570987;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover, td button:hover {
            background-color: #7d0dc3;
        }

        /* Status Colors */
        .solved {
            color: green;
            font-weight: bold;
        }
        .pending {
            color: red;
            font-weight: bold;
        }

        /* Pagination Styling */
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
             
            </nav>
        </div>

        <div class="main">
            <?php
            include('database.php');

            // Pagination logic
            $limit = 8; // Records per page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $total_sql = "SELECT COUNT(*) AS total FROM complaints";
            $total_result = $conn->query($total_sql);
            $total_records = $total_result->fetch_assoc()['total'];
            $total_pages = ceil($total_records / $limit);
            ?>

            <h1>Complaints</h1>
            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Complaint Title</th>
                        <th>Complaint Text</th>
                        <th>Priority</th>
                        <th>Category</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch complaints
                    $sql = "SELECT * FROM complaints ORDER BY status ASC, complaint_date DESC LIMIT ?, ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ii', $offset, $limit);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['status'];
                            $button_text = ($status === 'solved') ? 'Pending' : 'Solved';
                            $status_display = ($status === 'solved') ? "<span class='solved'>$status</span>" : "<span class='pending'>$status</span>";
                            echo "<tr id='complaint_" . $row['complaint_id'] . "'>";
                            echo "<td>{$row['member_id']}</td>";
                            echo "<td>{$row['complaint_title']}</td>";
                            echo "<td>{$row['complaint_text']}</td>";
                            echo "<td>{$row['priority']}</td>";
                            echo "<td>{$row['category']}</td>";
                            echo "<td>{$row['complaint_date']}</td>";
                            echo "<td class='status_display'>$status_display</td>";
                            echo "<td><button class='toggle-status' data-id='{$row['complaint_id']}'>$button_text</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No complaints found.</td></tr>";
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
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
    $(document).on('click', '.toggle-status', function () {
        var complaint_id = $(this).data('id');
        var button = $(this);
        var currentStatus = $('#complaint_' + complaint_id + ' .status_display span').text().trim();
        var newStatus = currentStatus === 'solved' ? 'pending' : 'solved';

        if (confirm('Are you sure you want to change this complaint status?')) {
            $.ajax({
                url: 'resolve_complaint.php',
                type: 'POST',
                data: { complaint_id: complaint_id, new_status: newStatus },
                dataType: 'json',
                beforeSend: function() {
                    button.prop('disabled', true).text('Updating...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#complaint_' + complaint_id + ' .status_display').html('<span class="' + newStatus + '">' + newStatus + '</span>');
                        button.text(newStatus === 'solved' ? 'Pending' : 'Solved').prop('disabled', false);
                        alert(response.message);
                    } else {
                        alert('Error: ' + response.message);
                        button.prop('disabled', false).text(currentStatus === 'solved' ? 'Pending' : 'Solved');
                    }
                },
                error: function() {
                    alert('An error occurred while updating the complaint.');
                    button.prop('disabled', false).text(currentStatus === 'solved' ? 'Pending' : 'Solved');
                }
            });
        }
    });
</script>
</body>
</html>
