<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['member_id'])) {
        $member_id = $_POST['member_id'];

        // Check current status
        $sql = "SELECT status FROM member WHERE member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_status = $row['status'];

            // Toggle status
            $new_status = ($current_status == 1) ? 0 : 1; // 1 for active, 0 for inactive

            if ($new_status == 1) {
                // If activating, update status AND last_login_date
                $update_sql = "UPDATE member SET status = ?, last_login = NOW() WHERE member_id = ?";
            } else {
                // If deactivating, only update status
                $update_sql = "UPDATE member SET status = ? WHERE member_id = ?";
            }

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $new_status, $member_id);

            if ($update_stmt->execute()) {
                echo $new_status == 1 ? "Member activated successfully. Last login date updated." : "Member deactivated successfully.";
            } else {
                echo "Error updating status.";
            }
        } else {
            echo "Member not found.";
        }
    } else {
        echo "No member ID provided.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
