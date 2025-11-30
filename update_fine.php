    <?php
    include('database.php');

    $fine_id = "";
    $amount = "";
    $date_of_fine = "";


    // Fetch fine details for editing
    if (isset($_GET['edit_fine_id'])) {
        $fine_id = $_GET['edit_fine_id'];

        $stmt = $conn->prepare("SELECT * FROM fines WHERE fine_id = ?");
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $amount = $row['amount'];
            $date_of_fine = $row['date_of_fine'];
            $status = $row['status'];
        }

        $stmt->close();
    }

    // Update fine details on form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fine_id = $_POST['fine_id'];
        $amount = $_POST['amount'];
        $date_of_fine = $_POST['date_of_fine'] ?: NULL;

        $stmt = $conn->prepare("UPDATE fines SET amount = ?, date_of_fine = ? WHERE fine_id = ?");
        $stmt->bind_param("ssi", $amount, $date_of_fine, $fine_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Fine record updated successfully!');
                window.location.href = 'view_fine.php';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Edit Fine</title>
        <link rel="stylesheet" href="dashboard_style.css">
        <style>
        .update_fine {
            background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 40px auto;
    }

    .update_fine h1 {
        margin-bottom: 20px;
        text-align: center;
        font-size: 30px; /* Corrected property name from 'text-size' to 'font-size' */
        color: #570987;
        font-weight: bold;
    }


    .update_fine label {
        font-weight: bold;
        
    }

    .update_fine input[type="number"],
    .update_fine input[type="date"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 5px;
    }

    .update_fine input:focus {
        outline: none;
        border-color: #570987;
        box-shadow: 0 0 5px rgba(87, 9, 135, 0.5);
    }
    .update_fine input[type="submit"] {
        background-color: #570987;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px; 
        font-size: 16px;
        margin-left:25%;
    }

    .update_fine input[type="submit"]:hover {
        background-color: #570987; /* Darker purple on hover */
    }

    .button {
        background-color: #D22B2B;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            margin-left:2%;
            border-radius: 4px;
            cursor: pointer;
    }

    .button:hover {
        background-color: #960018; /* Darker red on hover */
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
    <div class="update_fine">
        <h1>Edit Fine</h1>
        <form method="post">
            <input type="hidden" name="fine_id" value="<?php echo htmlspecialchars($fine_id); ?>">
            <label for="amount">Fine Amount:</label>
            <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" required>

            <label for="date_of_fine">Date of Fine:</label>
            <input type="date" id="date_of_fine" name="date_of_fine" value="<?php echo htmlspecialchars($date_of_fine); ?>">

            <input type="submit" value="Update Fine">
            <a href="view_fine.php"><input type="button" class="button" value="Cancel"></a>
        </form>
        </div>
    </body>
    <script src="dashboard.js"></script>
    </html>
