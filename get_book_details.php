<?php
include('database.php');

if (isset($_GET['book_title'])) {
    $book_title = mysqli_real_escape_string($conn, $_GET['book_title']);
    
    $query = "SELECT book_id, author_name FROM book WHERE book_title = '$book_title'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode($book);  // Return book_id and author_name
    } else {
        echo json_encode(['book_id' => null, 'author_name' => null]);
    }
}
$conn->close();
?>
