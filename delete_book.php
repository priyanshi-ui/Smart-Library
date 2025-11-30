<?php
include ('database.php');

if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM book WHERE book_id='$book_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully";
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    echo "Book ID not provided";
}

$conn->close();
?>
