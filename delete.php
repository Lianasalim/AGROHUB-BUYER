<?php
include('connection.php');

if (isset($_GET['deleteID'])) {
    $id = intval($_GET['deleteID']); // Ensure the ID is an integer
    $sql = "DELETE FROM savedaddresses WHERE ID = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header('location:disp_address.php');
    } else {
        echo "Error: Address not found.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error: Invalid request.";
}

mysqli_close($con);
?>