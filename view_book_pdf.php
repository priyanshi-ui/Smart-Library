<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: registration.php");
    exit();
}

if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);

    // Ensure the file exists and is a PDF
    if (file_exists($file) && mime_content_type($file) === "application/pdf") {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>View Book</title>
            <style>
                body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4; }
                iframe { width: 80%; height: 90vh; border: none; }
            </style>
            <script>
                // Show alert message when the PDF is opened
                window.onload = function() {
                    alert("If you read for more than 5 minutes, you will earn points that can help you download the book.");
                };
            </script>
        </head>
        <body>
            <iframe src="https://docs.google.com/gview?url=<?php echo urlencode('http://localhost/sem6/'.$file); ?>&embedded=true"></iframe>
        </body>
        </html>
        <?php
    } else {
        echo "<div style='text-align: center; margin-top: 20px; font-size: 20px; color: red;'>Invalid file or file not found.</div>";
    }
} else {
    echo "<div style='text-align: center; margin-top: 20px; font-size: 20px; color: red;'>No file specified.</div>";
}
?>




