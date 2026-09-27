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

// Handle new contract submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $property_id = $_POST['property_id'];
    $tenant_id = $_POST['tenant_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO contracts (property_id, tenant_id, start_date, end_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $property_id, $tenant_id, $start_date, $end_date);
    $stmt->execute();
    $stmt->close();
}

// Fetch data for dropdowns
$properties = $conn->query("SELECT property_id, title FROM properties");
$tenants = $conn->query("SELECT tenant_id, name FROM tenants");

// Fetch existing contracts
$contracts = $conn->query("
    SELECT c.*, p.title AS property_title, t.name AS tenant_name
    FROM contracts c
    JOIN properties p ON c.property_id = p.property_id
    JOIN tenants t ON c.tenant_id = t.tenant_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contracts - Askari Rentals</title>
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

        .section {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        form select, form input[type="date"], form input[type="submit"] {
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
    </style>
</head>
<body>

<a class="back-link" href="index.php">&larr; Back to Dashboard</a>

<div class="section">
    <h2>Create New Contract</h2>
    <form method="post">
        <label>Select Property</label>
        <select name="property_id" required>
            <option value="">-- Choose Property --</option>
            <?php while($row = $properties->fetch_assoc()): ?>
                <option value="<?= $row['property_id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Select Tenant</label>
        <select name="tenant_id" required>
            <option value="">-- Choose Tenant --</option>
            <?php while($row = $tenants->fetch_assoc()): ?>
                <option value="<?= $row['tenant_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <input type="submit" value="Create Contract">
    </form>
</div>

<div class="section">
    <h2>Existing Contracts</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Property</th>
            <th>Tenant</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php while($row = $contracts->fetch_assoc()): ?>
        <tr>
            <td><?= $row['contract_id'] ?></td>
            <td><?= htmlspecialchars($row['property_title']) ?></td>
            <td><?= htmlspecialchars($row['tenant_name']) ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
