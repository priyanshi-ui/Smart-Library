<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        
/* Default Light Mode */
body {
    background-color: white;
    color: black;
    transition: background-color 0.3s, color 0.3s;
}

/* Dark Mode Styles */
body.dark-mode {
    background-color: black;
    color: white;
}

    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Mode Example</title>
    <script src="dark-mode.js" defer></script>
</head>
<body>
    <h1>Welcome to My Website</h1>
    <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
    <p>This is a sample page with dark mode functionality.</p>
</body>
</html>
