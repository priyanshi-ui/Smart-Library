<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Member</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
                table {
    width: 100%;
    border-collapse: collapse;

}

.btn-link {
    text-decoration: none;

}

th,
td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #570987;
    font-weight: bold;
    color:#fff;
}

tr:hover {
    background-color: #f2f2f2;
}

.main {
    padding: 20px;
}

.btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 10px 10px ;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    margin-right: 0.1px; /* Small margin between buttons */
}
/* Eye Button */
.eye-btn {
    background-color: #ffc107; /* Yellow background */
    color: white;
    padding: 8px 10px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    margin-right: 0.1px; /* Small margin between buttons */
}

.eye-btn:hover {
    background-color: #e0a800; /* Darker yellow */
    transform: scale(1.05);
}

/* Eye Slash button for inactive status */
.eye-btn.inactive {
    background-color: #6c757d; /* Grey background */
}

.eye-btn.inactive:hover {
    background-color: #5a6268; /* Darker grey */
}

/* Font Awesome icon styles */
i.fas {
    font-size: 18px;
    color: white;
}

/* Add Button */
.add-btn {
    background-color: #28a745; /* Green background */
    color: white;
    margin-bottom: 15px;
}

.add-btn:hover {
    background-color: #218838; /* Darker green */
    transform: scale(1.05);
}

/* Edit Button */
.edit-btn {
    background-color: #007bff; /* Blue background */
    color: white;
}

.edit-btn:hover {
    background-color: #0056b3; /* Darker blue */
    transform: scale(1.05);
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545; /* Red background */
    color: white;
}

.delete-btn:hover {
    background-color: #c82333; /* Darker red */
    transform: scale(1.05);
}

/* Font Awesome icon styles */
i.fas {
    font-size: 18px;
    color: white;
}

/* Link inside buttons */
.btn-link {
    color: white;
    text-decoration: none;
    display: inline-flex;
    justify-content: center;
    align-items: center;
   
}

/* Optional: Tooltip hover for button title */
.btn[title]:hover::after {
    content: attr(title);
    position: absolute;
    background-color: #333;
    color: white;
    padding: 5px 8px;
    border-radius: 5px;
    top: -35px;
    right: 0;
    white-space: nowrap;
    font-size: 12px;
    z-index: 10;
}

a {
    text-decoration: none;
    color: white;
    font-weight: bold;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                if (confirm("Are you sure you want to delete this student?")) {
                    var row = $(this).closest('tr');
                    var memberID = row.find('td:eq(0)').text();
                    $.ajax({
                        type: 'POST',
                        url: 'delete_member.php',
                        data: { member_id: memberID },
                        success: function(response) {
                            alert(response);
                            row.remove();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            $('#searchInput').keydown(function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('#searchForm').submit();
                }
            });
        });
    </script>
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
            <div>
                <h1 style="text-align:center; color:#45076a;">Manage Member</h1>
    </div>
    <div style="text-align:right">
                <button type="button" class="btn add-btn" title="Add">
                                    <a href="add_member.php" class="btn-link">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </button> </div>
                <table id="bookListing">
                    <thead>
                        <tr>
							<th> ID </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Institute</th>
							<th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include ('database.php');
                       // $member_id = $_POST["member_id"];

// Set the number of results to display per page
$results_per_page = 10;

// Check which page the user is currently on, default to page 1
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}

// Calculate the starting record for the query
$start_from = ($current_page - 1) * $results_per_page;


if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM member 
            WHERE member_id LIKE '%$search%' 
            OR m_name LIKE '%$search%' 
            OR m_email LIKE '%$search%' 
            OR m_address LIKE '%$search%' 
            OR institute_name LIKE '%$search%'  
            ORDER BY status DESC, member_id ASC 
            LIMIT $start_from, $results_per_page";
} else {
    $sql = "SELECT * FROM member  
            ORDER BY status ASC, member_id ASC 
            LIMIT $start_from, $results_per_page";
}

                        $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["member_id"] . "</td>";
            echo "<td>" . $row["m_name"] . "</td>";
            echo "<td>" . $row["m_email"] . "</td>";
            echo "<td>" . $row["m_contact"] . "</td>";
            echo "<td>" . $row["m_address"] . "</td>";
            echo "<td>" . $row["institute_name"] . "</td>";

            $eyeIcon = $row["status"] == 1 ? "fa-eye" : "fa-eye-slash";
            $eyeTitle = $row["status"] == 1 ? "Deactivate" : "Activate";
            $eyeBtnClass = $row["status"] == 1 ? "eye-btn" : "eye-btn inactive";
            
            // Eye button to toggle active/deactivate
            echo '<td> 
                <button type="button" class="btn ' . $eyeBtnClass . '" title="' . $eyeTitle . '" onclick="toggleMemberStatus(' . $row["member_id"] . ')">
                    <i class="fas ' . $eyeIcon . '"></i>
                </button>
            </td>';
            
            // Edit button
            echo '<td> 
                <button type="button" class="btn edit-btn" title="Edit">
                    <a href="update_member.php?id=' . $row['member_id'] . '&name=' . $row['m_name'] . '" class="btn-link">
                        <i class="fas fa-edit"></i>
                    </a>
                </button> 
            </td>';
            
            // Delete button
            echo '<td> 
                <button type="button" class="btn delete-btn" title="Delete">
                    <i class="fas fa-trash"></i>
                </button> 
            </td>';
            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No Member found</td></tr>";
    }

                        
// Count total number of books to determine the number of pages
$sql_total = "SELECT COUNT(*) AS total FROM member";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_books = $row_total['total'];
$total_pages = ceil($total_books / $results_per_page);

                        $conn->close();
                        ?>
                    </tbody>
                </table>
                              
<div class="pagination">
    <?php
    if ($current_page > 1) {
        echo "<a href='member.php?page=" . ($current_page - 1) . "'>&laquo; Previous</a>";
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo "<a class='active' href='member.php?page=" . $i . "'>" . $i . "</a>";
        } else {
            echo "<a href='member.php?page=" . $i . "'>" . $i . "</a>";
        }
    }

    if ($current_page < $total_pages) {
        echo "<a href='member.php?page=" . ($current_page + 1) . "'>Next &raquo;</a>";
    }
    ?>
</div>


            </div>
        </div>
    </div>
    <script src="dashboard.js"></script>
    <script>
      
        function toggleMemberStatus(memberID) {
    if (confirm("Are you sure you want to toggle this member's status?")) {
        $.ajax({
            type: 'POST',
            url: 'deactivate_member.php',
            data: { member_id: memberID },
            success: function(response) {
                alert(response);
                location.reload();  // Reload the page to update the icon
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);  // Handle error
            }
        });
    }
}

    </script>
</body>

</html>
