<?php
session_start();
include('connection.php');

if (isset($_GET['updateID']) && is_numeric($_GET['updateID'])) {
    $updateID = $_GET['updateID'];

    // Retrieve the address data using the ID
    $sql = "SELECT * FROM savedaddresses WHERE ID = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $updateID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $Name = $row['Name'];
        $BuildingNo = $row['BuildingNo'];
        $Locality = $row['Locality'];
        $Address = $row['Address'];
        $Pincode = $row['Pincode'];
        $PhoneNo = $row['PhoneNo'];
    } else {
        echo "Invalid or missing address ID.";
        exit;
    }
} else {
    echo "Invalid or missing address ID.";
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $Name = mysqli_real_escape_string($con, $_POST['Name']);
    $BuildingNo = mysqli_real_escape_string($con, $_POST['BuildingNo']);
    $Locality = mysqli_real_escape_string($con, $_POST['Locality']);
    $Address = mysqli_real_escape_string($con, $_POST['Address']);
    $Pincode = mysqli_real_escape_string($con, $_POST['Pincode']);
    $PhoneNo = mysqli_real_escape_string($con, $_POST['PhoneNo']);

    // Update the address data in the database
    $sql = "UPDATE savedaddresses SET Name = ?, BuildingNo = ?, Locality = ?, Address = ?, Pincode = ?, PhoneNo = ? WHERE ID = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssisi", $Name, $BuildingNo, $Locality, $Address, $Pincode, $PhoneNo, $updateID);
    mysqli_stmt_execute($stmt);

    // Redirect back to the saved addresses page
    echo "<script>alert('Address updated successfully!');</script>";
    echo "<script>window.location.href='disp_address.php';</script>";
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Address</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('background3.png'); /* Replace 'background3.png' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            padding-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007b5e;
            border-color: #007b5e;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #005f46;
            border-color: #005f46;
        }
        .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 20px;
        }
        .error {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Address</h1>
        <div id="address-container">
            <div class="address-group">
                <form id="addressForm" method="post" action="update.php?updateID=<?php echo $updateID; ?>">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="text" class="form-control" id="Name" placeholder="Enter your name" name="Name" autocomplete="off" value="<?php echo htmlspecialchars($Name); ?>" required>
                        <div id="error_name" class="error">Please enter a valid name.</div>
                    </div>
                    <div class="form-group">
                        <label for="BuildingNo">Building No</label>
                        <input type="text" class="form-control" id="BuildingNo" placeholder="Enter building no:" name="BuildingNo" autocomplete="off" value="<?php echo htmlspecialchars($BuildingNo); ?>" required>
                        <div id="error_buildingNo" class="error">Please enter a valid building number.</div>
                    </div>
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" class="form-control" id="Address" placeholder="Enter your address" name="Address" autocomplete="off" value="<?php echo htmlspecialchars($Address); ?>" required>
                        <div id="error_address" class="error">Please enter a valid address.</div>
                    </div>
                    <div class="form-group">
                        <label for="Locality">Locality</label>
                        <input type="text" class="form-control" id="Locality" placeholder="Enter your locality" name="Locality" autocomplete="off" value="<?php echo htmlspecialchars($Locality); ?>" required>
                        <div id="error_locality" class="error">Please enter a valid locality.</div>
                    </div>
                    <div class="form-group">
                        <label for="Pincode">Pincode</label>
                        <input type="text" class="form-control" id="Pincode" placeholder="Enter your pincode" name="Pincode" autocomplete="off" value="<?php echo htmlspecialchars($Pincode); ?>" pattern="\d{6}" required>
                        <div id="error_pincode" class="error">Please enter a valid 6-digit pincode.</div>
                    </div>
                    <div class="form-group">
                        <label for="PhoneNo">Phone No</label>
                        <input type="text" class="form-control" id="PhoneNo" placeholder="Enter your phone number" name="PhoneNo" autocomplete="off" value="<?php echo htmlspecialchars($PhoneNo); ?>" pattern="\d{10}" required>
                        <div id="error_phoneNo" class="error">Please enter a valid 10-digit phone number.</div>
                    </div>
                    <div class="wrap">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("addressForm").onsubmit = function(event) {
            var isValid = true;
            
            var name = document.getElementById("Name").value;
            if (name.trim() === "") {
                document.getElementById("error_name").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_name").style.display = "none";
            }

            var buildingNo = document.getElementById("BuildingNo").value;
            if (buildingNo.trim() === "") {
                document.getElementById("error_buildingNo").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_buildingNo").style.display = "none";
            }

            var address = document.getElementById("Address").value;
            if (address.trim() === "") {
                document.getElementById("error_address").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_address").style.display = "none";
            }

            var locality = document.getElementById("Locality").value;
            if (locality.trim() === "") {
                document.getElementById("error_locality").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_locality").style.display = "none";
            }

            var pincode = document.getElementById("Pincode").value;
            var pincodePattern = /^\d{6}$/;
            if (!pincodePattern.test(pincode)) {
                document.getElementById("error_pincode").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_pincode").style.display = "none";
            }

            var phoneNo = document.getElementById("PhoneNo").value;
            var phoneNoPattern = /^\d{10}$/;
            if (!phoneNoPattern.test(phoneNo)) {
                document.getElementById("error_phoneNo").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("error_phoneNo").style.display = "none";
            }

            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>
