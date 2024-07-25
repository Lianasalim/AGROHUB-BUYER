<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ahproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['My Orders'])) {
    header("Location: order_history.php");
    exit;
} elseif (isset($_POST['Wishlist'])) {
    header("Location: wishlist.php");
    exit;
} elseif (isset($_POST['My Address'])) {
    header("location: disp_address.php");
    exit;
} elseif (isset($_POST['Edit Profile'])) {
    header("location: update_profile.php");
    exit;
} elseif (isset($_POST['Terms and Conditions'])) {
    header("location: tc.html");
    exit;
}elseif (isset($_POST['Help and Support'])) {
    header("location: complaint.php");
    exit;
} 
 elseif (isset($_POST['Logout'])) {
    header("location:logout.php");
    exit;
}

if (!isset($_SESSION['emailidb'])) {
    die('Please log in.');
}

$email = $_SESSION['emailidb'];

$sql = "SELECT fname, email, phonenumber FROM buyer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No results found";
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Profile - AGROHUB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-header h1 {
            font-size: 2rem;
            color: #28a745;
        }

        .profile-details {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-details p {
            font-size: 1rem;
            margin: 10px 0;
            color: #555;
        }

        .profile-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-button {
            padding: 10px 20px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .profile-button:hover {
            background-color: #218838;
        }

        .profile-button:focus {
            outline: none;
        }

        .profile-button:active {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Buyer Profile</h1>
        </div>
        <div class="profile-details">
            <?php if (isset($user['fname'])): ?>
                <p><strong>Name:</strong> <?php echo $user['fname']; ?></p>
            <?php endif; ?>
            <?php if (isset($user['phonenumber'])): ?>
                <p><strong>Phone Number:</strong> <?php echo $user['phonenumber']; ?></p>
            <?php endif; ?>
            <?php if (isset($user['email'])): ?>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <?php endif; ?>
        </div>
        <div class="profile-buttons">
            <button class="profile-button" onclick="location.href='order_history.php'">My Orders</button>
            <button class="profile-button" onclick="location.href='wishlist.php'">Wishlist</button>
            <button class="profile-button" onclick="location.href='disp_address.php'">My Address</button>
            <button class="profile-button" onclick="location.href='update_profile.php'">Edit Profile</button>
            <button class="profile-button" onclick="location.href='tc.html'">Terms and Conditions</button>
 <button class="profile-button" onclick="location.href='complaint.php'">Help and Support</button>
            <button class="profile-button" onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
</body>
</html>
