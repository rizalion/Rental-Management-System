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

// Add or Update Tenant
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if (isset($_POST['edit_id'])) {
        // Update existing tenant
        $edit_id = $_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE tenants SET name=?, phone=?, email=? WHERE tenant_id=?");
        $stmt->bind_param("sssi", $name, $phone, $email, $edit_id);
    } else {
        // Insert new tenant
        $stmt = $conn->prepare("INSERT INTO tenants (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: tenants.php");
    exit();
}

// Delete Tenant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tenants WHERE tenant_id = $id");
    header("Location: tenants.php");
    exit();
}

// Get data for editing
$edit_mode = false;
$edit_data = ["name" => "", "phone" => "", "email" => ""];
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tenants WHERE tenant_id = $id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}

// Fetch all tenants
$tenants = $conn->query("SELECT * FROM tenants");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tenants - Askari Rentals</title>
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
        <h2><?= $edit_mode ? "Edit Tenant" : "Add New Tenant" ?></h2>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Tenant Name" required value="<?= htmlspecialchars($edit_data['name']) ?>">
            <input type="text" name="phone" placeholder="Phone Number" required value="<?= htmlspecialchars($edit_data['phone']) ?>">
            <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($edit_data['email']) ?>">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="edit_id" value="<?= $id ?>">
            <?php endif; ?>
            <input type="submit" value="<?= $edit_mode ? "Update Tenant" : "Add Tenant" ?>">
        </form>
    </div>

    <div class="table-section">
        <h2>Existing Tenants</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while($tenant = $tenants->fetch_assoc()): ?>
                <tr>
                    <td><?= $tenant['tenant_id'] ?></td>
                    <td><?= htmlspecialchars($tenant['name']) ?></td>
                    <td><?= htmlspecialchars($tenant['phone']) ?></td>
                    <td><?= htmlspecialchars($tenant['email']) ?></td>
                    <td class="actions">
                        <a href="?edit=<?= $tenant['tenant_id'] ?>">Edit</a>
                        <a href="?delete=<?= $tenant['tenant_id'] ?>" onclick="return confirm('Are you sure you want to delete this tenant?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
<?php $conn->close(); ?>
