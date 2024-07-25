<?php
session_start();

header('Content-Type: application/json');

// Validate session and input data
if (!isset($_SESSION['emailidb'])) {
    die(json_encode(array('status' => 'error', 'message' => 'User not logged in')));
}

$buyerID = $_SESSION['emailidb']; // Assumes BuyerID is stored here
error_log("BuyerID from session: " . $buyerID); // Debug output

$productID = filter_input(INPUT_POST, 'productID', FILTER_SANITIZE_NUMBER_INT);
$sellerName = filter_input(INPUT_POST, 'sellerName', FILTER_SANITIZE_STRING);
$productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
$productImage = filter_input(INPUT_POST, 'productImage', FILTER_SANITIZE_STRING);
$productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Convert base64 image string to blob
$productImageBlob = base64_decode($productImage);

// Connect to the database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "ahproject";
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
}

// Check if BuyerID exists
$buyerCheckQuery = $conn->prepare("SELECT COUNT(*) FROM buyer WHERE BuyerID = ?");
$buyerCheckQuery->bind_param('i', $buyerID);
$buyerCheckQuery->execute();
$buyerCheckQuery->bind_result($buyerExists);
$buyerCheckQuery->fetch();
$buyerCheckQuery->close();

if ($buyerExists == 0) {
    die(json_encode(array('status' => 'error', 'message' => 'BuyerID does not exist')));
}

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO Wishlist (BuyerID, ProductID, SellerName, ProductName, ProductImage, ProductPrice) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('iisssd', $buyerID, $productID, $sellerName, $productName, $productImageBlob, $productPrice);

if ($stmt->execute()) {
    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>