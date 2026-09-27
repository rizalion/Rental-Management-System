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

// Insert or Update Property
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $rent = $_POST['rent'];
    $landlord_name = $_POST['landlord_name'];
    $landlord_contact = $_POST['landlord_contact'];

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // Update
        $edit_id = $_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE properties SET title=?, location=?, monthly_rent=?, landlord_name=?, landlord_contact=? WHERE property_id=?");
        $stmt->bind_param("ssdssi", $title, $location, $rent, $landlord_name, $landlord_contact, $edit_id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO properties (title, location, monthly_rent, landlord_name, landlord_contact) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $title, $location, $rent, $landlord_name, $landlord_contact);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: properties.php");
    exit();
}

// Delete Property
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM properties WHERE property_id = $delete_id");
    header("Location: properties.php");
    exit();
}

// Get property for editing
$edit = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM properties WHERE property_id = $edit_id");
    if ($result->num_rows > 0) {
        $edit = $result->fetch_assoc();
    }
}

// Get all properties
$properties = $conn->query("SELECT * FROM properties");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Properties - Askari Rentals</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef4fb;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1b72e8;
        }

        .form-section, .table-section {
            max-width: 900px;
            margin: auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form input[type="submit"] {
            background-color: #1b72e8;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #125ab6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #1b72e8;
            color: white;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #1b72e8;
            text-decoration: none;
        }

        a.back-link:hover {
            text-decoration: underline;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #1b72e8;
            font-weight: bold;
        }

        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<a class="back-link" href="index.php">&larr; Back to Dashboard</a>

<div class="form-section">
    <h2><?= $edit ? "Edit Property" : "Add New Property" ?></h2>
    <form method="post" action="">
        <input type="hidden" name="edit_id" value="<?= $edit['property_id'] ?? '' ?>">
        <input type="text" name="title" placeholder="Property Title" required value="<?= $edit['title'] ?? '' ?>">
        <input type="text" name="location" placeholder="Location" required value="<?= $edit['location'] ?? '' ?>">
        <input type="number" name="rent" step="0.01" placeholder="Monthly Rent" required value="<?= $edit['monthly_rent'] ?? '' ?>">
        <input type="text" name="landlord_name" placeholder="Landlord Name" required value="<?= $edit['landlord_name'] ?? '' ?>">
        <input type="text" name="landlord_contact" placeholder="Landlord Contact" required value="<?= $edit['landlord_contact'] ?? '' ?>">
        <input type="submit" value="<?= $edit ? "Update Property" : "Add Property" ?>">
    </form>
</div>

<div class="table-section">
    <h2>Existing Properties</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Location</th>
            <th>Rent</th>
            <th>Landlord</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php while($prop = $properties->fetch_assoc()): ?>
            <tr>
                <td><?= $prop['property_id'] ?></td>
                <td><?= htmlspecialchars($prop['title']) ?></td>
                <td><?= htmlspecialchars($prop['location']) ?></td>
                <td>$<?= number_format($prop['monthly_rent'], 2) ?></td>
                <td><?= htmlspecialchars($prop['landlord_name']) ?></td>
                <td><?= htmlspecialchars($prop['landlord_contact']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $prop['property_id'] ?>">Edit</a>
                    <a href="?delete=<?= $prop['property_id'] ?>" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
