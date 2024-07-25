<?php
session_start();
include('connection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>CART</title>
     <style>
        header {
            background-color: #006633;
            padding: 7px;
            text-align: center;
            color: white;
            font-size: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            grid-gap: 10px;
            padding: 10px;
        }

        .grid-item {
            background-color: white;
            border: 1px solid lightgray;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .grid-item img {
            width: 100%;
            height: auto;
        }

        .quantity-input {
            width: 50px;
            margin: 0 5px;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
        }

        .total-price {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
        }

        button {
            background-color: #006633;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            display: block;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function updateTotalPrice() {
    let totalPrice = 0;
    const priceElements = document.getElementsByClassName('price');
    const quantityInputs = document.getElementsByClassName('quantity-input');
    for (let i = 0; i < priceElements.length; i++) {
        totalPrice += parseFloat(priceElements[i].innerText) * quantityInputs[i].value;
    }
    document.getElementById('total-price').innerText = totalPrice.toFixed(2);
}

function updateQuantity(productId, newQuantity) {
    fetch(`updatecart.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productId: productId, quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateTotalPrice();
        } else {
            alert('Error updating quantity');
        }
    });
}

function incrementQuantity(productId) {
    const quantityInput = document.getElementById(`quantity-input-${productId}`);
    const newQuantity = parseInt(quantityInput.value) + 1;
    quantityInput.value = newQuantity;
    updateQuantity(productId, newQuantity);
}

function decrementQuantity(productId) {
    const quantityInput = document.getElementById(`quantity-input-${productId}`);
    const currentQuantity = parseInt(quantityInput.value);
    if (currentQuantity > 1) {
        const newQuantity = currentQuantity - 1;
        quantityInput.value = newQuantity;
        updateQuantity(productId, newQuantity);
    }
}
function proceedToCheckout() {
    // Check if the cart is empty before removing items
    const gridItems = document.querySelectorAll('.grid-item');
    
    if (gridItems.length === 0) {
        alert('Your Cart is Empty!!');
    } else {
        // Remove cart items from the grid container
        gridItems.forEach((item) => item.remove());

        // Update the total price to 0
        document.getElementById('total-price').innerText = '0.00';

        // Redirect to checkout page
        location.href = 'checkout.php';
    }
}

   
function removeFromCart(productId) {
    fetch(`removecart.php?removeID=${productId}`)
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                location.reload();
            } else {
                alert(' product removed from cart');
            }
        });
}

document.addEventListener('DOMContentLoaded', updateTotalPrice);

    </script>
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
    </header>
    <div class="grid-container">
        <?php
        
        include('connection.php');

        // Get the email from session
        $email = $_SESSION['emailidb'];

        // Retrieve the BuyerID based on the session email
        $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $BuyerID = $row['BuyerID'];

            // Fetch cart items for the logged-in user
            // Fetch cart items for the logged-in user
$query = "SELECT cart.ProductID,cart.SellerName, cart.Quantity, cart.ProductName,  cart.ProductPrice, cart.ProductImage 
          FROM cart 
          WHERE cart.BuyerID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $BuyerID);
$stmt->execute();
$result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<div class="grid-item">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['ProductImage']) . '" alt="' . $row['ProductName'] . '">';
                echo '<h2>' . $row['ProductName'] . '</h2>';
                echo '<p>' . $row['SellerName'] . '</p>';
                echo '<p>Quantity: <button onclick="decrementQuantity(' . $row['ProductID'] . ')">-</button> 
                      <input type="number" id="quantity-input-' . $row['ProductID'] . '" class="quantity-input" value="' . $row['Quantity'] . '" min="1" readonly> 
                      <button onclick="incrementQuantity(' . $row['ProductID'] . ')">+</button></p>';
                echo '<p class="price">' . $row['ProductPrice'] . '</p>';
                echo '<button onclick="removeFromCart(' . $row['ProductID'] . ')">Remove</button>';
                echo '</div>';
            }
        } else {
            echo "Error: Buyer not found.";
        }

        $stmt->close();
        $con->close();
        ?>    
    </div>
    <div class="total-price">Total price: <span id="total-price">0.00</span></div>
    <button onclick="proceedToCheckout()">Proceed to checkout</button>
</body>
</html>