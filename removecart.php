Updates to keyboard shortcuts … On Thursday, August 1, 2024, Drive keyboard shortcuts will be updated to give you first-letters navigation.Learn more
removecart.php
<?php
session_start();
include('connection.php');

// Check if the removeID parameter is set
if (isset($_GET['removeID'])) {
    // Get the ProductID from the URL parameter
    $productId = $_GET['removeID'];

    // Get the email from session
    $email = $_SESSION['emailidb'];
    echo "Email: $email<br>"; // Debugging

    // Retrieve the BuyerID based on the session email
    $sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $BuyerID = $row['BuyerID'];
        echo "BuyerID: $BuyerID<br>"; // Debugging

        // Delete the item from the cart
        $deleteQuery = "DELETE FROM cart WHERE BuyerID = ? AND ProductID = ?";
        $stmt = $con->prepare($deleteQuery);
        $stmt->bind_param("ii", $BuyerID, $productId);
        if ($stmt->execute()) {
            // If deletion was successful
            echo "success";
        } else {
            // If there was an error during deletion
            echo "Error: " . $stmt->error; // Debugging
        }
    } else {
        // If the BuyerID was not found
        echo "BuyerID not found";
    }

    $stmt->close();
} else {
    // If the removeID parameter is not set
    echo "removeID parameter is not set";
}

$con->close();
?>