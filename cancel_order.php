<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['orderID'];

    $sql = "UPDATE order_history SET isCancelled = 1 WHERE orderID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect to order history page or show success message
        header("Location: order_history.php");
    } else {
        // Handle error
        echo "Error canceling the order.";
    }
}
?>
