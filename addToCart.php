<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ahproject";

    // Create connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if the session email is set
    if (!isset($_SESSION['emailidb'])) {
        die("Please log in.");
    }

    $email = $_SESSION['emailidb'];

    // Retrieve the BuyerID based on the session email
    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $buyerID = $row['BuyerID'];
    } else {
        die("Error: Buyer not found.");
    }

    // Get POST data
    $productId = $_POST['productId'];
    $sellerName = $_POST['sellerName'];
    $productName = $_POST['productName'];
    $productType = $_POST['productType'];
    $productImage = $_POST['productImage'];
    $quantity = 1; // Assuming default quantity as 1
    $productPrice = $_POST['productPrice'];
    $total = $productPrice * $quantity;

    // Decode the base64-encoded image data
    $decodedImage = base64_decode($productImage);

    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO cart (BuyerID,ProductID,SellerName, ProductName, ProductType, ProductImage, Quantity, ProductPrice, Total) VALUES ( ?, ?,?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssidd", $buyerID,$productId, $sellerName, $productName, $productType, $decodedImage, $quantity, $productPrice, $total);

    // Execute statement
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $con->close();
} else {
    echo "Invalid request method";
}
?>
