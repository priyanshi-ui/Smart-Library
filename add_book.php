<?php
// Database connection settings
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_title = $_POST['book_title'];
    $book_edition = $_POST['book_edition'];
    $isbn = $_POST['isbn'];
    $author_name = $_POST['author_name'];
    $no_of_copy = $_POST['no_of_copy'];
    $domain_name = $_POST['domain_name'];
    $rack_no = $_POST['rack_no'];
    $book_price = $_POST['book_price'];


    // Handling Book Image Upload
    $imageName = $_FILES["book_image"]["name"];
    $imageTmpName = $_FILES["book_image"]["tmp_name"];
    $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowedImageTypes = array("jpg", "jpeg", "png", "gif");

    $imagePath = "uploads/" . $imageName; // Ensure the image is stored in 'uploads/' folder

    // Handling PDF Upload
    $pdfName = $_FILES["book_pdf"]["name"];
    $pdfTmpName = $_FILES["book_pdf"]["tmp_name"];
    $pdfExt = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));

    $pdfPath = "ebooks/" . $pdfName; // Ensure the PDF is stored in 'ebook/' folder

    // Validate Image File Type
    if (!in_array($imageExt, $allowedImageTypes)) {
        echo "<script>alert('Invalid image format! Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
        exit;
    }

    // Validate PDF File Type
    if ($pdfExt != "pdf") {
        echo "<script>alert('Invalid file format! Only PDF files are allowed.');</script>";
        exit;
    }

    // Move uploaded files to correct directories
    if (move_uploaded_file($imageTmpName, $imagePath) && move_uploaded_file($pdfTmpName, $pdfPath)) {
        // Prepare SQL Statement
        $stmt = $conn->prepare("INSERT INTO book (book_title, book_edition, book_isbn, author_name, no_of_copy, domain_name, book_image, book_pdf, rack_no, book_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("ssssissssd", $book_title, $book_edition, $isbn, $author_name, $no_of_copy, $domain_name, $imagePath, $pdfPath, $rack_no, $book_price);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                alert('Book added successfully!');
                window.location.href = 'book.php';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Error uploading files!');</script>";
    }
}

$domainData = [];

$result = $conn->query("SELECT LOWER(domain_name) AS domain_name, rack_no FROM book");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $domain = $row['domain_name'];
        $rack_no = $row['rack_no'];

        $letter = strtoupper(substr($rack_no, 0, 1)); // First character of rack_no
        $number = intval(substr($rack_no, 1)); // Number part

        if (!isset($domainData[$domain])) {
            $domainData[$domain] = [
                'letter' => $letter,
                'max_number' => $number
            ];
        } else {
            // Update max number if this is higher
            if ($number > $domainData[$domain]['max_number']) {
                $domainData[$domain]['max_number'] = $number;
            }
        }
    }
}


function getMaxRackNumber($conn, $domain) {
    $stmt = $conn->prepare("SELECT rack_no FROM book WHERE LOWER(domain_name) = ?");
    $stmt->bind_param("s", $domain);
    $stmt->execute();
    $result = $stmt->get_result();
    $max = 0;
    while ($row = $result->fetch_assoc()) {
        $rack_no = $row['rack_no'];
        $numberPart = intval(substr($rack_no, 1)); // remove letter
        if ($numberPart > $max) {
            $max = $numberPart;
        }
    }
    return $max;
}

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Add Book</title>
        <link rel="stylesheet" href="dashboard_style.css">
        
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
    text-align: center;
    font-size: 30px; /* Corrected property name from 'text-size' to 'font-size' */
    color: #570987;
    font-weight: bold;
}

.input-container {
    position: relative;
    margin-bottom: 25px;
    
}

.input-container input[type="text"],
.input-container input[type="number"] {
    width: 100%;
    padding: 10px;
    padding-top: 10px; /* Extra space for the label */
    border: 2px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background: none;
    box-sizing: border-box;
    
}

.input-container label {
    position: absolute;
    top: 10px;
    left: 12px;
    color: #999;
    font-size: 16px;
    transition: 0.3s ease all;
    pointer-events: none; /* Makes the label unclickable */
}

.input-container input:focus + label,
.input-container input:not(:placeholder-shown) + label {
    top: -25px;
    font-size: 18px;
    color: #570987;
    
}

.input-container input:focus {
    border-color: #570987;
}

.book-form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 5px;
}

.book-form input[type="submit"]
 {
    background-color: #570987;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px; 
    font-size: 16px;
    margin-left:45%;/* Adds some space between inputs and buttons */
}

.book-form input[type="submit"]:hover {
    background-color: #45076a;
}

.book-form label {
    font-weight: bold;
}

span {
    color: red;
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
                <div class="book-form">
                    <form id="bookForm" method="POST" action="#" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <h2>Add Book</h2>
                        <br>
                        <div class="input-container">
        <input type="text" id="book_title" name="book_title" placeholder=" " >
        <label for="book_title">Book Title</label>
        <span id="bookTitleErr"></span>
    </div>

    <div class="input-container">
        <input type="text" id="book_edition" name="book_edition" placeholder=" " >
        <label for="book_edition">Book Edition</label>
    </div>

    <div class="input-container">
        <input type="text" id="isbn" name="isbn" placeholder=" " >
        <label for="isbn">Book ISBN</label>
        <span id="isbnErr"></span>
    </div>

    <div class="input-container">
        <input type="text" id="author_name" name="author_name" placeholder=" " >
        <label for="author_name">Author Name</label>
    </div>

    <div class="input-container">
        <input type="number" id="no_of_copy" name="no_of_copy" placeholder=" " min="1" >
        <label for="no_of_copy">Number Of Copy</label>
        <span id="no_of_copyErr"></span>
    </div>

    <div class="input-container">
        <input type="text" id="domain_name" name="domain_name" placeholder=" " >
        <label for="domain_name">Domain Name</label>
    </div>

    <div class="input-container">
                <input type="number" id="book_price" name="book_price" placeholder=" " min="0" step="0.01">
                <label for="book_price">Book Price</label>
                <span id="bookPriceErr"></span>
            </div>

    <div class="input-container">
        <input type="text" id="rack_no" name="rack_no" placeholder=" " readonly >
        <label for="rack_no">Rack Number</label>
        <span id="rack_noErr"></span>
    </div>  
    
                        <label for="file_upload">File Upload:</label><br>
                        <input type="file" name="book_image"><br><br>

                        <label>Select PDF:</label>
        <input type="file" name="book_pdf" accept=".pdf" required><br><br>
       
                        <input type="submit" value="Add">
                        <a href="book.php"><input type="button" class="button" value="Cancel"></a>
                    </form>
                </div>
            </div>
            <script  src="dashboard.js"></script>
            <script>
    function validateForm() {
        var bookTitle = document.getElementById("book_title").value;
        var bookEdition = document.getElementById("book_edition").value;
        var isbn = document.getElementById("isbn").value;
        var authorName = document.getElementById("author_name").value;
        var numberOfCopy = document.getElementById("no_of_copy").value;
        var domainName = document.getElementById("domain_name").value;
        var rack_no = document.getElementById("rack_no").value;
        var bookPrice = document.getElementById("book_price").value;


        var bookTitleErr = document.getElementById("bookTitleErr");
        var isbnErr = document.getElementById("isbnErr");
        var numberOfCopyErr = document.getElementById("no_of_copyErr");
        var racknoErr = document.getElementById("rack_noErr");
        var bookPriceErr = document.getElementById("bookPriceErr");

        // Clear previous error messages
        bookTitleErr.innerHTML = "";
        isbnErr.innerHTML = "";
        numberOfCopyErr.innerHTML = "";
        racknoErr.innerHTML = "";

        // Check if all fields are empty
        if (bookTitle.trim() === "" && bookEdition.trim() === "" && isbn.trim() === "" &&
            authorName.trim() === "" && numberOfCopy.trim() === "" && domainName.trim() === "" &&
            rack_no.trim() === "") {
            alert("Please fill in all the fields.");
            return false;
        }

        if (bookTitle.trim() === "") {
    bookTitleErr.innerHTML = "*Book Title is required";
    return false;
} else if (!/^[a-zA-Z0-9\s\-:&,'#()\.]+$/.test(bookTitle)) {
    bookTitleErr.innerHTML = "*Only letters, numbers, spaces, and symbols like - : & , ' # ( ) . are allowed";
    return false;
}



        // Validate ISBN
        if (isbn.trim() === "") {
            isbnErr.innerHTML = "*ISBN is required";
            return false;
        } else if (!/^\d{3}-\d{2}-\d{5}-\d{2}-\d$/.test(isbn)) {
            isbnErr.innerHTML = "*ISBN number pattern doesn't match (Expected format: xxx-xx-xxxxx-xx-x)";
            return false;
        }

        // Validate Number of Copies
        if (numberOfCopy.trim() === "") {
            numberOfCopyErr.innerHTML = "*Number of Copies is required";
            return false;
        } else if (isNaN(numberOfCopy) || numberOfCopy <= 0) {
            numberOfCopyErr.innerHTML = "*Please enter a valid number of copies";
            return false;
        }

        // Validate Rack Number
        if (rack_no.trim() === "") {
            racknoErr.innerHTML = "*Rack Number is required";
            return false;
        } else if (!/^[A-Za-z0-9]+$/.test(rack_no)) {
            racknoErr.innerHTML = "*Special characters are not allowed in Rack Number";
            return false;
        }

            bookPriceErr.innerHTML = "";
            if (bookPrice.trim() === "" || isNaN(bookPrice) || bookPrice <= 0) {
                bookPriceErr.innerHTML = "*Please enter a valid book price";
                return false;
            }
        return true;
    }

    const domainData = <?php echo json_encode($domainData); ?>;

function updateRackNumber() {
    const domainNameInput = document.getElementById("domain_name");
    const rackNoInput = document.getElementById("rack_no");
    const domainName = domainNameInput.value.trim().toLowerCase();

    if (domainName === "") {
        rackNoInput.value = "";
        return;
    }

    if (domainData.hasOwnProperty(domainName)) {
        const letter = domainData[domainName]['letter'];
        const nextNumber = domainData[domainName]['max_number'] + 1;
        rackNoInput.value = letter + nextNumber;
    } else {
        const nextLetter = getNextAvailableLetter();
        rackNoInput.value = nextLetter + "1";
    }
}

function getNextAvailableLetter() {
    const usedLetters = Object.values(domainData).map(d => d.letter);
    const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split('');
    for (let i = 0; i < alphabet.length; i++) {
        if (!usedLetters.includes(alphabet[i])) {
            return alphabet[i];
        }
    }
    return "Z"; // fallback
}

document.getElementById("domain_name").addEventListener("input", updateRackNumber);
  
</script>
        </body>
    </html>