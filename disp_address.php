<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Addresses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('background3.png'); /* Replace 'background3.png' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-color: #f4f7f6;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary, .btn-danger {
            color: white;
        }
        .btn a {
            color: white;
            text-decoration: none;
        }
        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.8;
        }
        table {
            margin-top: 20px;
        }
        table thead {
            background-color: #007b5e;
            color: white;
        }
        table th, table td {
            text-align: center;
        }
        .btn-add-address {
            margin-bottom: 20px;
            background-color: #007b5e;
            color: white;
        }
        .btn-add-address:hover {
            background-color: #005f46;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <button class="btn btn-add-address" onclick="location.href='savedaddress.php'">Add Address</button>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Building No</th>
                <th scope="col">Locality</th>
                <th scope="col">Address</th>
                <th scope="col">Pincode</th>
                <th scope="col">PhoneNo</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php
    session_start();
    include('connection.php');
    
    $email = $_SESSION['emailidb'];
    
    // Retrieve the BuyerID based on the email
    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $BuyerID = $row['BuyerID'];
        
        // Retrieve the addresses using BuyerID
        $sql = "SELECT * FROM savedaddresses WHERE BuyerID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $BuyerID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (isset($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ID = $row['ID'];
                $Name = $row['Name'];
                $BuildingNo = $row['BuildingNo'];
                $Locality = $row['Locality'];
                $Address = $row['Address'];
                $Pincode = $row['Pincode'];
                $PhoneNo = $row['PhoneNo'];
                echo '<tr>
                    <th scope="row">'.$ID.'</th>
                    <td>'.$Name.'</td>
                    <td>'.$BuildingNo.'</td>
                    <td>'.$Locality.'</td>
                    <td>'.$Address.'</td>
                    <td>'.$Pincode.'</td>
                    <td>'.$PhoneNo.'</td>
                    <td>
                        <button class="btn btn-primary"><a href="update.php?updateID='.$ID.'">Update</a></button>
                        <button class="btn btn-danger" onclick="return confirmDelete('.$ID.')"><a href="delete.php?deleteID='.$ID.'">Delete</a></button>
                    </td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="8">No addresses saved.</td></tr>';
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($con);
    ?>
        </tbody>
    </table>
</div>
<script>
function confirmDelete(id) {
    return confirm("Are you sure you want to delete this address?");
}
</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
