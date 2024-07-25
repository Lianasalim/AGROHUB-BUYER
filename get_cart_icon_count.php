<?php
session_start();

if (!isset($_SESSION['emailidb'])) {
    echo 0;
    exit;
}

$email = $_SESSION['emailidb'];

$conn = new mysqli("localhost", "root", "", "ahproject");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as itemCount FROM cart WHERE user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$itemCount = $row['itemCount'];

$stmt->close();
$conn->close();

echo $itemCount;
?>
