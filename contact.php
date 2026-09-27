<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "askari_rentals");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - Askari  Rentals</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #e3f0ff; /* Soft Samsung-style blue */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1b72e8;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #1b72e8;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #125ab6;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Contact Us</h2>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <input type="submit" name="submit" value="Send Message">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['message']);
        if ($stmt->execute()) {
            echo "<div class='message success'>Thank you, " . htmlspecialchars($_POST['name']) . "! Your message has been received.</div>";
        } else {
            echo "<div class='message error'>Error saving message.</div>";
        }
        $stmt->close();
    }
    $conn->close();
    ?>
</div>
</body>
</html>
