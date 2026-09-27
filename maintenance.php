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

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM maintenance_requests WHERE request_id = $id");
    header("Location: maintenance.php");
    exit();
}

// Handle Edit
$editMode = false;
$editData = null;
if (isset($_GET['edit'])) {
    $editMode = true;
    $edit_id = intval($_GET['edit']);
    $editResult = $conn->query("SELECT * FROM maintenance_requests WHERE request_id = $edit_id");
    if ($editResult->num_rows > 0) {
        $editData = $editResult->fetch_assoc();
    } else {
        $editMode = false;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $property_id = $_POST['property_id'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $request_date = $_POST['request_date'] ?? date("Y-m-d");

    if (isset($_POST['update_id'])) {
        // Update mode
        $id = intval($_POST['update_id']);
        $stmt = $conn->prepare("UPDATE maintenance_requests SET property_id=?, description=?, status=?, request_date=? WHERE request_id=?");
        $stmt->bind_param("isssi", $property_id, $description, $status, $request_date, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO maintenance_requests (property_id, description, request_date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $property_id, $description, $request_date, $status);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: maintenance.php");
    exit();
}

// Get property options
$propertyOptions = $conn->query("SELECT property_id, title FROM properties");

// Fetch all maintenance requests with property titles
$requests = $conn->query("SELECT m.*, p.title FROM maintenance_requests m JOIN properties p ON m.property_id = p.property_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Requests - Askari Rentals</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f7fb;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1b72e8;
        }

        .form-section, .table-section {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        form input, textarea, select {
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

        .actions a {
            margin-right: 10px;
            color: #1b72e8;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>

<a class="back-link" href="index.php">&larr; Back to Dashboard</a>

<div class="form-section">
    <h2><?= $editMode ? "Edit Request" : "Submit Maintenance Request" ?></h2>
    <form method="post" action="">
        <select name="property_id" required>
            <option value="">-- Select Property --</option>
            <?php while($row = $propertyOptions->fetch_assoc()): ?>
                <option value="<?= $row['property_id'] ?>" <?= $editMode && $editData['property_id'] == $row['property_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['title']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <textarea name="description" rows="4" placeholder="Describe the issue..." required><?= $editMode ? htmlspecialchars($editData['description']) : '' ?></textarea>

        <select name="status" required>
            <option value="Pending" <?= $editMode && $editData['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $editMode && $editData['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Completed" <?= $editMode && $editData['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <?php if ($editMode): ?>
            <input type="hidden" name="update_id" value="<?= $editData['request_id'] ?>">
        <?php endif; ?>

        <input type="submit" value="<?= $editMode ? 'Update Request' : 'Submit Request' ?>">
    </form>
</div>

<div class="table-section">
    <h2>Maintenance History</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Property</th>
            <th>Description</th>
            <th>Request Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($req = $requests->fetch_assoc()): ?>
        <tr>
            <td><?= $req['request_id'] ?></td>
            <td><?= htmlspecialchars($req['title']) ?></td>
            <td><?= htmlspecialchars($req['description']) ?></td>
            <td><?= $req['request_date'] ?></td>
            <td><?= $req['status'] ?></td>
            <td class="actions">
                <a href="?edit=<?= $req['request_id'] ?>">Edit</a>
                <a href="?delete=<?= $req['request_id'] ?>" onclick="return confirm('Delete this request?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
