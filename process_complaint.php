<?php
include('database.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $member_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $complaint_title = mysqli_real_escape_string($conn, $_POST['complaint_title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $complaint_text = mysqli_real_escape_string($conn, $_POST['complaint_text']);

    // Check if the member_id exists in the member table
    $checkMemberSql = "SELECT * FROM member WHERE member_id = '$member_id'";
    $memberResult = $conn->query($checkMemberSql);

    if (!$memberResult) {
        // If query fails, display error
        echo "<script>alert('Database query failed: " . $conn->error . "'); window.location.href='submit_complaint.php';</script>";
    } else if ($memberResult->num_rows > 0) {
        // Member ID exists, insert complaint into the database
        $sql = "INSERT INTO complaints (member_id, complaint_title, category, priority, complaint_text) 
                VALUES ('$member_id', '$complaint_title', '$category', '$priority', '$complaint_text')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Complaint submitted successfully.'); window.location.href='submit_complaint.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='submit_complaint.php';</script>";
        }
    } else {
        // Member ID does not exist
        echo "<script>alert('Error: Member ID does not exist.'); window.location.href='submit_complaint.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='submit_complaint.php';</script>";
}

$conn->close();
?>
