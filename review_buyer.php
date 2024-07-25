<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['orderID'];
    $productID = $_POST['ProductID'];
    $sellerName = $_POST['SellerName'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $image = $_FILES['image']['tmp_name'];

    if ($image) {
        $imgData = file_get_contents($image);
        $stmt = $con->prepare("INSERT INTO product_review (orderID, ProductID, SellerName, star, review, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $orderID, $productID, $sellerName, $rating, $review, $imgData);
    } else {
        $stmt = $con->prepare("INSERT INTO product_review (orderID, ProductID, SellerName, star, review) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $orderID, $productID, $sellerName, $rating, $review);
    }

    if ($stmt->execute()) {
        echo "Review submitted successfully.";
        header("Location: order_history.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
