<?php
// Include database connection
include "connection.php";

// SQL query to fetch payment data
$sql = "SELECT c.childID, c.full_name, p.amount, p.payment_date
        FROM child c
        LEFT JOIN payments p ON c.childID = p.childID
        WHERE c.FeePaid = 1
        ORDER BY p.payment_date DESC";

// Perform the query
$result = $conn->query($sql);

// Initialize an empty array to store payments
$payments = array();

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while ($row = $result->fetch_assoc()) {
        // Add each row (payment data) to the payments array
        $payments[] = $row;
    }
}

// Close the database connection
$conn->close();

// Send JSON response with payments array
header('Content-Type: application/json');
echo json_encode($payments);
?>
