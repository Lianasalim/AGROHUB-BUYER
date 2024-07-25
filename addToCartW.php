<?php
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

    // Get POST data
    $buyerID = 2; // Assuming a static BuyerID for now. You can replace it with dynamic session-based BuyerID
    $productID = $_POST['productId'];
    $productName = $_POST['productName'];
    $productType = $_POST['productType'];
    $productImage = base64_decode($_POST['productImage']); // Decode the base64 image
    $quantity = 1; // Assuming default quantity as 1
    $productPrice = $_POST['productPrice'];
    $total = $productPrice * $quantity;


    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO cart (BuyerID, ProductID, ProductName, ProductType, ProductImage, Quantity, ProductPrice, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssidd", $buyerID, $productID, $productName, $productType, $productImage, $quantity, $productPrice, $total);

    // Execute statement
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    // Close connection
    $stmt->close();
    $con->close();
} else {
    echo "Invalid request method";
}
?>
