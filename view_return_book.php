<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Returned Books</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    
    <style>
         .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            width: 280px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    background: white;
    text-align: center;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

        .card:hover {
            transform: scale(1.07);
        }

        .card img {
            width: 120px;
    height: 185px;
    border-radius: 8px;
    margin: 0 auto 10px; /* Centers the image horizontally */
    display: block;
}

        .card h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #45076a;
        }

        .card p {
            font-size: 16px;
            font-weight: bold;
            color: #d9534f;
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
  h1 {
                color: #570987;
                text-align:center;
            }


    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.clickable-row').on('click', function() {
                window.location.href = $(this).data('href');
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
            <h1> Return Books Record</h1><br>
            
            <div class="card-container">   
    <?php
    include('database.php');

    $results_per_page = 10; // Number of results per page
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($current_page - 1) * $results_per_page;
    
    // Count total books returned for pagination
    $sql_count = "SELECT COUNT(DISTINCT book.book_id) AS total_books 
                  FROM book 
                  LEFT JOIN return_book ON book.book_id = return_book.book_id";
    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    $total_books = $row_count['total_books'];
    $total_pages_return = ceil($total_books / $results_per_page);
    if (isset($_POST['search'])) {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT book.book_id, book.book_title, book.book_image, 
                COALESCE(COUNT(issue_book.book_id), 0) AS total_returned
        FROM book 
        LEFT JOIN issue_book ON book.book_id = issue_book.book_id
        WHERE book.book_title LIKE '%$search%' 
        GROUP BY book.book_id 
        LIMIT $start_from, $results_per_page";
        
    }else{
    $sql = "SELECT book.book_id, book.book_title, book.book_image, COUNT(return_book.book_id) AS total_returned 
                    FROM book 
                    LEFT JOIN return_book ON book.book_id = return_book.book_id 
                    GROUP BY book.book_id LIMIT $start_from, $results_per_page";}
    $result_return = $conn->query($sql);

    if ($result_return->num_rows > 0) {
        while ($row = $result_return->fetch_assoc()) {
            echo "<div class='card' onclick=\"window.location.href='view_return_details.php?book_id=" . urlencode($row['book_id']) . "'\">";
            echo "<img src='" . htmlspecialchars($row['book_image']) . "' alt='Book Image'>";
            echo "<h3>" . htmlspecialchars($row['book_title']) . "</h3>";
            echo "<p>Total Return: " .  htmlspecialchars($row['total_returned']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<tr><td colspan='3'>No books returned</td></tr>";
    }
    ?>
  
</div>
        
        <div class="pagination">
            <?php
                // Return books pagination links
                if ($current_page > 1) {
                    echo "<a href='view_return_book.php?page=" . ($current_page - 1) . "'>&laquo; Previous</a>";
                }

                for ($i = 1; $i <= $total_pages_return; $i++) {
                    if ($i == $current_page) {
                        echo "<a class='active' href='view_return_book.php?page=" . $i . "'>" . $i . "</a>";
                    } else {
                        echo "<a href='view_return_book.php?page=" . $i . "'>" . $i . "</a>";
                    }
                }

                if ($current_page < $total_pages_return) {
                    echo "<a href='view_return_book.php?page=" . ($current_page + 1) . "'>Next &raquo;</a>";
                }
            ?>
        </div>
    </div>
</div>

<script src="dashboard.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.clickable-row').on('click', function() {
                window.location.href = $(this).data('href');
            });

            $('.clickable-row').hover(function() {
                $(this).css('cursor', 'pointer');
                $(this).attr('title', 'Click here to get details!');
            });

            $('#searchInput').keydown(function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('#searchForm').submit();
                }
            });
        });
    </script>
</body>

</html>
