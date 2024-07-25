<?php
session_start();
include('connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['productId'];
$newQuantity = $data['quantity'];

$email = $_SESSION['emailidb'];

$sql = "SELECT BuyerID FROM buyer WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $BuyerID = $row['BuyerID'];
    $query = "UPDATE cart SET Quantity = ? WHERE BuyerID = ? AND ProductID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iii", $newQuantity, $BuyerID, $productId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$con->close();
?>
