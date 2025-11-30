<?php
session_start();
include('database.php');

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in to download.";
    exit();
}

$user_id = $_SESSION["user_id"];
$file_name = isset($_GET['file']) ? basename($_GET['file']) : '';
$file_path = 'ebooks/' . $file_name;

// if (!file_exists($file_path)) {
//     echo "File not found.";
//     exit();
// }

// Check user points before allowing download
$query = "SELECT points FROM member WHERE member_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['points'] >= 5) {
    // User has enough points, proceed with the download

    // Update points (reduce by 1 after downloading)
    $update_points_query = "UPDATE member SET points = points - 1 WHERE member_id = ?";
    $update_stmt = $conn->prepare($update_points_query);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();

    // Force file download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
} else {
    echo "You need at least 5 points to download this file.";
}
?>