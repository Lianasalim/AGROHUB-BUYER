<?php
session_start();
include('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input data
    $Name = trim($_POST["Name"]);
    $BuildingNo = trim($_POST["BuildingNo"]);
    $Locality = trim($_POST["Locality"]);
    $Address = trim($_POST["Address"]);
    $Pincode = trim($_POST["Pincode"]);
    $PhoneNo = trim($_POST["PhoneNo"]);

    // Retrieve the BuyerID based on the session email
    $email = $_SESSION['emailidb'];
    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $BuyerID = $row['BuyerID'];

        // Prepare the SQL statement to insert the address
        $sql = "INSERT INTO savedaddresses (BuyerID, Name, BuildingNo, Locality, Address, Pincode, PhoneNo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("issssss", $BuyerID, $Name, $BuildingNo, $Locality, $Address, $Pincode, $PhoneNo);
        $result = $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            echo "Error: " . $stmt->error;
        } else {
            header('Location: disp_address.php');
            exit();
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error: Buyer not found.";
    }
    
    // Close the database connection
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Address</title>
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
        <h1>Add Address</h1>
        <div id="address-container">
            <div class="address-group">
                <form id="addressForm" method="post" action="">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="text" class="form-control" id="Name" placeholder="Enter your name" name="Name" autocomplete="off" required>
                        <div id="error_name" class="error">Please enter a valid name.</div>
                    </div>
                    <div class="form-group">
                        <label for="BuildingNo">Building No</label>
                        <input type="text" class="form-control" id="BuildingNo" placeholder="Enter building no:" name="BuildingNo" autocomplete="off" required>
                        <div id="error_buildingNo" class="error">Please enter a valid building number.</div>
                    </div>
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" class="form-control" id="Address" placeholder="Enter your address" name="Address" autocomplete="off" required>
                        <div id="error_address" class="error">Please enter a valid address.</div>
                    </div>
                    <div class="form-group">
                        <label for="Locality">Locality</label>
                        <input type="text" class="form-control" id="Locality" placeholder="Enter your locality" name="Locality" autocomplete="off" required>
                        <div id="error_locality" class="error">Please enter a valid locality.</div>
                    </div>
                    <div class="form-group">
                        <label for="Pincode">Pincode</label>
                        <input type="text" class="form-control" id="Pincode" placeholder="Enter your pincode" name="Pincode" autocomplete="off" pattern="\d{6}" required>
                        <div id="error_pincode" class="error">Please enter a valid 6-digit pincode.</div>
                    </div>
                    <div class="form-group">
                        <label for="PhoneNo">Phone No</label>
                        <input type="text" class="form-control" id="PhoneNo" placeholder="Enter your phone number" name="PhoneNo" autocomplete="off" pattern="\d{10}" required>
                        <div id="error_phoneNo" class="error">Please enter a valid 10-digit phone number.</div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
