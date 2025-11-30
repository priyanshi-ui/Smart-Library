
<?php
include('database.php');
session_start();

if (!isset($_SESSION['member_id'])) {
    // If no member is logged in, redirect to login page
  //  header("Location: login.php");
    //exit();
}

$member_id = $_SESSION['user_id']; // Get logged-in member ID

$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records query for logged-in member
$total_sql = "SELECT COUNT(*) AS total FROM fines WHERE member_id = ?";
$stmt = $conn->prepare($total_sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Main query with pagination for logged-in member
$sql = "SELECT f.fine_id, f.amount, f.date_of_fine, f.status, m.m_name, b.book_title 
        FROM fines f 
        JOIN member m ON f.member_id = m.member_id 
        JOIN book b ON f.book_id = b.book_id 
        WHERE f.member_id = ? 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $member_id, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Fine</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
            background-color: #45076a; /* Medium Purple */
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

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #E6E6FA; /* Lavender */
            color: #4B0082; /* Dark purple for text */
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #DDA0DD; /* Plum */
        }

        .pagination span {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #ddd;
            border-radius: 4px;
            color: #333;
        }

        h1 {
            color: #45076a;
            text-align:center; /* Dark purple for headings */
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
                        <div class="nav-option option1">
                            <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                            <h3> Dashboard</h3>
                        </div>
                    </a>
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
                    <!-- Add other menu options here -->
                </div>
            </nav>
        </div>
        <div class="main">
            <h1>Fines Management</h1>
            <?php if ($result->num_rows > 0) { ?>
        <table class='fines-table' >
            <tr>
                <th>Book Title</th>
                <th>Amount</th>
                <th>Date of Fine</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                    <td>₹<?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_of_fine']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if (strtolower(trim($row['status'])) == 'unpaid') { ?>
                            <button class="pay-button" 
                                    data-amount="<?php echo $row['amount'] * 100; ?>" 
                                    data-fine-id="<?php echo $row['fine_id']; ?>">
                                Pay Now
                            </button>
                        <?php } else { echo "<strong style='color:green;'>Paid</strong>"; } ?>
                    </td>
                </tr>
            <?php } ?>
                        </table>

                        <div class='pagination'>
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href='?page=<?php echo $i; ?>' <?php if ($i == $page) echo "style='font-weight:bold;'"; ?>><?php echo $i; ?></a>
            <?php } ?>
        </div>
    <?php } else { echo "<p>No fines found for your account.</p>"; } ?>

           <?php

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
    <script src="dashboard.js"></script>
    <script>

        function editFine(fineId) {
            alert('Edit fine with ID: ' + fineId);
        }

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
                    alert(this.responseText);
                    window.location.reload();
                }
            };
            xhr.send("fine_id=" + fineId + "&status=" + newStatus);
        }

        document.querySelectorAll('.pay-button').forEach(button => {
            button.addEventListener('click', function () {
                let amount = this.getAttribute('data-amount');
                let fineId = this.getAttribute('data-fine-id');

                var options = {
                    key: 'rzp_test_kE14KcyItAB2Xy', // Replace with your Razorpay Key
                    amount: amount,
                    currency: 'INR',
                    name: 'Smart Library',
                    description: 'Fine Payment',
                    handler: function (response) {
                        alert('Payment successful! Payment ID: ' + response.razorpay_payment_id);
                        window.location.href = 'update_fine_status.php?fine_id=' + fineId + '&payment_id=' + response.razorpay_payment_id;
                    },
                    prefill: {
                        name: 'User Name', 
                        email: 'user@example.com', 
                        contact: '9876543210'
                    },
                    theme: { color: '#f67d4a' }
                };
                
                var rzp = new Razorpay(options);
                rzp.open();
            });
        });
    </script>
</body>
</html>

