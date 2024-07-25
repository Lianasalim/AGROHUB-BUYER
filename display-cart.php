
<?php
require_once 'connect.php';

// Connect to the database
$conn = (new DatabaseConnection())->connect();

// Fetch the cart data
$result = $conn->query("SELECT * FROM cart");

if ($result->num_rows > 0) {
    echo $result->num_rows;

    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?= htmlspecialchars($row['product_id']) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
        </tr>
        <?php
    }
}

// Close the connection
$conn->close();
?>