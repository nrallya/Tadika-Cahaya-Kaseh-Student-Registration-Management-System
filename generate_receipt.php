<?php
// Include connection to your database
include "connection.php";

// Check if payment ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $payment_id = $_GET['id'];
    // Prepare SQL statement with a placeholder for the payment ID
    $sql = "SELECT p.id, p.childID, p.amount, p.payment_date, c.full_name, c.parent_name, c.email
            FROM payments p
            LEFT JOIN child c ON p.childID = c.childID
            WHERE p.id = ?";
    
    // Prepare and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Format the payment date
        $payment_date = date('Y-m-d', strtotime($row['payment_date']));

        // Output the receipt
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Payment Receipt</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .receipt {
                    border: 1px solid #ccc;
                    padding: 20px;
                    width: 300px;
                    margin: 0 auto;
                }
                .receipt h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .receipt .info {
                    margin-bottom: 10px;
                }
                .receipt .info span {
                    font-weight: bold;
                }
                .receipt .footer {
                    margin-top: 20px;
                    text-align: center;
                    <?php
// Include connection to your database
include "connection.php";

// Check if payment ID is provided and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $payment_id = $_GET['id'];

    // Prepare SQL statement with parameterized query
    $sql = "SELECT p.id, p.childID, p.amount, p.payment_date, c.full_name, c.parent_name, c.email
            FROM payments p
            LEFT JOIN child c ON p.childID = c.childID
            WHERE p.id = ?";
    
    // Prepare and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Format the payment date
        $payment_date = date('Y-m-d', strtotime($row['payment_date']));

        // Output the receipt
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Payment Receipt</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .receipt {
                    border: 1px solid #ccc;
                    padding: 20px;
                    width: 300px;
                    margin: 0 auto;
                }
                .receipt h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .receipt .info {
                    margin-bottom: 10px;
                }
                .receipt .info span {
                    font-weight: bold;
                }
                .receipt .footer {
                    margin-top: 20px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <h2>Payment Receipt</h2>
                <div class="info">
                    <span>Payment ID:</span> <?php echo $row['id']; ?><br>
                    <span>Child Name:</span> <?php echo htmlspecialchars($row['full_name']); ?><br>
                    <span>Parent Name:</span> <?php echo htmlspecialchars($row['parent_name']); ?><br>
                    <span>Email:</span> <?php echo htmlspecialchars($row['email']); ?><br>
                    <span>Payment Date:</span> <?php echo $payment_date; ?><br>
                    <span>Amount Paid:</span> <?php echo $row['amount']; ?> MYR<br>
                </div>
                <div class="footer">
                    <p>Thank you for your payment.</p>
                </div>
            </div>
        </body>
        </html>
        <?php

    } else {
        echo "No payment found with ID: $payment_id";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid payment ID provided.";
}
?>

                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <h2>Payment Receipt</h2>
                <div class="info">
                    <span>Payment ID:</span> <?php echo "Payment ID: " . $payment_id; // Debugging output; ?><br>
                    <span>Child Name:</span> <?php echo htmlspecialchars($row['full_name']); ?><br>
                    <span>Parent Name:</span> <?php echo htmlspecialchars($row['parent_name']); ?><br>
                    <span>Email:</span> <?php echo htmlspecialchars($row['email']); ?><br>
                    <span>Payment Date:</span> <?php echo $payment_date; ?><br>
                    <span>Amount Paid:</span> <?php echo $row['amount']; ?> MYR<br>
                </div>
                <div class="footer">
                    <p>Thank you for your payment.</p>
                </div>
            </div>
        </body>
        </html>
        <?php

    } else {
        echo "No payment found with ID: $payment_id";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid payment ID provided.";
}
?>
