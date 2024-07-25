<!DOCTYPE html>
<html>
<head>
    <title>WISHLIST</title>
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

        .price {
            font-size: 18px;
            font-weight: bold;
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
        function addToCart(productId, sellerName, productName, productPrice, productImage, productType) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "addToCart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText.trim() === "success") {
                        alert("Product added to cart successfully!");
                        fetchCartItemCount();
                    } else {
                        alert("Error adding product to cart: " + xhr.responseText);
                    }
                }
            };
            var data = "productId=" + productId + "&sellerName=" + encodeURIComponent(sellerName) + "&productName=" + encodeURIComponent(productName) + "&productPrice=" + productPrice + "&productImage=" + encodeURIComponent(productImage) + "&productType=" + encodeURIComponent(productType);
            xhr.send(data);
        }
    </script>
</head>
<body>
    <header>
        <h1>Wishlist</h1>
    </header>
    <div class="grid-container">
        <?php
        session_start();
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

            // Fetch wishlist items for the logged-in user
            $query = "SELECT ProductID, SellerName, ProductName, ProductPrice, ProductImage, ProductVariety FROM wishlist WHERE BuyerID = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $BuyerID);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<div class="grid-item">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['ProductImage']) . '" alt="' . $row['ProductName'] . '">';
                echo '<h2>' . $row['ProductName'] . '</h2>';
                echo '<p>' . $row['SellerName'] . '</p>';
               echo '<p class="price">Rs ' . $row['ProductPrice'] . '</p>';

                echo "<button onclick=\"addToCart(" . $row['ProductID'] . ", '" . $row['SellerName'] . "', '" . $row['ProductName'] . "', " . $row['ProductPrice'] . ", '" . base64_encode($row['ProductImage']) . "', '" . $row['ProductVariety'] . "')\" class='add-to-cart-button'>Add to cart</button>";
                echo '<button class="btn btn-danger"><a href="remove.php?removeID=' . $row['ProductID'] . '" style="color: white; text-decoration: none;">Remove</a></button>';
                echo '</div>';
            }
        } else {
            echo "Error: Buyer not found.";
        }

        $stmt->close();
        $con->close();
        ?>    
    </div>
</body>
</html>
