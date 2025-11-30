<?php
// Include database connection
include('database.php');

// Check if book_id and member_id are provided in the query parameters
if (isset($_GET['book_id']) && isset($_GET['member_id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['book_id']);
    $member_id = mysqli_real_escape_string($conn, $_GET['member_id']);
    $fine = isset($_GET['fine']) ? (int)$_GET['fine'] : 0;
    $date_of_fine= isset($_GET['return_date']) ? $_GET['return_date'] : date('Y-m-d'); 
    $return_date = isset($_GET['return_date']) ? $_GET['return_date'] : date('Y-m-d');

    // Step 1: Mark the book as returned in the issue_book table
    $updateIssueStatus = "
        UPDATE issue_book 
        SET status = 'returned'
        WHERE book_id = '$book_id' AND member_id = '$member_id' AND status = 'issued'
    ";

    $resultUpdateIssue = mysqli_query($conn, $updateIssueStatus);

    if ($resultUpdateIssue && mysqli_affected_rows($conn) > 0) {
        // Step 2: Insert the return details into the return_book table
        $insertReturnBook = "
            INSERT INTO return_book (book_id, member_id, date_of_return) 
            VALUES ('$book_id', '$member_id', '$return_date')
        ";
        $resultReturnBook = mysqli_query($conn, $insertReturnBook);

        if ($resultReturnBook) {
            // Step 3: Update the number of copies in the book table
            $updateBookCopies = "
                UPDATE book 
                SET no_of_copy = no_of_copy + 1 
                WHERE book_id = '$book_id'
            ";
            $resultUpdateBook = mysqli_query($conn, $updateBookCopies);

            if ($resultUpdateBook) {
                // Step 4: If a fine is applicable, insert it into the fines table
                $logFineSQL = "
                    INSERT INTO fines (member_id, book_id, amount, date_of_fine)
                    VALUES ('$member_id', '$book_id', '$fine', '$date_of_fine')
                ";
                $conn->query($logFineSQL);
                // Success: Redirect back with success message
                echo "<script>alert('Book returned successfully!'); window.location.href='view_issue_book.php';</script>";
            } else {
                // Error while updating the book's number of copies
                echo "<script>alert('Error updating the number of copies.'); window.location.href='view_issue_book.php';</script>";
            }
        } else {
            // Error while inserting into the return_book table
            echo "<script>alert('Error returning the book. Please try again.'); window.location.href='view_issue_book.php';</script>";
        }
    } else {
        // If no rows were affected, it means the book wasn't issued or status was not 'issued'
        echo "<script>alert('No issued book found with the provided details.'); window.location.href='view_issue_book.php';</script>";
    }
} else {
    // Invalid request due to missing parameters
    echo "<script>alert('Invalid request.'); window.location.href='view_issue_book.php';</script>";
}

// Close the database connection
mysqli_close($conn);
?>
