<?php
include ('database.php');

if (isset($_POST['fine_id'])) {
    $fine_id = $_POST['fine_id'];

    $sql = "DELETE FROM fines WHERE fine_id='$fine_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Fine deleted successfully";
    } else {
        echo "Error deleting fine: " . $conn->error;
    }
} else {
    echo "Fine ID not provided";
}

$conn->close();
?>
