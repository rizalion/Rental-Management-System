<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Askari Rentals - Dashboard</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100%;
        }

        .bg {
            background-image: url('imagez/house2.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(6px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .nav {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #1b72e8;
            font-weight: bold;
            padding: 10px 18px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .nav a:hover {
            background-color: #1b72e8;
            color: white;
        }

        .main-content {
            max-width: 900px;
            margin: 100px auto 0;
            background: rgba(240, 248, 255, 0.85);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            margin-top: 0;
            font-size: 32px;
            color: #1b72e8;
        }

        p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="bg"></div>

    <div class="nav">
        <a href="properties.php">Properties</a>
        <a href="tenants.php">Tenants</a>
        <a href="contracts.php">Contracts</a>
        <a href="payments.php">Payments</a> 
        <a href="maintenance.php">Maintenance</a>
        <a href="contact.php">Contact</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Welcome to Askari Rentals</h1>
        <p>
            At Askari Rentals, we help connect tenants with the perfect property and simplify rental management for landlords.
            Manage listings, contracts, tenants, and landlord information all in one place. We handle everything from cozy apartments
            to luxurious houses. Dive into an easy and organized way of renting!
        </p>
    </div>
</body>
</html>
