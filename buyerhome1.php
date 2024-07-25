<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGROHUB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
            <style>
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-image: url('newimg2.png'); /* Replace with your image URL */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            width: 100%;
            animation: slideInDown 1s ease-in-out;
        }

        @keyframes slideInDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        header input[type="search"] {
            width: 200px;
            padding: 5px;
            border: none;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        header input[type="search"]:focus {
            transform: scale(1.05);
        }

        header button, main button {
            background-color: #006633;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header button:hover, main button:hover {
            background-color: #444;
            transform: scale(1.05);
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding: 20px;
        }

        main a {
            text-decoration: none;
            color: #006633;
            margin-bottom: 10px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        main a:hover {
            color: #004d33;
            transform: scale(1.05);
        }

        main h2 {
            font-family: 'Arial', sans-serif;
            color: #006633;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-image: url('newimg2.png'); /* Same background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
            transition: width 0.5s ease, opacity 0.5s ease;
            padding-top: 60px;
            opacity: 0;
        }

        .sidebar.open {
            width: 250px;
            opacity: 1;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 25px;
            color: black;
            display: block;
            transition: color 0.3s ease;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            transition: transform 0.3s ease;
        }

        .sidebar .closebtn:hover {
            transform: rotate(90deg);
        }

        #main {
            transition: margin-left 0.5s ease;
            padding: 16px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #006633;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .openbtn:hover {
            background-color: #444;
            transform: scale(1.1);
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeInRight 1s ease-in-out;
        }

        @keyframes fadeInRight {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-header h1 {
            font-size: 2rem;
            color: black;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        .profile-details {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-details p {
            font-size: 1rem;
            margin: 10px 0;
            color: black;
            animation: fadeIn 1s ease-in-out;
        }

        .profile-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-button {
            padding: 10px 20px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #006633;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 75%;
            max-width: 300px;
            text-align: center;
        }

        .profile-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .profile-button:focus {
            outline: none;
        }

        .profile-button:active {
            background-color: #1e7e34;
        }

        #cart {
            position: relative;
            cursor: pointer;
        }

        #cart img {
            height: 30px;
            width: 30px;
            transition: transform 0.3s ease;
        }

        #cart img:hover {
            transform: scale(1.2);
        }

        #cart span {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px;
            font-size: 12px;
        }
       .logo {
            animation: bounceIn 2s infinite alternate;
            transition: transform 0.3s ease;
        }

        @keyframes bounceIn {
            from { transform: scale(1); }
            to { transform: scale(1.2); }
        }

        .logo:hover {
            transform: rotate(360deg);
        }
    </style>


</head>
<body>
    <div class="sidebar" id="mySidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="profile-container">
            <div class="profile-header">
                <h1>My Account</h1>
            </div>
            <div class="profile-details">
                <?php
                if (!isset($_SESSION['emailidb'])) {
                    die('Please log in.');
                }

                $email = $_SESSION['emailidb'];

                $conn = new mysqli("localhost", "root", "", "ahproject");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT fname, email, phonenumber FROM buyer WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                } else {
                    echo "No results found";
                }

                $stmt->close();
                $conn->close();
                ?>

                <?php if (isset($user['fname'])): ?>
                    <p><strong></strong> <?php echo $user['fname']; ?></p>
                <?php endif; ?>
                <?php if (isset($user['phonenumber'])): ?>
                    <p><strong></strong> <?php echo $user['phonenumber']; ?></p>
                <?php endif; ?>
                <?php if (isset($user['email'])): ?>
                    <p><strong></strong> <?php echo $user['email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="profile-buttons">
                <button class="profile-button" onclick="location.href='order_history.php'">My Orders</button>
                <button class="profile-button" onclick="location.href='wishlist.php'">Wishlist</button>
                <button class="profile-button" onclick="location.href='disp_address.php'">My Address</button>
                <button class="profile-button" onclick="location.href='update_profile.php'">Edit Profile</button>
                <button class="profile-button" onclick="location.href='terms and conditions.html'">Terms and Conditions</button>
                 <button class="profile-button" onclick="location.href='buy_about.html'">AboutUs</button>

                <button class="profile-button" onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>
    </div>

    <div id="main">
        <header>
            <button class="openbtn" onclick="openNav()">☰ </button>
            



            <div>
                <form action="search.php" method="get">
                    <input type="search" name="search" placeholder="Search" required>
                </form>
            </div>

            <?php
            if (isset($_GET['search'])) {
                require 'search.php';
            }
            ?>

            <div id="cart" onclick="location.href='cart1.php'">
                <img src="cart_icon.png" alt="Cart">
                            </div>
        </header>

        <main>
            <img src="DOC-20240312-WA0051-removebg-preview.png" alt="Logo" style="max-width: 10%; height: auto;">
            <h2>OUR PRODUCTS</h2>
            <div>
                <button onclick="location.href='fruitt.php'">Fruits</button>
                <button onclick="location.href='veg.php'">Vegetables</button>
                <button onclick="location.href='homemade.php'">Homemades</button>
                <button onclick="location.href='oil.php'">Oils</button>
                <button onclick="location.href='powder.php'">Powders</button>
                <button onclick="location.href='map.html'">Map of Machineries and Pesticides</button>
            </div>
        </main>
    </div>

    <script>
        function openNav() {
    document.getElementById("mySidebar").classList.add('open');
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").classList.remove('open');
    document.getElementById("main").style.marginLeft = "0";
}
        function updateCartItemCount(count) {
            document.getElementById("cartItemCount").innerText = count;
        }

        // Function to fetch the cart item count from the server
        function fetchCartItemCount() {
            fetch('get_cart_icon_count.php')
                .then(response => response.text())
                .then(count => {
                    updateCartItemCount(count);
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        // Call the fetchCartItemCount function when the DOM content is loaded
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartItemCount();
        });
    </script>
</body>
</html>