<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $reserve_id = $_POST['reserve_id'];
    $new_status = $_POST['status']; // This will be 'Reserved' or 'Unreserved'

    // Validate the new status (optional but recommended)
    if (!in_array($new_status, ['Reserved', 'Unreserved'])) {
        // If status is not valid, exit or handle the error
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }

    // Update the reservation status in the database
    $sql = "UPDATE book_reserve SET status = ? WHERE reserve_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_status, $reserve_id);
    
    if ($stmt->execute()) {
        // Send success response
        echo json_encode(['success' => true]);
    } else {
        // Send failure response
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>