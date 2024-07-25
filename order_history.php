<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Seller Name</th>
                <th scope="col">Order ID</th>
                <th scope="col">Product Name</th>
                <th scope="col">Price</th>
                <th scope="col">Product Image</th>
                <th scope="col">Quantity</th>
                <th scope="col">Date</th>
                <th scope="col">Delivery Address</th>
                <th scope="col">Action</th>
                <th scope="col">Review</th>
                <th scope="col">Cancel</th>
                <th scope="col">Invoice</th>
                <th scope="col">Help</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
      <?php
session_start();
include('connection.php');

$email = $_SESSION['emailidb'];
$sql = "SELECT oh.*, b.fname, b.lname, r.star, r.review, r.image AS reviewImage
        FROM order_history oh
        INNER JOIN buyer b ON oh.BuyerID = b.BuyerID
        LEFT JOIN product_review r ON oh.orderID = r.orderID
        WHERE b.email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderID = $row['orderID'];
        $SellerName = $row['SellerName'];
        $ProductName = $row['ProductName'];
        $ProductPrice = $row['Price'];
        $ProductImage = $row['ProductImage'];
        $Quantity = $row['Quantity'];
        $Date = $row['Date'];
        $DeliveryAddress = $row['DeliveryAddress'];
        $star = $row['star'];
        $review = $row['review'];
        $reviewImage = $row['reviewImage'];
        $status = $row['status'];
        $isCancelled = $row['isCancelled'];
        $isDelivered = $row['isDelivered'];
        
        // Set timezone to India
        date_default_timezone_set('Asia/Kolkata');
        
        $currentDateTime = new DateTime();
        $orderDateTime = new DateTime($Date);
        $interval = $currentDateTime->diff($orderDateTime);
        $minutesSinceOrder = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        
        echo '<tr>
              <th scope="row">' . htmlspecialchars($SellerName) . '</th>
              <td>' . htmlspecialchars($orderID) . '</td>
              <td>' . htmlspecialchars($ProductName) . '</td>
              <td>' . htmlspecialchars($ProductPrice) . '</td>
              <td><img src="data:image/jpeg;base64,' . base64_encode($ProductImage) . '" width="100" height="100"></td>
              <td>' . htmlspecialchars($Quantity) . 'kg</td>
              <td>' . htmlspecialchars($Date) . '</td>
              <td>' . htmlspecialchars($DeliveryAddress) . '</td>';

        if ($star !== null && $review !== null) {
            echo '<td>Reviewed</td>
                  <td>' . htmlspecialchars($review) . ' (' . htmlspecialchars($star) . ' stars)';
            if ($reviewImage !== null) {
                echo '<br><img src="data:image/jpeg;base64,' . base64_encode($reviewImage) . '" width="100" height="100">';
            }
            echo '</td>';
        } else {
            if (!$isCancelled) {
                echo '<td>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#reviewModal' . $orderID . '">Rate & Review</button>
                      </td>
                      <td>No review given</td>';
            } else {
                echo '<td colspan="2">Review not allowed (Order Cancelled)</td>';
            }
        }
        
        if ($isCancelled) {
            echo '<td>Cancelled</td>';
            echo '<td colspan="2">Invoice not available (Order Cancelled)</td>';
        } else {
            if ($minutesSinceOrder <= 10) {
                echo '<td>
                      <form id="cancelForm' . $orderID . '" action="cancel_order.php" method="post" onsubmit="return confirmCancel();">
                          <input type="hidden" name="orderID" value="' . $orderID . '">
                          <button type="submit" class="btn btn-danger">Cancel</button>
                      </form>
                      </td>';
            } else {
                echo '<td>Cancellation not allowed</td>';
            }
            echo '<td>
                  <button class="btn btn-info"><a href="invoice.php?orderID=' . $orderID . '" style="color: white; text-decoration: none;">Invoice</a></button> </td>';
echo '<td>
      <button class="btn btn-info"><a href="complaint.php?orderID=' . $orderID . '&customerName=' . urlencode($row['fname'] . ' ' . $row['lname']) . '&SellerName=' . urlencode($SellerName) . '" style="color: white; text-decoration: none;">Help</a></button>
      </td>';

                     }

        // Display status
        if ($isCancelled) {
            echo '<td>Cancelled</td>';
        } else if ($isDelivered) {
            echo '<td>Processing</td>';
        } else {
            echo '<td>Delivered</td>';
        }

        echo '</tr>';

        // Review Modal
        echo '<div class="modal fade" id="reviewModal' . $orderID . '" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel' . $orderID . '" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel' . $orderID . '">Rate & Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="review_buyer.php" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="rating">Rating:</label>
                        <select id="rating" name="rating">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="review">Review:</label>
                        <textarea class="form-control" id="review" name="review" rows="3" required></textarea>
                      </div>
                      <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                      </div>
                      <input type="hidden" name="orderID" value="' . $orderID . '">
                      <input type="hidden" name="ProductID" value="' . $row['ProductID'] . '">
                      <input type="hidden" name="SellerName" value="' . $SellerName . '">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
    }
} else {
    echo "<div class='grid-item'><p>No History.</p></div>";
}
?>

        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    function confirmCancel() {
        return confirm('Are you sure you want to cancel this order?');
    }
</script>
</body>
</html>
