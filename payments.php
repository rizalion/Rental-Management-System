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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contract_id = $_POST["contract_id"];
    $amount = $_POST["amount"];
    $payment_date = $_POST["payment_date"];
    $payment_method = $_POST["payment_method"];

    $stmt = $conn->prepare("INSERT INTO payments (contract_id, amount_paid, payment_date, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $contract_id, $amount, $payment_date, $payment_method);
    $stmt->execute();
    $stmt->close();
}

// Get all contracts (to show in dropdown)
$contracts = $conn->query("SELECT c.contract_id, t.name AS tenant_name, p.title AS property_title
                           FROM contracts c
                           JOIN tenants t ON c.tenant_id = t.tenant_id
                           JOIN properties p ON c.property_id = p.property_id");

// Get all payments
$payments = $conn->query("SELECT p.*, t.name AS tenant_name, prop.title AS property_title
                          FROM payments p
                          JOIN contracts c ON p.contract_id = c.contract_id
                          JOIN tenants t ON c.tenant_id = t.tenant_id
                          JOIN properties prop ON c.property_id = prop.property_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payments - Askari Rentals</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f7fc;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1b72e8;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        form input, form select {
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
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #125ab6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
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
    </style>
</head>
<body>

    <a class="back-link" href="index.php">&larr; Back to Dashboard</a>

    <div class="container">
        <h2>Add Payment</h2>
        <form method="post">
            <select name="contract_id" required>
                <option value="">-- Select Contract (Tenant / Property) --</option>
                <?php while ($row = $contracts->fetch_assoc()): ?>
                    <option value="<?= $row['contract_id'] ?>">
                        <?= htmlspecialchars($row['tenant_name']) ?> - <?= htmlspecialchars($row['property_title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="amount" step="0.01" placeholder="Payment Amount" required>
            <input type="text" name="payment_method" placeholder="Payment Method (e.g., Cash, Bank Transfer)" required>
            <input type="date" name="payment_date" required>
            <input type="submit" value="Add Payment">
        </form>

        <h2>All Payments</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tenant</th>
                <th>Property</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $payments->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["payment_id"] ?></td>
                    <td><?= htmlspecialchars($row["tenant_name"]) ?></td>
                    <td><?= htmlspecialchars($row["property_title"]) ?></td>
                    <td>$<?= number_format($row["amount_paid"], 2) ?></td>
                    <td><?= htmlspecialchars($row["payment_method"]) ?></td>
                    <td><?= $row["payment_date"] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
