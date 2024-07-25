<?php
session_start();
include('connection.php'); // Ensure connection.php contains $con initialization

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['customerName'];
    $SellerName = $_POST['SellerName'];
    $contactInfo = $_POST['contactInfo'];
    $orderID = $_POST['orderID'];
    $complaintCategory = $_POST['complaintCategory'];
    $complaintDescription = $_POST['complaintDescription'];

    // Handling file upload
    $image = NULL; // Initialize with NULL
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Ensure $con is defined
    if (isset($con)) {
        // Prepare the statement with correct types
        $stmt = $con->prepare("INSERT INTO process_complaint (customerName, SellerName, contactInfo, orderID, complaintCategory, complaintDescription, attachments) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissb", $customerName, $SellerName, $contactInfo, $orderID, $complaintCategory, $complaintDescription, $null);

        // Bind the image data if it exists
        if ($image !== NULL) {
            $stmt->send_long_data(6, $image);
        } else {
            $stmt->send_long_data(6, null);
        }

        // Execute the statement and handle the result
        if ($stmt->execute()) {
            echo "Complaint registered successfully.";

            // Prepare email notification
            $adminEmail = "admin@example.com"; // Replace with the admin's actual email address
            $subject = "New Complaint Registered";
            $message = "
                A new complaint has been registered with the following details:\n
                Customer Name: $customerName\n
                Contact Info: $contactInfo\n
                Order ID: $orderID\n
                Complaint Category: $complaintCategory\n
                Complaint Description: $complaintDescription\n
            ";
            $headers = "From: no-reply@example.com"; // Replace with your email address

            // Send email to admin
            if (mail($adminEmail, $subject, $message, $headers)) {
                echo "Notification sent to admin.";
            } else {
                echo "Failed to send notification to admin.";
            }

            // Redirect to order_history.php
            header("Location: order_history.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    } else {
        echo "Database connection failed.";
    }
} else {
    echo "Invalid request method.";
}
?>
