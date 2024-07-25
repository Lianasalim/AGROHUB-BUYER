<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connection.php');

$orderDetails = null;

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    try {
        // Fetch order details from order_history table
        $sql = "SELECT * FROM order_history WHERE orderID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $orderDetails = $result->fetch_assoc();
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No order ID provided!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .invoice-container, .no-order-container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        .invoice-title, .no-order-title {
            font-size: 24px;
            font-weight: 600;
            color: #343a40;
        }
        .invoice-details th, .invoice-details td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        .invoice-details th {
            background-color: #007bff;
            color: #fff;
        }
        .invoice-details td {
            background-color: #f8f9fa;
        }
        .invoice-total {
            font-size: 18px;
            font-weight: 600;
            text-align: right;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .back-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .product-image {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 20px auto;
            border: 1px solid #dee2e6;
            padding: 10px;
            background-color: #fff;
        }
        .no-order-message {
            font-size: 20px;
            font-weight: 500;
            color: #dc3545;
            animation: fadeIn 1.5s infinite;
        }
        @keyframes fadeIn {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>

    <?php if ($orderDetails): ?>
        <div class="invoice-container">
            <div class="invoice-title">Order Invoice</div>

            <table class="invoice-details table">
                <tr>
                    <th>Order ID</th>
                    <td><?= htmlspecialchars($orderDetails['orderID']) ?></td>
                </tr>
                <tr>
                    <th>Buyer ID</th>
                    <td><?= htmlspecialchars($orderDetails['BuyerID']) ?></td>
                </tr>
                <tr>
                    <th>Seller Name</th>
                    <td><?= htmlspecialchars($orderDetails['SellerName']) ?></td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <td><?= htmlspecialchars($orderDetails['ProductName']) ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>$<?= htmlspecialchars($orderDetails['Price']) ?></td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td><?= htmlspecialchars($orderDetails['Quantity']) ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= htmlspecialchars($orderDetails['DeliveryAddress']) ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?= htmlspecialchars($orderDetails['Date']) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars($orderDetails['status']) ?></td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td>
                        <?php if (!empty($orderDetails['ProductImage'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($orderDetails['ProductImage']) ?>" alt="Product Image" class="product-image">
                        <?php else: ?>
                            No Image Available
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div class="invoice-total">
                Total: $<?= htmlspecialchars($orderDetails['Price'] * $orderDetails['Quantity']) ?>
            </div>

            <a href="display_data.php" class="back-btn">Back to Complaints</a>
        </div>
    <?php else: ?>
        <div class="no-order-container">
            <div class="no-order-title">Order Not Found</div>
            <p class="no-order-message">No order found with ID: <?= htmlspecialchars($orderID) ?></p>
            <a href="display_data.php" class="back-btn">Back to Complaints</a>
        </div>
    <?php endif; ?>

</body>
</html>
