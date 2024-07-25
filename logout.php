<?php
session_start();
require_once("connection.php"); // Include the database connection file

// Remove user data from the session
unset($_SESSION['ahproject']);
unset($_SESSION['emailidb']);

// Close the database connection
$con->close();

// Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            background-image: url('background3.png'); /* Replace 'background3.png' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-color: #f8f9fa; /* Fallback color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .logout-text {
            color: #006633;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        .logout-message {
            color: #006633;
            font-size: 18px;
            margin-top: 10px;
        }
        .logout-message a {
            color: #006633;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-text">Logout Successful</div>
        <p class="logout-message">You have been logged out. Click <a href="home_1.html">here</a> to log back in.</p>
    </div>
</body>
</html>
