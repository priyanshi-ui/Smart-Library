<?php
include('database.php');

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
    
    // Prepare SQL to get the member name
    $query = "SELECT m_name as member_name FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($query);
    
    // Error handling in case query preparation fails
    if ($stmt === false) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    
    // Bind the member ID to the query and execute
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return the member name if found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['member_name' => '']);
    }

    $stmt->close();
}

$conn->close();
?>
