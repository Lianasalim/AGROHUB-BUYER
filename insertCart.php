<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ahproject";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: ". $con->connect_error);
}

// Insert into cart table
if (isset($_POST['productId']) && isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productType'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productType = $_POST['productType'];

    $sql = "INSERT INTO cart (ProductName, ProductType, ProductImage, Quantity, Price) VALUES (?, ?, ?, 1, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $productName, $productType, $productImage, $productPrice);

    if ($productType == 'fruits') {
        $sql = "SELECT FrImage FROM fruits WHERE FruitID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $productImage = $row['FrImage'];
    } elseif ($productType == 'homemades') {
        $sql = "SELECT HImage FROM homemades WHERE HomID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $productImage = $row['HImage'];
    } elseif ($productType == 'oil') {
        $sql = "SELECT OilImg FROM oil WHERE OilID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $productImage = $row['OilImg'];
    } elseif ($productType == 'powder') {
        $sql = "SELECT PowImg FROM powder WHERE PowID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $productImage = $row['PowImg'];
    } elseif ($productType == 'vegetables') {
        $sql = "SELECT VeImg FROM vegetables WHERE VegID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $productImage = $row['VeImg'];
    }

    $stmt->execute();
    echo 'success';
} else {
    echo 'error';
}

$con->close();
?>