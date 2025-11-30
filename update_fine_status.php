<?php
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fine_id = $_POST['fine_id'];
    $new_status = $_POST['new_status'];
    
    // Prepare SQL statement
    if ($new_status == 'paid') {
        $date_of_payment = date('Y-m-d'); // Get the current date
        $stmt = $conn->prepare("UPDATE fines SET status = ?, paid_date = ? WHERE fine_id = ?");
        $stmt->bind_param("ssi", $new_status, $date_of_payment, $fine_id);
    } else {
        $stmt = $conn->prepare("UPDATE fines SET status = ?, paid_date = NULL WHERE fine_id = ?");
        $stmt->bind_param("si", $new_status, $fine_id);
    }

    if ($stmt->execute()) {
        echo "Fine status updated successfully!";
    } else {
        echo "Error updating fine status: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
