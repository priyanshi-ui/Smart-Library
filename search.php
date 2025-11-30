<?php
// Database connection (example using mysqli)
include ('database.php');

// Process search query
if (isset($_GET['submit-search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['author']);
    
    // SQL query to search books by author_name name
    $sql = "SELECT * FROM book WHERE author_name LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display search results
        while ($row = $result->fetch_assoc()) {
            echo "<p>Book Title: " . $row['book_title'] . "<br>";
            echo "author_name: " . $row['author_name'] . "<br>";
            echo "book_isbn: " . $row['book_isbn'] . "</p>";
        }
    } else {
        echo "No books found with author_name name: " . $search;
    }
}

$conn->close();
?>
