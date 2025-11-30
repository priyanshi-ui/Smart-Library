<?php
include 'database.php'; 
if (isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
    $message = trim($_POST['message']);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($message === "Recommendations of Various Books") {
        $query = "SELECT book_title FROM book";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $response = "Recommended Books:\n"; 
            while ($row = mysqli_fetch_assoc($result)) {
                $response .= $row['book_title'] . "\n"; 
            }
        } else {
            $response = "No books found.";
        }
        echo nl2br($response); 
    }  
     else {
        // Fetch reply based on query
        $stmt = $conn->prepare("SELECT replies FROM chatbot WHERE queries = ?");
        $stmt->bind_param("s", $userMessage);
        $stmt->execute();
        $stmt->bind_result($reply);

        if ($stmt->fetch()) {
            echo $reply; // Send back reply
        } else {
            echo "Sorry, I don't understand that.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>