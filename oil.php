<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AGROHUB</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      font-family: Arial, sans-serif;
      background-image: url('oilbackground.png'); /* Replace with your image URL */
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      margin: 0;
      padding: 0;
    }

    header {
      color: #006633;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      margin: 0;
    }

    header button {
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
    }

    header button:hover {
      background-color: #ddd;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      grid-gap: 10px;
      padding: 10px;
    }

    .grid-item {
      background-color: white;
      border: 1px solid lightgray;
      padding: 20px;
      text-align: center;
    }

    .grid-item img {
      width: 100px;
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
  </style>
</head>
<body>
  <header>
    <h1>AGROHUB</h1>
    <div>
      <button onclick="location.href='order_history.php'">Orders</button>
      <button onclick="location.href='cart1.php'">Cart</button>
    </div>
    <div>
      <form action="search.php" method="get">
        <input type="search" name="search" placeholder="Search" required>
      </form>
    </div>
    <?php
      if (isset($_GET['search'])) {
        require 'search.php';
      }
    ?>
  </header>
  <main>
    <div class="grid-container">
        
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "ahproject";

      $con = new mysqli($servername, $username, $password, $dbname);

      if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }

      $sql = "SELECT p.ProductID, p.SellerName, p.ProductName, p.ProductPrice, p.ProductDesc, p.ProductImage,  p.Quantity,
              COALESCE(AVG(r.star), 0) AS avgRating, COUNT(r.review) AS reviewCount
              FROM oil p
              LEFT JOIN product_review r ON p.SellerName = r.SellerName
              GROUP BY p.ProductID";
      $result = $con->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $avgRating = round($row['avgRating'], 1);
          $reviewCount = $row['reviewCount'];
          
          echo "<div class='grid-item'>";
          echo "<img src='data:image/jpeg;base64," . base64_encode($row['ProductImage']) . "' alt='" . $row['ProductName'] . "'>";
          echo "<p>" . $row['SellerName'] . "</p>";
          echo "<h2>" . $row['ProductName'] . "</h2>";
          echo "<p>Rs." . $row['ProductPrice'] . "</p>";
          echo "<p>" . $row['ProductDesc'] . "</p>";
          echo "<p>Rating: 
              <span class='stars'>";
                  for ($i = 0; $i < 5; $i++) {
                      echo ($i < round($avgRating)) ? '&#9733;' : '&#9734;';
                  }
          echo "</span> 
              ($reviewCount reviews)</p>";

          if ($row['Quantity'] == 0) {
            echo "<p style='color: red;'>Out of stock</p>";
          } else {
            echo "<button onclick=\"addToCart(" . $row['ProductID'] . ", '" . $row['SellerName'] . "', '" . $row['ProductName'] . "', " . $row['ProductPrice'] . ", '" . base64_encode($row['ProductImage']) . "')\" class='add-to-cart-button'>Add to cart</button>";
            echo "<button onclick=\"addToWishlist(" . $row['ProductID'] . ", '" . $row['SellerName'] . "', '" . $row['ProductName'] . "', " . $row['ProductPrice'] . ", '" . base64_encode($row['ProductImage']) . "')\" class='wishlist'>Wishlist</button>";
          }
          
          echo "</div>";
        }
      } else {
        echo "<p>No products found.</p>";
      }

      $con->close();
      ?>
    </div>
  </main>
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

    function addToWishlist(productId, sellerName, productName, productPrice, productImage) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "addToWishlist.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          if (xhr.responseText.trim() === "success") {
            alert("Product added to wishlist successfully!");
          } else {
            alert("Error adding product to wishlist: " + xhr.responseText);
          }
        }
      };
      var data = "productId=" + productId + "&sellerName=" + encodeURIComponent(sellerName) + "&productName=" + encodeURIComponent(productName) + "&productPrice=" + productPrice + "&productImage=" + encodeURIComponent(productImage);
      xhr.send(data);
    }
 </script>
</body>
</html>
