<?php
session_start();

// Check login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hardcoded login
    if ($username === "munir" && $password === "munir123") {
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Askari Rentals</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Background container with blur */
        .bg {
            background: url('imagez/house1.jpg') no-repeat center center fixed;
            background-size: cover;
            filter: blur(8px);
            height: 100%;
            width: 100%;
            position: absolute;
            z-index: -1;
        }

        /* Foreground container (form) */
        .content {
            position: relative;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="bg"></div> <!-- Blurred background image -->
<div class="content">
    <form method="post">
        <h2>Admin Login</h2>
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    </form>
</div>
</body>
</html>
