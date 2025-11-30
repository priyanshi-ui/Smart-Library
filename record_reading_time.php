<?php
session_start();
include('database.php');

if (!isset($_SESSION["user_id"])) {
    header("Location: registration.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reading_time = $_POST['reading_time'] ?? 0;
    $member_id = $_SESSION["user_id"];
    $book_id = $_POST['book_id'] ?? null;

    // Insert the reading time into the database
    $query = "INSERT INTO reading_time (member_id, book_id, reading_time, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iid", $member_id, $book_id, $reading_time);

    if ($stmt->execute()) {
        if ($reading_time >= 2) {
            // Add 5 points
            $update_points_query = "UPDATE member SET points = points + 5 WHERE member_id = ?";
            $update_stmt = $conn->prepare($update_points_query);
            $update_stmt->bind_param("i", $member_id);
            $update_stmt->execute();

            echo json_encode([
                "message" => " Congratulations! You have earned 5 points. You can now download the book.",
                "can_download" => true
            ]);
        } else {
            echo json_encode([
                "message" => " You have not read for long enough (minimum 2 seconds). Keep reading to earn points and unlock downloads.",
                "can_download" => false
            ]);
        }
    } else {
        echo json_encode(["message" => " Failed to record reading time."]);
    }
} else {
    echo json_encode(["message" => " Invalid request method."]);
}
?>
