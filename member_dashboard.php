<?php
include('database.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php"); // Redirect to login/registration page
    exit();
}

// Get logged-in member ID
$logged_in_member_id = $_SESSION['user_id'];

// Query to fetch the member's name
$name = "SELECT m_name FROM member WHERE member_id = '$logged_in_member_id'";
$result_name = mysqli_query($conn, $name);

if (!$result_name) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result_name);
$member_name = $row['m_name'];

// Query to count total issued books
$queryTotalIssue = "SELECT COUNT(*) AS total_issue FROM issue_book WHERE member_id='$logged_in_member_id' AND status='issued'";

$resultTotalIssue = mysqli_query($conn, $queryTotalIssue);

if (!$resultTotalIssue) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalIssue = mysqli_fetch_assoc($resultTotalIssue);
$totalIssue = $rowTotalIssue['total_issue'];

// Query to count total returned books
$queryTotalReturn = "SELECT COUNT(*) AS total_return FROM return_book WHERE member_id='$logged_in_member_id'";
$resultTotalReturn = mysqli_query($conn, $queryTotalReturn);

if (!$resultTotalReturn) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalReturn = mysqli_fetch_assoc($resultTotalReturn);
$totalReturn = $rowTotalReturn['total_return'];

// Query to count total reserved books
$queryTotalReserve = "SELECT COUNT(*) AS total_reserve FROM book_reserve WHERE member_id='$logged_in_member_id'";
$resultTotalReserve = mysqli_query($conn, $queryTotalReserve);

if (!$resultTotalReserve) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalReserve = mysqli_fetch_assoc($resultTotalReserve);
$totalReserve = $rowTotalReserve['total_reserve'];

// Query to count total fines
$queryTotalFine = "SELECT SUM(amount) AS total_fine FROM fines WHERE member_id='$logged_in_member_id' AND status = 'paid'";
$resultTotalFine = mysqli_query($conn, $queryTotalFine);

if (!$resultTotalFine) {
    die("Query failed: " . mysqli_error($conn));
}

$rowTotalFine = mysqli_fetch_assoc($resultTotalFine);
$totalFine = $rowTotalFine['total_fine'];

$queryTotalPoint = "SELECT SUM(points) AS total_points FROM member WHERE member_id='$logged_in_member_id'";
$resultTotalPoint = mysqli_query($conn, $queryTotalPoint);

if (!$resultTotalPoint) {
    die("Query failed: " . mysqli_error($conn));
}

$resultTotalPoint = mysqli_fetch_assoc($resultTotalPoint);
$totalPoint = $resultTotalPoint['total_points'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .welcome{
            font-size:40px;
            font-weight: bold;
        }
          .chart-container {
            width: 40%;
            margin: auto;
            padding: 20px;
        }
        .dropdown {
            position: relative;
            display: flex;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            text-align: center;
            background-color: white;
            padding: 10px 14px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 1;
            margin-top: 90%;
            width: 100%;
        }

        .dropdown-content a {
            color: black;
            width: 100px;
            padding: 12px 0px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
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
        <div class="welcome">
        <?php
        echo 'Welcome, ' . htmlspecialchars($member_name, ENT_QUOTES, 'UTF-8');
?>
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
                    <div class="nav-option">
                        <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                        <h3>Dashboard</h3>
                    </div>

                    <a href="member_book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="articles">
                            <h3>Books</h3>
                        </div>
                    </a>

                    <a href="member_reserve_book.php" style="text-decoration:none; color:black;">
                    <div id="reserve" class="option2 nav-option">
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
            <div class="box-container">
            <a href="member_issue_book.php" style="text-decoration:none;">
                <div class="box box1">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo "$totalIssue"; ?></h2>
                        <h2 class="topic">Issued Books</h2>
                    </div>
                    <img src="images/Books.png" alt="Books">
                </div>
    </a>

    <a href="member_return_book.php" style="text-decoration:none;">
                <div class="box box2">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo "$totalReturn"; ?></h2>
                        <h2 class="topic">Returned Books</h2>
                    </div>
                    <img src="images/return_book.png" alt="return_book">
                </div></a>

                <a href="member_reserve_book.php" style="text-decoration:none;">
                <div class="box box3">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo "$totalReserve"; ?></h2>
                        <h2 class="topic">Reserved Books</h2>
                    </div>
                    <img src="images/reserve_book.png" alt="reserve_book">
                </div></a>

                <a href="member_fine.php" style="text-decoration:none;">
                <div class="box box4">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo "$totalFine"; ?></h2>
                        <h2 class="topic">Fine</h2>
                    </div>
                    <img src="images/Fine.png" alt="Fine">
                </div>
    </a>

    <div class="box box5">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo "$totalPoint"; ?></h2>
                        <h2 class="topic">Points</h2>
                    </div>
                    <img src="images/medal.png" alt="Fine">
                </div>

            </div>
 <div class="chart-container">
        <h2 style="text-align: center;">Book Statistics</h2>
        <canvas id="reserveBookChart"></canvas>
    </div>
        </div>
    </div>

    <script src="dashboard.js"></script>
    <script>
         const totalIssue = <?php echo $totalIssue; ?>;
        const totalReturn = <?php echo $totalReturn; ?>;
        const totalReserve = <?php echo $totalReserve; ?>;

        const ctx = document.getElementById('reserveBookChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Issued Books', 'Returned Books', 'Reserved Books'],
        datasets: [{
            data: [totalIssue, totalReturn, totalReserve],
            backgroundColor: ['#4caf50', '#2196f3', '#ff5722'],
            hoverBackgroundColor: ['#66bb6a', '#42a5f5', '#ff7043'],
        }]
    },
    options: {
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: (context) => `${context.label}: ${context.raw} books`,
                }
            }
        }
    }
});

    </script>
</body>

</html>
