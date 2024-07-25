<?php
include('connection.php');
session_start();
$email = $_SESSION['emailidb'];

if (isset($_GET['removeID'])) {
    $id = intval($_GET['removeID']); // Ensure the ID is an integer

    // Fetch the BuyerID based on the email
    $buyer_sql = "SELECT BuyerID FROM buyer WHERE email = ?";
    $buyer_stmt = mysqli_prepare($con, $buyer_sql);
    mysqli_stmt_bind_param($buyer_stmt, "s", $email);
    mysqli_stmt_execute($buyer_stmt);
    mysqli_stmt_bind_result($buyer_stmt, $buyer_id);
    mysqli_stmt_fetch($buyer_stmt);
    mysqli_stmt_close($buyer_stmt);

    if ($buyer_id) {
        // Proceed to delete from wishlist
        $sql = "DELETE FROM wishlist WHERE ProductID = ? AND BuyerID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $buyer_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header('Location: wishlist.php');
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: Item not found in wishlist.<br>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Buyer not found.<br>";
    }
} else {
    echo "Error: Invalid request.<br>";
}

mysqli_close($con);
?>
