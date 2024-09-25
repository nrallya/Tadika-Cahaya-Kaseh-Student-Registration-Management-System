<?php
include "connection.php";

// Get the filter value from the GET request, default to 'all'
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Get the search term from the GET request, default to empty string
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// Base query to get all children and their payment status
$sql = "SELECT c.childID, c.full_name, c.FeePaid, p.amount, p.payment_date
        FROM child c
        LEFT JOIN payments p ON c.childID = p.childID";

// Apply filter to the query if needed
if ($filter === 'paid') {
    $sql .= " WHERE c.FeePaid = 1";
} elseif ($filter === 'unpaid') {
    $sql .= " WHERE c.FeePaid = 0";
}

// Apply search term to the query if needed
if (!empty($searchTerm)) {
    // If there's already a WHERE clause, use AND; otherwise, use WHERE
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND c.full_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
    } else {
        $sql .= " WHERE c.full_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
    }
}

$sql .= " ORDER BY c.childID ASC"; // Order by childID for consistency

$result = $conn->query($sql);

$payments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Fee Payment Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        header {
            background-color: #52B4B7;
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            font-size: 30px;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        button {
            padding: 12px 25px;
            margin: 20px;
            background: linear-gradient(135deg, #52B4B7, #549DB7);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            text-transform: uppercase;
            display: inline-block;
            text-align: center;
        }
        
        tbody tr {
            background-color: #f9f9f9;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }
        
        tbody tr.paid {
            background-color: #D4EDDA; /* Light green for paid */
        }

        tbody tr.unpaid {
            background-color: #F8D7DA; /* Light red for unpaid */
        }

        .status {
            text-transform: capitalize;
        }

        .center {
            text-align: center;
        }

        .empty {
            font-style: italic;
            text-align: center;
            color: #999;
        }

        .filter {
            margin-bottom: 20px;
            text-align: center;
        }

        .filter select, .filter input {
            padding: 10px;
            font-size: 16px;
            margin: 5px;
        }
    </style>
</head>
<body>

<header>
    <h1>Students Payment List</h1>
</header>

<div class="container">
    <div class="filter">
        <form action="" method="get">
            <label for="filter">Filter by:</label>
            <select name="filter" id="filter" onchange="this.form.submit()">
                <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All</option>
                <option value="paid" <?php echo $filter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="unpaid" <?php echo $filter === 'unpaid' ? 'selected' : ''; ?>>Unpaid</option>
            </select>
            <label for="searchTerm">Search by name:</label>
            <input type="text" name="searchTerm" id="searchTerm" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter child's name">
            <button type="submit">Search</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Child ID</th>
                <th>Child Name</th>
                <th>Status</th>
                <th>Amount (MYR)</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                    <tr class="<?php echo $payment['FeePaid'] ? 'paid' : 'unpaid'; ?>">
                        <td><?php echo $payment['childID']; ?></td>
                        <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                        <td class="status"><?php echo $payment['FeePaid'] ? 'Paid' : 'Unpaid'; ?></td>
                        <td><?php echo $payment['amount'] ? number_format($payment['amount'], 2) : 'N/A'; ?></td>
                        <td><?php echo $payment['payment_date'] ? date('M d, Y H:i:s', strtotime($payment['payment_date'])) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty">No payments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="adminmainpage.php">
        <button type="button">Back</button>
    </a>
</div>

</body>
</html>
