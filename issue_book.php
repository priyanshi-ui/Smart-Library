<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="dashboard_style.css">
	<link rel="stylesheet" href="manage.css">
    <title>Issue Book</title>
    <style>
        h2 {
            margin-top: 20px;
            text-align: center;
        }

        form {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select,input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            box-sizing: border-box; /* Ensure padding and border are included in width */
        }

        input[type="submit"] {
            background-color: #570987;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-left:45%;
        }

        input[type="submit"]:hover {
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

    </style>
</head>
<body>
 	<header class="head">
		<div class="logosec">
		<div class="logo">
<img src="images/logo.png" class="logo" style="margin-top:30px;padding:2px; width: 150px; height: auto;" ></div>
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
					 <div id="book" class="option2 nav-option " >
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
            <div class="issue-book">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Issue Book</h2>
    
    <label for="book_title">Book Title:</label>
    <select id="book_title" name="book_title" onchange="fetchBookAuthor()">
        <option value="" disabled selected>Select Book</option>
        <?php
        include('database.php');

        // Fetch book title from the database
        $fetch_books_query = "SELECT book_title FROM book";
        $books_result = $conn->query($fetch_books_query);
        if ($books_result->num_rows > 0) {
            while ($row = $books_result->fetch_assoc()) {
                echo "<option value='" . $row['book_title'] . "'>" . $row['book_title'] . "</option>";
            }
        }
        ?>
    </select>

    <!-- Hidden input for book_id -->
    <input type="hidden" id="book_id" name="book_id">

    <label for="author_name">Book Author:</label>
    <input type="text" id="author_name" name="author_name" readonly>

    <label for="member_id">Member ID:</label>
    <select id="member_id" name="member_id" onchange="fetchMemberName()">
        <option value="" disabled selected>Select Member ID</option>
        <?php
        $fetch_member_ids_query = "SELECT member_id FROM member WHERE status = 1;";
        $member_ids_result = $conn->query($fetch_member_ids_query);
        if ($member_ids_result->num_rows > 0) {
            while ($row = $member_ids_result->fetch_assoc()) {
                echo "<option value='" . $row['member_id'] . "'>" . $row['member_id'] . "</option>";
            }
        }
        ?>
    </select>

    <label for="member_name">Member Name:</label>
    <input type="text" id="member_name" name="member_name" readonly>

    <label for="due_date">Due Date:</label>
<input type="date" id="due_date" name="due_date" required>

    <input type="submit" value="Issue ">
    <a href="view_issue_book.php"> <input type="button" class="button" value="Cancel"></a>
</form>


<?php
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $due_date = $_POST['due_date'];
    $date_of_issue = date("Y-m-d");  // Current date


    // Check for unpaid fines
    $check_fines_query = "SELECT COUNT(*) AS unpaid_fines FROM fines WHERE member_id = '$member_id' AND status = 'unpaid'";
    $fines_result = $conn->query($check_fines_query);
    $fines_row = $fines_result->fetch_assoc();

    if ($fines_row['unpaid_fines'] > 0) {
        echo "<script>
            alert('Member has unpaid fines. Cannot issue book.');
            window.location.href = 'view_fine.php';
        </script>";
        exit; // Stop further processing
    }
    // Fetch the number of available copies for the book
    $fetch_book_query = "SELECT no_of_copy FROM book WHERE book_id = '$book_id'";
    $book_result = $conn->query($fetch_book_query);

    if ($book_result->num_rows > 0) {
        $book_data = $book_result->fetch_assoc();
        $no_of_copy = $book_data['no_of_copy'];

        if ($no_of_copy > 0) {
            // Check if the member exists
            $check_member_query = "SELECT * FROM member WHERE member_id = '$member_id'";
            $member_result = $conn->query($check_member_query);

            if ($member_result->num_rows > 0) {
                // Insert into issue_book table
                $issue_query = "INSERT INTO issue_book (book_id, member_id, date_of_issue, due_date) 
                VALUES ('$book_id', '$member_id', '$date_of_issue', '$due_date')";


                // Decrement the number of available copies
                $update_book_query = "UPDATE book SET no_of_copy = no_of_copy - 1 WHERE book_id = '$book_id'";

                if ($conn->query($issue_query) === TRUE && $conn->query($update_book_query) === TRUE) {
                    echo "<script>
                            alert('Book issued successfully!');
                            window.location.href = 'view_issue_book.php';
                          </script>";
                } else {
                    echo "<p style='color: red; text-align: center;'>Failed to issue the book. Please try again.</p>";
                }
            } else {
                echo "<p style='color: red; text-align: center;'>Member with ID $member_id does not exist.</p>";
            }
        } else {
            echo "<p style='color: red; text-align: center;'>No copies of the book are available.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Book not found with ID $book_id.</p>";
    }
}

$conn->close();
?>
	</div>
</div>
</div>
<script src="dashboard.js"></script>
	<script>
function fetchBookAuthor() {
    let bookTitle = document.getElementById('book_title').value;
    let bookAuthorInput = document.getElementById('author_name');
    let bookIdInput = document.getElementById('book_id'); // Hidden input for book ID

    if (bookTitle) {
        fetch('get_book_details.php?book_title=' + encodeURIComponent(bookTitle))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.author_name) {
                    bookAuthorInput.value = data.author_name;
                } else {
                    bookAuthorInput.value = 'Unknown';
                }

                if (data.book_id) {
                    bookIdInput.value = data.book_id; // Set the hidden book_id input
                } else {
                    bookIdInput.value = ''; // Clear if no book_id found
                }
            })
            .catch(error => {
                console.error('Error fetching book details:', error);
                bookAuthorInput.value = 'Error fetching details';
                bookIdInput.value = '';
            });
    } else {
        bookAuthorInput.value = '';
        bookIdInput.value = '';
    }
}


        // Fetch member name based on selected member ID
        function fetchMemberName() {
            let memberId = document.getElementById('member_id').value;
            let memberNameInput = document.getElementById('member_name');
            if (memberId) {
                fetch('get_member_details.php?member_id=' + encodeURIComponent(memberId))
                    .then(response => response.json())
                    .then(data => {
                        if (data.member_name) {
                            memberNameInput.value = data.member_name;
                        } else {
                            memberNameInput.value = 'Unknown';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching member details:', error);
                        memberNameInput.value = 'Error fetching details';
                    });
            } else {
                memberNameInput.value = '';
            }
        }

// Function to set minimum due date to today's date and default to 15 days later
function setDueDateRestrictions() {
    let today = new Date();
    let minDate = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
    let defaultDate = new Date(today);
    defaultDate.setDate(defaultDate.getDate() + 15); // Add 15 days
    let formattedDefaultDate = defaultDate.toISOString().split('T')[0];

    let dueDateInput = document.getElementById('due_date');
    dueDateInput.min = minDate; // Prevent selection of past dates

    // Set default only if the user hasn't changed it
    if (!dueDateInput.value) {
        dueDateInput.value = formattedDefaultDate;
    }
}

// Set restrictions when the page loads
document.addEventListener("DOMContentLoaded", function() {
    setDueDateRestrictions();
});

// Update restrictions when a book is selected
document.getElementById('book_title').addEventListener('change', function() {
    setDueDateRestrictions();
});

</script>
</body>
</html>