<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Registration</title>
    <style>
        body {
            background-image: url('background3.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6"><path d="M6 0L0 6h12z"/></svg>') no-repeat right 10px center;
            background-size: 12px 6px;
            padding-right: 30px;
        }
        textarea {
            height: 150px;
        }
        input[type="file"] {
            display: none;
        }
        #image-upload {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 7px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #image-upload:hover {
            background-color: #0056b3;
        }
        input[type="submit"] {
            background-color: #006633;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Register Your Complaint</h1>
    <form action="submit_complaint.php" method="POST" enctype="multipart/form-data">
        <label for="orderID">Order ID:</label><br>
        <input type="number" id="orderID" name="orderID" value="<?php echo htmlspecialchars($_GET['orderID']); ?>" readonly><br>
        
        <label for="customerName">Customer Name:</label><br>
        <input type="text" id="customerName" name="customerName" value="<?php echo htmlspecialchars($_GET['customerName']); ?>" readonly><br>
        
        <label for="SellerName">Seller Name:</label><br>
        <input type="text" id="SellerName" name="SellerName" value="<?php echo htmlspecialchars($_GET['SellerName']); ?>" readonly><br>
        
        <label for="contactInfo">Contact Info:</label><br>
        <input type="text" id="contactInfo" name="contactInfo" required><br>

        <label for="complaintCategory">Complaint Category:</label><br>
        <select id="complaintCategory" name="complaintCategory" required>
            <option value="" disabled selected>Select a category</option>
            <option value="Delivery Issue">Delivery Issue</option>
            <option value="Product Quality">Product Quality</option>
            <option value="Payment Problem">Payment Problem</option>
            <option value="Customer Service">Customer Service</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="complaintDescription">Complaint Description:</label><br>
        <textarea id="complaintDescription" name="complaintDescription" required></textarea><br>

        <label for="attachments">Upload Image:</label><br>
        <label id="image-upload" for="image">Choose File</label>
        <input type="file" id="image" name="image" accept="image/*"><br>

        <input type="submit" value="Submit Complaint">
    </form>
</body>
</html>
