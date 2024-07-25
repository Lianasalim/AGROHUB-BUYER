<?php
session_start();

$searchTerm = $_GET['search'];

// Connect to the database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "ahproject";

$conn = mysqli_connect($host, $user, $password, $dbname);

// Check for a search query
if (!empty($searchTerm)) {
    // Prepare the SQL query
    $query = "
    SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductDesc, p.ProductPrice, p.ProductImage,p.Quantity, COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
    FROM fruits p
    LEFT JOIN product_review r ON p.ProductID = r.ProductID
    WHERE p.ProductName LIKE '%$searchTerm%' OR p.ProductVariety LIKE '%$searchTerm%'
    GROUP BY p.ProductID
    
    UNION
    
    SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductDesc, p.ProductPrice, p.ProductImage,p.Quantity, COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
    FROM vegetables p
    LEFT JOIN product_review r ON p.ProductID = r.ProductID
    WHERE p.ProductName LIKE '%$searchTerm%' OR p.ProductVariety LIKE '%$searchTerm%'
    GROUP BY p.ProductID
    
    UNION
    
    SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductDesc, p.ProductPrice, p.ProductImage,p.Quantity, COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
    FROM homemades p
    LEFT JOIN product_review r ON p.ProductID = r.ProductID
    WHERE p.ProductName LIKE '%$searchTerm%'
    GROUP BY p.ProductID
    
    UNION
    
    SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductDesc, p.ProductPrice, p.ProductImage,p.Quantity, COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
    FROM oil p
    LEFT JOIN product_review r ON p.ProductID = r.ProductID
    WHERE p.ProductName LIKE '%$searchTerm%'
    GROUP BY p.ProductID
    
    UNION
    
    SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductDesc, p.ProductPrice, p.ProductImage,p.Quantity, COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
    FROM powder p
    LEFT JOIN product_review r ON p.ProductID = r.ProductID
    WHERE p.ProductName LIKE '%$searchTerm%'
    GROUP BY p.ProductID";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check for a query error
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Display the search results
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="grid-container">';
        while ($row = mysqli_fetch_assoc($result)) {
            $avgRating = round($row['avgRating'], 1);
            $reviewCount = $row['reviewCount'];

            echo '<div class="grid-item">';
            echo "<p>" . htmlspecialchars($row['SellerName']) . "</p>";
            echo "<p><strong>" . htmlspecialchars($row['ProductName']) . "</strong></p>";
            echo "<p>" . htmlspecialchars($row['ProductDesc']) . "</p>";
            echo "<p>Price: " . htmlspecialchars($row['ProductPrice']) . "</p>";
            echo "<p>Rating: 
                <span class='stars'>";
                    for ($i = 0; $i < 5; $i++) {
                        echo ($i < round($avgRating)) ? '&#9733;' : '&#9734;';
                    }
            echo "</span> 
                ($reviewCount reviews)</p>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($row['ProductImage']) . "' alt='" . htmlspecialchars($row['ProductName']) . "'>";
            echo '<br>';
 if ($row['Quantity'] == 0) {
            echo "<p style='color: red;'>Out of stock</p>";
          }else {
            echo "<button onclick=\"addToCart(" . $row['ProductID'] . ", '" . $row['SellerName'] . "', '" . $row['ProductName'] . "', " . $row['ProductPrice'] . ", '" . base64_encode($row['ProductImage']) . "')\" class='add-to-cart-button'>Add to cart</button>";
            echo "<button onclick=\"addToWishlist(" . $row['ProductID'] . ", '" . $row['SellerName'] . "', '" . $row['ProductName'] . "', " . $row['ProductPrice'] . ", '" . base64_encode($row['ProductImage']) . "')\" class='wishlist'>Wishlist</button>";
            echo "</div>";
        }}
        echo '</div>';
    } else {
        echo "No product found.";
    }
} else {
    // Display an error message if no search term was provided
    echo "Please enter a search term.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            padding: 10px;
            max-width: 200px; /* Adjust the maximum width as needed */
            margin: 0 auto; /* Center the grid container */
        }
        .grid-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            background-color: #fff;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .add-to-cart-button, .wishlist {
            background-color: #006633;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 3px;
            display: inline-block;
        }
        .add-to-cart-button {
            margin-right: 10px;
        }
        .review-button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        /* Hide the reviews by default */
        .reviews {
            display: none;
            padding: 10px;
        }
        .stars {
            display: inline-block;
        }
        .stars .star {
            color: gold;
            font-size: 20px;
            margin-right: 2px;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <script>
        function toggleReviews(sellerName) {
            var reviewDiv = document.getElementById('reviews-' + sellerName);
            if (reviewDiv.style.display === 'none' || reviewDiv.style.display === '') {
                // Fetch and display reviews
                fetch('fetchReviews.php?sellerName=' + sellerName)
                    .then(response => response.json())
                    .then(data => {
                        if (data.reviews && data.reviews.length > 0) {
                            reviewDiv.innerHTML = '';
                            data.reviews.forEach(review => {
                                var reviewHtml = '<div>' +
                                    '<p>Rating: ' + review.star + '</p>' +
                                    '<p>Review: ' + review.review + '</p>';
                                if (review.image) {
                                    reviewHtml += '<img src="' + review.image + '" alt="Review Image" style="width:100px;height:auto;">';
                                }
                                reviewHtml += '</div>';
                                reviewDiv.innerHTML += reviewHtml;
                            });

                            // Display average rating
                            var avgRatingHtml = '<p>Average Rating: ' + data.avgRating + ' (' + data.reviewCount + ' reviews)</p>';
                            reviewDiv.innerHTML = avgRatingHtml + reviewDiv.innerHTML;
                        } else {
                            reviewDiv.innerHTML = '<p>No reviews found for this seller.</p>';
                        }
                        reviewDiv.style.display = 'block';
                    });
            } else {
                // Hide reviews
                reviewDiv.style.display = 'none';
            }
        }

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

        function addToWishlist(productId, sellerName, productName, productPrice, productImage, productType) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "addToWishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "success") {
                        alert("Product added to wishlist successfully!");
                    } else {
                        alert("Failed to add product to wishlist.");
                    }
                }
            };
            xhr.send("productId=" + productId + "&sellerName=" + sellerName + "&productName=" + productName + "&productPrice=" + productPrice + "&productImage=" + productImage + "&productType=" + productType);
        }
    </script>
</body>
</html>
