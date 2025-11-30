<?php
include ('database.php');

if (isset($_POST['member_id'])) {
    $member_id = $_POST['member_id'];

    $sql = "DELETE FROM member WHERE member_id='$member_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Member deleted successfully";
    } else {
        echo "Error deleting Member: " . $conn->error;
    }
} else {
    echo "Member ID not provided";
}

$conn->close();
?>
