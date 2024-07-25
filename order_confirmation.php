<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1>Order Confirmation</h1>

    <!-- Display the order details -->
    <h2>Order Details</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Get the products in the cart from the session
            $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

            // Display each product in the cart
            foreach ($products_in_cart as $product_id => $quantity) {
                // Get the product details from the database
                $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
                $stmt->bindValue(':id', $product_id);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                // Display the product name, quantity, and price
                echo '<tr>';
                echo '<td>' . $product['name'] . '</td>';
                echo '<td>' . $quantity . '</td>';
                echo '<td>' . $product['price'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Display the total price -->
    <p>Total price: $<?php echo $total_price; ?></p>

    <!-- Insert the order into the order_history table -->
    <?php
        // Get the products in the cart from the session
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        // Display each product in the cart
        foreach ($products_in_cart as $product_id => $quantity) {
            // Get the product details from the database
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
            $stmt->bindValue(':id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // Display the product name, quantity, and price
            echo '<tr>';
            echo '<td>' . $product['name'] . '</td>';
            echo '<td>' . $quantity . '</td>';
            echo '<td>' . $product['price'] . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<!-- Display the total price -->
<p>Total price: $<?php echo $total_price; ?></p>

<!-- Insert the order into the order_history table -->
<?php
// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the selected address from the session
$selected_address = $_SESSION['selected_address'];

// Get the products in the cart from the session
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Calculate the total price of the order
$total_price = 0.00;
foreach ($products_in_cart as $product_id => $quantity) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindValue(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_price += $product['price'] * $quantity;
}

// Insert the order into the order_history table
$stmt = $pdo->prepare('INSERT INTO order_history (buyer_id, address, total_price, date) VALUES (:buyer_id, :address, :total_price, :date)');
$stmt->bindValue(':buyer_id', $user_id);
$stmt->bindValue(':address', $selected_address);
$stmt->bindValue(':total_price', $total_price);
$stmt->bindValue(':date', date('Y-m-d H:i:s'));
$stmt->execute();

// Get the ID of the newly inserted order
$order_id = $pdo->lastInsertId();

// Insert the order details into the order_details table
foreach ($products_in_cart as $product_id => $quantity) {
    $stmt = $pdo->prepare('INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)');
    $stmt->bindValue(':order_id', $order_id);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->bindValue(':quantity', $quantity);
    $stmt->execute();
}

// Clear the cart session variable
unset($_SESSION['cart']);
?>
    <!-- Display a message to confirm the order -->
    <p>Your order has been confirmed and will be processed shortly. Thank you for shopping with us!</p>
<h4>Your orders will be delivered within 2 working days.</h4>
<h5>Cancellation is only allowed within 10 minutes of ordering.</h5>
    <!-- Add a link to go back to the homepage -->
    <p><a href="Buyerhome1.html">Go back to the homepage</a></p>
</body>
</html>