<?php
session_start();
include('connection.php');

if(isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Fetch order details from the database based on the OrderID
    $sql = "SELECT oh.*, b.fname, b.lname FROM order_history oh
            INNER JOIN buyer b ON oh.BuyerID = b.BuyerID
            WHERE oh.orderID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Extract necessary information from the database
        $orderID = $row['orderID'];
        $SellerName = $row['SellerName'];
        $ProductName = $row['ProductName'];
        $ProductPrice = $row['Price'];
        $ProductImage = $row['ProductImage'];
        $Quantity = $row['Quantity'];
        $Date = $row['Date'];
        $DeliveryAddress = $row['DeliveryAddress'];
        $FirstName = $row['fname'];
        $LastName = $row['lname'];

        // Generate the PDF content
        $invoice_content = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Invoice</title>


            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                h1 {
                    color: #333;
                }
                .invoice-details {
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    padding: 20px;
                    border-radius: 5px;
                }
                .invoice-details p {
                    margin: 5px 0;
                }
                .invoice-details strong {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="DOC-20240312-WA0051-removebg-preview.png" alt="Logo" style="max-width: 10%; height: auto;">
                        <h1 class="text-center mb-4">AGROHUB</h1>
                        <h2 class="text-center mb-4">Invoice</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="invoice-details">
                            <p><strong>Order ID:</strong> '.$orderID.'</p>
                            <p><strong>Buyer Name:</strong> '.$FirstName.' '.$LastName.'</p>
                            <p><strong>Seller Name:</strong> '.$SellerName.'</p>
                            <p><strong>Product Name:</strong> '.$ProductName.'</p>
                            <p><strong>Price:</strong> '.$ProductPrice.'</p>
                            <p><strong>Quantity:</strong> '.$Quantity.'</p>
                            <p><strong>Date:</strong> '.$Date.'</p>
                            <p><strong>Delivery Address:</strong> '.$DeliveryAddress.'</p>
                            <!-- Add more details as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';

        // Output the generated HTML content
        echo $invoice_content;
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    echo "<p>Order ID not provided.</p>";
}
?>
