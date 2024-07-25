<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #006633;
            padding: 7px;
            text-align: center;
            color: white;
            font-size: 24px;
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
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .grid-item:hover {
            transform: translateY(-5px);
        }
        .grid-item img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .total-price {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        #selected-address {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .modal-content {
            border-radius: 5px;
        }
        .modal-header, .modal-footer {
            background-color: #f1f1f1;
        }
        .close {
            color: black;
        }
        .modal-title {
            font-size: 20px;
        }
        .select-address-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px 0;
            transition: background-color 0.3s;
        }
        .select-address-btn:hover {
            background-color: #45a049;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
<header>
    <h1>Checkout</h1>
</header>
<div class="grid-container">
    <?php
    session_start();
    include('connection.php');

    $email = $_SESSION['emailidb'];

    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $BuyerID = $row['BuyerID'];

        $query = "SELECT cart.ProductID,cart.SellerName, cart.Quantity, cart.ProductName, cart.ProductType, cart.ProductPrice, cart.ProductImage 
                  FROM cart 
                  WHERE cart.BuyerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $BuyerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalPrice = 0;

        while ($row = $result->fetch_assoc()) {
            echo '<div class="grid-item">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['ProductImage']) . '" alt="' . $row['ProductName'] . '">';
            echo '<p>' . $row['SellerName'] . '</p>';
            echo '<h2>' . $row['ProductName'] . '</h2>';
            echo '<p>Type: ' . $row['ProductType'] . '</p>';
            echo '<p>Quantity: ' . $row['Quantity'] . 'kg</p>';
            echo '<p class="price">Rs.' . number_format($row['ProductPrice'], 2) . '</p>';
            echo '</div>';

            $totalPrice += $row['ProductPrice'] * $row['Quantity'];
        }
    } else {
        echo "Error: Buyer not found.";
    }

    $stmt->close();
    $con->close();
    ?>
</div>
<div class="total-price">Total price: <span id="total-price"><?php echo 'Rs.' . number_format($totalPrice, 2); ?></span></div>
<div id="selected-address">Selected Address: <span id="address-details">None</span></div>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addressModal">Select Address</button>

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<form id="orderForm" action="confirm_order.php" method="post">
    <input type="hidden" name="selected_address" id="hidden-address">
    <button type="submit" class="btn btn-primary">Confirm Order</button>
</form>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addressModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('.modal-body').load('check_address.php');
        });

        $(document).on('click', '.select-address-btn', function() {
            var address = $(this).data('address');
            $('#address-details').text(address);
            $('#hidden-address').val(address);
            $('#addressModal').modal('hide');
        });

        $('#orderForm').submit(function(event) {
            if ($('#hidden-address').val() === '') {
                alert('Please select an address before confirming the order.');
                event.preventDefault();
            }
        });
    });
</script>
</body>
</html>
