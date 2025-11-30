<?php
include('database.php');

$book_id = "";
$book_title = "";
$book_edition = "";
$isbn = "";
$author_name = "";
$no_of_copy = "";
$domain_name = "";
$rack_no = "";
$book_image = "";
$book_pdf = "";
$book_price = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $book_title = $_POST['book_title'];
    $book_edition = $_POST['book_edition'];
    $isbn = $_POST['isbn'];
    $author_name = $_POST['author_name'];
    $no_of_copy = $_POST['no_of_copy'];
    $domain_name = $_POST['domain_name'];
    $rack_no = $_POST['rack_no'];
    $book_price = $_POST['book_price'];


    // Check if the ISBN is already in use by another book
    $stmt = $conn->prepare("SELECT * FROM book WHERE book_isbn = ? AND book_id != ?");
    $stmt->bind_param("si", $isbn, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: ISBN number already exists!'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES["book_image"]["name"];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedImageTypes = array("jpg", "jpeg", "png", "gif");
    
        // Validate image type
        if (in_array($imageExt, $allowedImageTypes)) {
            // Store image path with folder name
            $book_image = "uploads/" . $imageName;
        } else {
            echo "<script>alert('Error: Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
            exit();
        }
    } else {
        // Retain existing image path if no new file is uploaded
        $stmt = $conn->prepare("SELECT book_image FROM book WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $book_image = $row['book_image']; // Keep existing image path
        }
        $stmt->close();
    }
    
    /* Update the book record with image path
    $stmt = $conn->prepare("UPDATE book SET book_image = ? WHERE book_id = ?");
    $stmt->bind_param("si", $book_image, $book_id);
    if ($stmt->execute()) {
        echo "<script>alert('Book record updated successfully!'); window.location.href = 'book.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
*/
$maxFileSize = 200 * 1024 * 1024; // 200MB in bytes

if ($_FILES['book_pdf']['size'] > $maxFileSize) {
    echo "<script>alert('Error: PDF file is too large! Max allowed size is 200MB.');</script>";
    exit();
}

if (isset($_FILES['book_pdf']) && $_FILES['book_pdf']['error'] === UPLOAD_ERR_OK) {
    $pdfName = $_FILES["book_pdf"]["name"];
    $pdfExt = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));
    $allowedPdfTypes = array("pdf", "epub"); // Only allow PDFs

    // Validate PDF type
    if (in_array($pdfExt, $allowedPdfTypes)) {
        // Store PDF path with folder name
        $book_pdf = "ebooks/" . $pdfName;
    } else {
        echo "<script>alert('Error: Only PDF files are allowed.');</script>";
        exit();
    }
} else {
    // Retain existing PDF path if no new file is uploaded
    $stmt = $conn->prepare("SELECT book_pdf FROM book WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $book_pdf = $row['book_pdf']; // Keep existing PDF path
    }
    $stmt->close();
}

    // Update book record with image & PDF
    $stmt = $conn->prepare("UPDATE book SET book_title = ?, book_edition = ?, book_isbn = ?, author_name = ?, no_of_copy = ?, domain_name = ?, rack_no = ?, book_image = ?, book_pdf = ?, book_price = ? WHERE book_id = ?");
    $stmt->bind_param("ssssissssdi", $book_title, $book_edition, $isbn, $author_name, $no_of_copy, $domain_name, $rack_no, $book_image, $book_pdf, $book_price, $book_id);
    
    if ($stmt->execute()) {
        echo "<script>
        alert('Book record updated successfully!');
        window.location.href = 'book.php';
      </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} elseif (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM book WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        $book_title = $row['book_title'];
        $book_edition = $row['book_edition'];
        $isbn = $row['book_isbn'];
        $author_name = $row['author_name'];
        $no_of_copy = $row['no_of_copy'];
        $domain_name = $row['domain_name'];
        $rack_no = $row['rack_no'];
        $book_image = $row['book_image'];
        $book_pdf = $row['book_pdf'];
        $book_price = $row['book_price'];  // Fetch book price
    }
    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Book </title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="manage.css">
    <style>
        
.book-form {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
    margin: 0 auto;
}

.book-form h2 {
    margin-bottom: 20px;
    text-align:center;
    text-size:30px;
}

.book-form input[type="text"],
.book-form input[type="number"],
.book-form input[type="file"]{
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 5px;
}

.book-form input[type="submit"] {
    background-color: #570987;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 5px; 
    font-size: 16px;
    margin-left:45%;
}

.book-form input[type="submit"]:hover {
    background-color: #45076a;
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
        background-color: #960018;
    }
.book-form label {
    font-weight: bold;
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
                            <img src="images/dashboard.png" class="nav-img" alt="dashboard">
                            <h3> Dashboard</h3>
                        </div>
                    </a>
                    <a href="book.php" style="text-decoration:none; color:black;">
                        <div id="book" class="option2 nav-option">
                            <img src="images/book.png" class="nav-img" alt="articles">
                            <h3>Books</h3>
                        </div>
                    </a>
                    <a href="member.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option3 dropdown">
                            <img src="images/member.png" class="nav-img" alt="report">
                            <h3> Members</h3>
                        </div>
                    </a>
                    <a href="category.php" style="text-decoration:none; color:black;">
                        <div class="nav-option option4">
                            <img src="images/catalogue.png" class="nav-img" alt="institution">
                            <h3> Catalogue</h3>
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

                    <div class="nav-option option6">
                        <img src="images/Complains.png" class="nav-img" alt="settings">
                        <h3> Complain</h3>
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
            <div class="book-form">
                <form id="bookForm" method="POST" action="#" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <h2>Update Book</h2>
                    <br>
                    <input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id; ?>">

                    <label for="bookTitle">Book Title:</label><br>
                    <input type="text" id="book_title" name="book_title" value="<?php echo $book_title; ?>" placeholder="Book-Title"><br><br>

                    <label for="bookEdition">Book Edition:</label><br>
                    <input type="text" id="book_edition" name="book_edition" value="<?php echo $book_edition; ?>" placeholder="Book-Edition"><br><br>

                    <label for="isbn">ISBN:</label><br>
                    <input type="text" id="isbn" name="isbn" value="<?php echo $isbn; ?>" placeholder="123-12-12345-12-1"><br><br>

                    <label for="author_name">Author Name:</label><br>
                    <input type="text" id="author_name" name="author_name" value="<?php echo $author_name; ?>" placeholder="Author-Name"><br><br>

                    <label for="no_of_copy">Number Of Copy:</label><br>
                    <input type="number" id="no_of_copy" name="no_of_copy" min="1" value="<?php echo $no_of_copy; ?>" placeholder="Number-of-Copy"><br><br>

                    <label for="domain_name">Domain Name:</label><br>
                    <input type="text" id="domain_name" name="domain_name" value="<?php echo $domain_name; ?>" placeholder="Domain-Name"><br><br>

                    <label for="rack_no">Rack Number:</label><br>
                    <input type="text" id="rack_no" name="rack_no" value="<?php echo $rack_no; ?>" placeholder="Rack Number" readonly><br><br>

                    <label for="book_price">Book Price:</label><br>
<input type="number" id="book_price" name="book_price" value="<?php echo $book_price; ?>" placeholder="Enter Book Price" step="0.01" min="0"><br><br>


                    <label for="book_image">Book Image:</label><br>
<?php if (!empty($book_image)): ?>
    <img src="<?php echo $book_image; ?>" alt="Book Image" style="max-width: 150px; height: auto;"><br><br>
<?php endif; ?>
<input type="file" id="book_image" name="book_image" accept="image/*"><br><br>


<label>Select PDF:</label>
<?php if (!empty($book_pdf)): ?>
    <img src="<?php echo $book_pdf; ?>" alt="Book PDF"><br><br>
<?php endif; ?>
<input type="file" name="book_pdf" accept=".pdf" ><br><br>

                    <input type="submit" value="Update">
                    <a href="book.php"><input type="button" class="button" value="Cancel"></a>
                </form>
            </div>
        </div>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
