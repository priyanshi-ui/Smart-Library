    <?php
    include('database.php'); // Connect to the database

    // Assuming the member is logged in and has a session (use actual login logic here)
    session_start();

    $user_id = $_SESSION['user_id']; // Assume member ID is stored in the session after login

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submit Complaint</title>
        <link rel="stylesheet" href="dashboard_style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
        <style>
    h1 {
                text-align: center;
                margin-bottom: 35px;
                font-size: 2.5rem;
                color: #45076a;
                font-weight: 700;
            }

        .complaint-container {
        background-color: #fff;
        max-width: 550px;
        width: 100%;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(75, 60, 114, 0.15);
        animation: scaleUp 0.7s ease-in-out;
        margin: 20px auto;
    }

    @keyframes scaleUp {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .input-container {
        position: relative;
        margin-bottom: 25px;
    }

    .input-container label {
        position: absolute;
        top: 15px;
        left: 4px;
        transform: translateY(-50%);
        font-size: 1.1rem;
        color: #45076a;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .input-container input,
    .input-container select,
    .input-container textarea {
        width: 100%;
        padding: 15px;
        font-size: 1rem;
        border: 1px solid #cfcce8;
        border-radius: 8px;
        background-color: #f3f1fa;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        color: #333;
    }

    .input-container input:focus,
    .input-container select:focus,
    .input-container textarea:focus {
        border-color: #45076a;
        box-shadow: 0 4px 12px rgba(131, 103, 199, 0.2);
        outline: none;
    }

    .input-container input:focus + label,
    .input-container select:focus + label,
    .input-container textarea:focus + label,
    .input-container input:not(:placeholder-shown) + label,
    .input-container select:not(:placeholder-shown) + label,
    .input-container textarea:not(:placeholder-shown) + label {
        top: -12px;
        font-size: 0.85rem;
        background-color: white;
        padding: 0 5px;
        color: #45076a;
    }

    button {
        padding: 12px;
        background-color: #45076a;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #6d54b0;
        transform: translateY(-3px);
    }

    button:active {
        transform: translateY(0);
    }

    .footer {
        margin-top: 15px;
        text-align: center;
        font-size: 0.9rem;
        color: #45076a;
    }

    @media (max-width: 600px) {
        .complaint-container {
            padding: 20px;
            max-width: 90%;
        }
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
                        <a href="member_dashboard.php" style="text-decoration:none; color:black;">
                            <div class="nav-option">
                                <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                                <h3>Dashboard</h3>
                            </div>
                        </a>

                        <a href="member_book.php" style="text-decoration:none; color:black;">
                            <div id="book" class="option2 nav-option">
                                <img src="images/book.png" class="nav-img" alt="articles">
                                <h3>Books</h3>
                            </div>
                        </a>

                        <a href="member_reserve_book.php" style="text-decoration:none; color:black;">
                            <div id="book" class="option2 nav-option">
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

            <div class="complaint-container">
        <h1>Submit Your Complaint</h1>

        <form action="process_complaint.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <!-- Complaint Title -->
            <div class="input-container">
                <input type="text" name="complaint_title" placeholder=" " required>
                <label for="complaint_title">Complaint Title</label>
            </div>

            <!-- Category -->
            <div class="input-container">
                <select name="category" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="Service Issue">Service Issue</option>
                    <option value="Book Condition">Book Condition</option>
                    <option value="Library Facility">Library Facility</option>
                    <option value="Other">Other</option>
                </select>
                <label for="category">Category</label>
            </div>

            <!-- Priority Level -->
            <div class="input-container">
                <select name="priority" required>
                    <option value="" disabled selected>Select Priority</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <label for="priority">Priority Level</label>
            </div>

            <!-- Complaint Text -->
            <div class="input-container">
                <textarea name="complaint_text" rows="5" placeholder=" " required></textarea>
                <label for="complaint_text">Your Complaint</label>
            </div>

            <button type="submit">Submit Complaint</button>
        </form>

        <div class="footer">
            <p>We value your feedback and will get back to you shortly.</p>
        </div>
    </div>

    </body>
    <script src="dashboard.js"></script>
    </html>
