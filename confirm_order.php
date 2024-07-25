<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        header {
            background-color:  #006633;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .order-details {
            display: flex;
            flex-wrap: wrap;
            background-color: white;
            border: 1px solid lightgray;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-item {
            flex: 1 1 calc(20% - 10px); /* Smaller size for item boxes */
            box-sizing: border-box;
            padding: 10px;
            margin: 5px; /* Reduced margin */
            background-color: #fafafa;
            border: 1px solid #ddd;
            text-align: center;
        }
        .order-item img {
            width: 80px; /* Reduced image size */
            height: auto;
        }
        .order-item h2 {
            margin: 10px 0;
            font-size: 16px; /* Slightly smaller font size */
        }
        .success-message {
            font-size: 24px;
            font-weight: bold;
            color: green;
            text-align: center;
            margin-top: 20px;
        }
        .address-details {
            font-size: 18px;
            margin-top: 20px;
            text-align: center;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color:  #006633;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<header>
    <h1>Order Confirmation</h1>
</header>
</head>

<div class="container">
    <?php
    session_start();
    include('connection.php');

    $email = $_SESSION['emailidb'];
    $selectedAddress = $_POST['selected_address']; // Get the selected address from the form

    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $BuyerID = $row['BuyerID'];

        $query = "SELECT cart.ProductID, cart.SellerName, cart.Quantity, cart.ProductName, cart.ProductPrice, cart.ProductImage 
                  FROM cart 
                  WHERE cart.BuyerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $BuyerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderDetails = [];
        $totalPrice = 0;

        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
            $totalPrice += $row['ProductPrice'] * $row['Quantity'];
        }

$insertQuery = "INSERT INTO order_history (BuyerID, ProductID, SellerName, ProductName, Price, ProductImage, Quantity, DeliveryAddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($insertQuery);

foreach ($orderDetails as $order) {
    // Adjust the binding to match the exact number of placeholders
    $null = null; // placeholder for ProductImage
    $stmt->bind_param("iisssbis", $BuyerID, $order['ProductID'], $order['SellerName'], $order['ProductName'], $order['ProductPrice'], $null, $order['Quantity'], $selectedAddress);
    $stmt->send_long_data(5, $order['ProductImage']);
    $stmt->execute();
}


        echo '<div class="order-details">';
        foreach ($orderDetails as $order) {
            echo '<div class="order-item">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($order['ProductImage']) . '" alt="' . $order['ProductName'] . '">';
            echo '<p>' . $order['SellerName'] . '</p>';
            echo '<h2>' . $order['ProductName'] . '</h2>';
            echo '<p>Quantity: ' . $order['Quantity'] . '</p>';
            echo '<p class="price">Rs.' . number_format($order['ProductPrice'], 2) . '</p>';
            echo '</div>';
        }
        echo '</div>';

        echo '<div class="total-price">Total price: Rs.' . number_format($totalPrice, 2) . '</div>';
        echo '<div class="address-details">Delivery Address: ' . htmlspecialchars($selectedAddress) . '</div>';
        echo '<div class="success-message">Your Order Placed Successfully!!</div>';

        echo '<div class="button-container">';
        echo '<button onclick="continueShopping()">Continue Shopping</button>';
        echo '</div>';

    } else {
        echo "Error: Buyer not found.";
    }
    $query = "DELETE FROM cart WHERE BuyerID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $BuyerID);
$stmt->execute();
    $stmt->close();
    $con->close();
    ?>
</div>
<h4>Your orders will be delivered within 2 working days.</h4>
<h5>Cancellation is only allowed within 10 minutes of ordering.</h5>
<script>
    function continueShopping() {
        window.location.href = 'buyerhome1.php';
    }
</script>
</body>
</html>