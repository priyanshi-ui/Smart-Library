<?php
session_start();
include('database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: registration.php");
    exit();
}

$logged_in_member_id = $_SESSION["user_id"];
$logged_in_member_email = $_SESSION["user_email"] ?? null;
$success_message = "";
$error_message = "";

// Fetch user points
$query_points = "SELECT points FROM member WHERE member_id = ?";
$stmt_points = $conn->prepare($query_points);
$stmt_points->bind_param("i", $logged_in_member_id);
$stmt_points->execute();
$result_points = $stmt_points->get_result();
$user_points = $result_points->fetch_assoc()['points'] ?? 0;

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Initialize session variable for download prompt if not set
if (!isset($_SESSION['download_prompt_shown'])) {
    $_SESSION['download_prompt_shown'] = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["reserve"])) {
        $book_id = $_POST["book_id"] ?? null;
        $book_title = $_POST["book_title"] ?? null;

        if (!empty($book_id) && !empty($book_title)) {
            $check_copies_query = "SELECT no_of_copy FROM book WHERE book_id = ?";
            $stmt_check = $conn->prepare($check_copies_query);
            $stmt_check->bind_param("i", $book_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $row_check = $result_check->fetch_assoc();
            $no_of_copy = $row_check['no_of_copy'];

            if ($no_of_copy > 0) {
                $reserve_date = date("Y-m-d");
                $reserve_query = "INSERT INTO book_reserve (book_id, reserve_date, member_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($reserve_query);
                $stmt->bind_param("isi", $book_id, $reserve_date, $logged_in_member_id);

                if ($stmt->execute()) {
                    if (sendReservationEmail($book_title, $logged_in_member_id)) {
                        $_SESSION['success_message'] = "Book reserved successfully! A confirmation email has been sent to the librarian.";
                    } else {
                        $_SESSION['error_message'] = "Failed to send the reservation email to the librarian.";
                    }
                } else {
                    $_SESSION['error_message'] = "Failed to reserve the book. Please try again.";
                }
            } else {
                $_SESSION['error_message'] = "Number of copies is zero. Reservation not possible.";
            }

            header("Location: member_book.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Book ID or Title is missing.";
        }
    }
}

function sendReservationEmail($book_title, $member_id) {
    global $conn;
    $query = "SELECT m_email FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_email = $row['m_email'] ?? '';

    if (empty($user_email)) {
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'smartlibrary25@gmail.com';
        $mail->Password = 'psuk ugup wwgv vaps';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('smartlibrary25@gmail.com', 'Library System');
        $mail->addAddress('smartlibrary@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Book Reservation Notification';
        $mail->Body = "Hello,<br><br>The book <b>{$book_title}</b> has been reserved by the member with email: <b>{$user_email}</b>.<br><br>Thank you for your attention.";

        return $mail->send();
    } catch (Exception $e) {
        file_put_contents('error_log.txt', $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    <script>
        window.onload = function() {
            <?php if (!empty($success_message)) { ?>
                alert("<?php echo addslashes($success_message); ?>");
            <?php } elseif (!empty($error_message)) { ?>
                alert("<?php echo addslashes($error_message); ?>");
            <?php } ?>
        }
    </script>
    <style>

.books-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    margin: 20px;
}

.book-card {
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

.book-card img {
    width: 100%;
    height: 180px;
    object-fit: contain; /* or 'cover' based on your requirement */
    display: block;
}


.book-details {
    padding: 10px;
    flex-grow: 1;
}

.book-title {
    font-size: 18px;
    font-weight: bold;
    color: #570987;
    margin: 10px 0;
}

.book-info {
    font-size: 14px;
    color: #333;
    margin: 4px 0;
}
.button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.reserve-form {
    display: flex;
    align-items: center;
    width: 100%;
    gap: 10px;
}

.reserve-btn {
    flex: 7; /* 70% width */
    padding: 10px;
    border: none;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
    background-color: #45076a;
    color: white;
}

.reserve-btn:hover {
    background-color: #6a0dad;
}

.download-btn {
    flex: 3; /* 30% width */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
    border-radius: 5px;
    transition: 0.3s;
    cursor: pointer;
    background-color: white;
   
}

.download-btn:hover {
    background-color: rgb(249, 245, 245);
}

.button-container img {
    width: 30px;
    height: 35px;
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

        <div class="main">
        <h1 style="color:#45076a; text-align:center;">Books Available</h1><br>

<div class="books-container">
<?php
include('database.php');
$results_per_page = 10;

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}

$start_from = ($current_page - 1) * $results_per_page;

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM book WHERE book_id LIKE '%$search%' OR book_title LIKE '%$search%' OR author_name LIKE '%$search%' OR book_isbn LIKE '%$search%' OR domain_name LIKE '%$search%' LIMIT $start_from, $results_per_page";
} else {
    $sql = "SELECT * FROM book LIMIT $start_from, $results_per_page";
}

$result = $conn->query($sql);

echo '<div class="books-container">';

$autoDownloadBooks = []; // For JS to auto download on page load

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if user has reading time >= 2 for this book
        $reading_check_sql = "SELECT reading_time FROM reading_time WHERE member_id = ? AND book_id = ? ORDER BY timestamp DESC LIMIT 1";
        $stmt_reading = $conn->prepare($reading_check_sql);
        $stmt_reading->bind_param("ii", $logged_in_member_id, $row['book_id']);
        $stmt_reading->execute();
        $reading_result = $stmt_reading->get_result();
        $reading_row = $reading_result->fetch_assoc();
        $has_read_enough = ($reading_row && $reading_row['reading_time'] >= 2);

        echo "<div class='book-card'>";
        echo "<img src='" . htmlspecialchars($row["book_image"]) . "' alt='Book Image'>";
        echo "<div class='book-details'>";
        echo "<div class='book-title'>" . htmlspecialchars($row["book_title"]) . "</div>";
        echo "<div class='book-info'><b>Edition:</b> " . htmlspecialchars($row["book_edition"]) . "</div>";
        echo "<div class='book-info'><b>Author:</b> " . htmlspecialchars($row["author_name"]) . "</div>";
        echo "<div class='book-info'><b>Category:</b> " . htmlspecialchars($row["domain_name"]) . "</div>";
        echo "</div>"; // Close book details
        
        // Button container
        echo "<div class='button-container'>";
        echo "<form method='POST' action='member_book.php' class='reserve-form'>";
        echo "<input type='hidden' name='book_id' value='" . htmlspecialchars($row["book_id"]) . "'>";
        echo "<input type='hidden' name='book_title' value='" . htmlspecialchars($row["book_title"]) . "'>";
        echo "<button type='submit' name='reserve' class='reserve-btn'>Reserve</button>";

        // Check if the PDF exists
        $pdf_path = $row['book_pdf'];
        if (!empty($pdf_path) && file_exists($pdf_path)) {
            echo "<button type='button' class='download-btn' onclick='viewPDF(\"" . urlencode($pdf_path) . "\", " . htmlspecialchars($row["book_id"]) . ")'>";
            echo " View PDF";
            echo "</button>";

            // Determine if user can download the book
            // User can download if they have >=5 points and read enough
            if ($user_points >= 5 && $has_read_enough) {
                // Show the download button visible
                echo "<button type='button' id='downloadButton_" . htmlspecialchars($row['book_id']) . "' class='download-btn' onclick='confirmDownload(" . htmlspecialchars($row['book_id']) . ", \"" . urlencode($pdf_path) . "\")'>";
                echo "<img src='images/download.png' alt='Download' class='download-icon'>";
                echo "</button>";
                
                // Add book id and pdf_path for auto download JS
                $autoDownloadBooks[] = ['book_id' => $row['book_id'], 'pdf_path' => $pdf_path];
            } else {
                // Download button hidden initially
                echo "<button type='button' id='downloadButton_" . htmlspecialchars($row['book_id']) . "' class='download-btn' style='display:none;' onclick='confirmDownload(" . htmlspecialchars($row['book_id']) . ", \"" . urlencode($pdf_path) . "\")'>";
                echo "<img src='images/download.png' alt='Download' class='download-icon'>";
                echo "</button>";
            }
        } else {
            echo "<span class='download-btn' style='color: gray;'>No PDF available</span>";
        }

        echo "</form>";
        echo "</div>"; // Close button container
        echo "</div>"; // Close book card
    }
} else {
    echo "<p style='text-align:center;'>No books found</p>";
}

echo '</div>';

// Pagination
$sql_total = "SELECT COUNT(*) AS total FROM book";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_books = $row_total['total'];
$total_pages = ceil($total_books / $results_per_page);

$conn->close();
?>
</div>
<div class="pagination">
    <?php
    if ($current_page > 1) {
        echo "<a href='member_book.php?page=" . ($current_page - 1) . "'>&laquo; Previous</a>";
    }

    for ($page = 1; $page <= $total_pages; $page++) {
        echo "<a href='member_book.php?page=" . $page . "' class='" . ($current_page == $page ? "active" : "") . "'>" . $page . "</a>";
    }

    if ($current_page < $total_pages) {
        echo "<a href='member_book.php?page=" . ($current_page + 1) . "'>Next &raquo;</a>";
    }
    ?>
</div>

        </div>
    </div>
</body>

<script src="dashboard.js"></script>

<script>
let readingStartTime;
let readingEndTime;

// List of books eligible for auto download on page load
const autoDownloadBooks = <?php echo json_encode($autoDownloadBooks); ?>;

function viewPDF(pdfPath, bookId) {
    // Start the timer
    readingStartTime = new Date().getTime();

    // Create a new window to display the PDF
    var pdfWindow = window.open("", "_blank");
    pdfWindow.document.write("<html><head><title>View PDF</title></head><body>");
    pdfWindow.document.write("<iframe src='" + decodeURIComponent(pdfPath) + "' style='width:100%; height:100vh;' frameborder='0'></iframe>");
    pdfWindow.document.write("</body></html>");
    pdfWindow.document.close();

    pdfWindow.onbeforeunload = function() {
        // Stop the timer
        readingEndTime = new Date().getTime();
        let readingTime = (readingEndTime - readingStartTime) / 1000; // in seconds

        console.log("Reading time: " + readingTime + " seconds"); // Debugging line

        // Send the reading time to the server
        sendReadingTime(readingTime, bookId); // Pass the bookId along
    };
}

function confirmDownload(bookId, pdfPath) {
    // Show a confirmation dialog
    if (confirm("Would you like to download this book? Please note that 2 points will be deducted from your account upon confirmation.")) {
        // If the user confirms, redirect to the download URL
        window.location.href = 'download_pdf.php?file=' + encodeURIComponent(pdfPath);
    } else {
        // If the user cancels, do nothing
        console.log("Download canceled.");
    }
}

function downloadBook(bookId, pdfPath) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "download_pdf.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("Raw response:", xhr.responseText); // Log the raw response
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Show success message
                    alert("Download started successfully!");

                    // Redirect to the download link
                    window.location.href = 'download_pdf.php?file=' + encodeURIComponent(pdfPath);
                } else {
                    alert("Error: " + response.message);
                }
            } catch (e) {
                console.error("Response Text:", xhr.responseText);
                alert("Invalid server response. Please check the console for details.");
            }
        } else {
            alert("Error: " + xhr.statusText);
        }
    };
    xhr.send("book_id=" + bookId);
}

function sendReadingTime(readingTime, bookId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "record_reading_time.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response.message); // Show the congratulations message

            // Check if the user can download the book
            if (response.can_download) {
                // Show the download button after the alert is acknowledged
                document.getElementById('downloadButton_' + bookId).style.display = 'block'; // Show the specific download button
            }

            // Update the points display in the UI if you have an element for it
            if (response.updated_points !== undefined) {
                document.getElementById('userPointsDisplay').innerText = "Points: " + response.updated_points;
            }
        } else {
            alert("Error: " + xhr.statusText);
        }
    };
    xhr.send("reading_time=" + readingTime + "&book_id=" + bookId);
}

// On page load, automatically prompt downloads for eligible books, only if session flag not set
window.onload = function() {
    <?php if (!empty($success_message)) { ?>
        alert("<?php echo addslashes($success_message); ?>");
    <?php } elseif (!empty($error_message)) { ?>
        alert("<?php echo addslashes($error_message); ?>");
    <?php } ?>

    // Check if download prompt has been shown
    var downloadPromptShown = <?php echo json_encode($_SESSION['download_prompt_shown']); ?>;
    if (!downloadPromptShown) {
        autoDownloadBooks.forEach(function(book) {
            let buttonId = 'downloadButton_' + book.book_id;
            let btn = document.getElementById(buttonId);
            if (btn) {
                // Optionally, show the button
                btn.style.display = 'block';
                // Prompt the user to download
                // if(confirm("You have unlocked the download for book ID " + book.book_id + ". Would you like to download it now?")) {
                //     window.location.href = 'download_pdf.php?file=' + encodeURIComponent(book.pdf_path);
                // }
            }
        });

        // After prompting downloads, inform backend that prompt has been shown to suppress future prompts
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "set_download_prompt_flag.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("download_prompt_shown=true");
    }
};
</script>
</html>
