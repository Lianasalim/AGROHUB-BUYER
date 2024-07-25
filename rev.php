<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sellerName = $_POST['sellername'];
    $star = $_POST['rating'];
    $review = $_POST['reviewarea'];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    $sql = "INSERT INTO product_review (SellerName, star, review, image) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siss", $sellerName, $star, $review, $image);
    $stmt->send_long_data(3, $image); // Ensure the image is sent as a long data string
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Review submitted successfully!";
    } else {
        echo "Error submitting review.";
    }

    $stmt->close();
    $con->close();
}
?>
