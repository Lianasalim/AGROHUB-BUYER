<?php
session_start();
include('connection.php');

// Get the BuyerID from session
$email = $_SESSION['emailidb'];
$sql = "SELECT BuyerID FROM buyer WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$BuyerID = $row['BuyerID'];

// Fetch addresses for the logged-in user
$query = "SELECT * FROM savedaddresses WHERE BuyerID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $BuyerID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $address = $row['Name'] . ', ' . $row['BuildingNo'] . ', ' . $row['Address'] . ', ' . $row['Locality'] . ', ' . $row['Pincode'] . ', ' . $row['PhoneNo'];
    echo '<div class="address-item">';
    echo '<p>' . $address . '</p>';
    echo '<button type="button" class="select-address-btn" data-address="' . $address . '">Select</button>';
    echo '</div>';
}

$stmt->close();
$con->close();
?>
