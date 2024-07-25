<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ahproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate input data
$firstname = trim($_POST["firstname"]);
$lastname = trim($_POST["lastname"]);
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$phonenumber = trim($_POST["phonenumber"]);
$address = trim($_POST["address"]);

$errors = [];

if (empty($firstname) || !preg_match("/^[a-zA-Z ]*$/", $firstname)) {
    $errors[] = "Invalid first name";
}
if (empty($lastname) || !preg_match("/^[a-zA-Z ]*$/", $lastname)) {
    $errors[] = "Invalid last name";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email";
}
if (empty($password) || strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}
if (empty($phonenumber) || !preg_match("/^[0-9]{10}$/", $phonenumber)) {
    $errors[] = "Invalid phone number";
}
if (empty($address)) {
    $errors[] = "Address is required";
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    exit();
}

// Check if email already exists
$emailCheckSql = "SELECT * FROM buyer WHERE email = ?";
$emailStmt = $conn->prepare($emailCheckSql);

if ($emailStmt) {
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $result = $emailStmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "Already Registered!!";
    } else {
        // Email does not exist, proceed with registration
        $sql = "INSERT INTO buyer (fname, lname, email, password, phonenumber, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssss", $firstname, $lastname, $email, $password, $phonenumber, $address);

            if ($stmt->execute()) {
                $_SESSION['ahproject'] = 'true';
                $_SESSION['emailidb'] = $email;
                header("Location: login_buyer.html"); // Redirect to login page after successful registration
                exit(); // Make sure to exit after redirecting
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    $emailStmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
