<?php
session_start();
include('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['emailidb'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['emailidb'];

// Fetch the current user details from the database
$sql = "SELECT * FROM buyer WHERE email=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$firstname = $user['fname'];
$lastname = $user['lname'];
$password = $user['password'];
$phonenumber = $user['phonenumber'];
$address = $user['address'];
$BuyerID = $user['BuyerID'];

$profile_updated = false;

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize and trim input data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phonenumber = trim($_POST['phonenumber']);
    $address = trim($_POST['address']);

    // Server-side validation
    $errors = [];
    if (empty($firstname)) $errors[] = "First name is required";
    if (empty($lastname)) $errors[] = "Last name is required";
    if (empty($password)) $errors[] = "Password is required";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    if (!preg_match('/^\d{10}$/', $phonenumber)) $errors[] = "Phone number must be 10 digits";
    if (empty($address)) $errors[] = "Address is required";

    if (empty($errors)) {
        // Update the user's data in the database
        $sql = "UPDATE buyer SET 
                fname=?, 
                lname=?, 
                password=?, 
                phonenumber=?, 
                address=? 
                WHERE BuyerID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $firstname, $lastname,  $password, $phonenumber, $address, $BuyerID);

        if ($stmt->execute() === TRUE) {
            $profile_updated = true;
        } else {
            $errors[] = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    }
}

$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - AGRO HUB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        .container-fluid {
            margin-top: 20px;
        }
        h1 {
            color: #007b5e;
            font-weight: bold;
        }
        .container {
            margin-top: 40px;
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-size: 14px;
            color: #495057;
        }
        .form-group input, .form-group textarea {
            font-size: 14px;
            border-radius: 4px;
            padding: 10px;
            border: 1px solid #ced4da;
        }
        .form-group textarea {
            resize: none;
        }
        .wrap {
            text-align: center;
            margin-top: 20px;
        }
        .wrap button {
            background-color: #007b5e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .wrap button:hover {
            background-color: #005f46;
        }
        p.error {
            color: red;
            font-size: 12px;
            display: none;
        }
    </style>
    <script>
        function validation(field) {
            var value = document.getElementById(field).value;
            var errorField = document.getElementById('error_' + field);
            if (value.trim() === '') {
                errorField.style.display = 'block';
            } else {
                errorField.style.display = 'none';
            }
        }

        function checking() {
            var password = document.getElementById('password').value;
            var confirm_password = document.getElementById('confirm_password').value;
            var errorField = document.getElementById('error_confirmpassword');
            if (password !== confirm_password) {
                errorField.style.display = 'block';
            } else {
                errorField.style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector("form").onsubmit = function (event) {
                var isValid = true;

                var fields = ["firstname", "lastname", "password", "confirm_password", "phonenumber", "address"];
                fields.forEach(function (field) {
                    validation(field);
                    if (document.getElementById(field).value.trim() === '') {
                        isValid = false;
                    }
                });

                checking();
                if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                }
            };

            <?php if ($profile_updated): ?>
            alert('Profile updated successfully');
            window.location.href = 'buyerhome1.php';
            <?php endif; ?>
        });
    </script>
</head>
<body>
<div class="container-fluid text-center">
    <h1>AGRO HUB</h1>
</div>
<div class="container">
    <h1 class="text-center">Edit Profile</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($firstname); ?>" onkeyup="validation('firstname')">
            <p id="error_firstname" class="error">First name is required</p>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($lastname); ?>" onkeyup="validation('lastname')">
            <p id="error_lastname" class="error">Last name is required</p>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" onkeyup="validation('password')">
            <p id="error_password" class="error">Password is required</p>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" onkeyup="checking()">
            <p id="error_confirmpassword" class="error">Passwords do not match</p>
        </div>
        <div class="form-group">
            <label for="phonenumber">Phone Number</label>
            <input type="number" min="1000000000" max="9999999999" name="phonenumber" id="phonenumber" class="form-control" value="<?php echo htmlspecialchars($phonenumber); ?>" onkeyup="validation('phonenumber')">
            <p id="error_phonenumber" class="error">Enter a valid 10-digit phone number</p>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea rows="4" cols="50" name="address" id="address" class="form-control" onkeyup="validation('address')"><?php echo htmlspecialchars($address); ?></textarea>
            <p id="error_address" class="error">Address is required</p>
        </div>
        <div class="wrap">
            <button type="submit" name="submit">UPDATE</button><br>
        </div>
    </form>
</div>
</body>
</html>
