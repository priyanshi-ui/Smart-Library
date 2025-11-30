<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fine_id = $_POST['fine_id'];
    $stmt = $conn->prepare("DELETE FROM fines WHERE fine_id = ?");
    $stmt->bind_param("i", $fine_id);
    if ($stmt->execute()) {
        echo "Fine deleted successfully.";
    } else {
        echo "Error deleting fine.";
    }
    $stmt->close();
    $conn->close();
}
?>
