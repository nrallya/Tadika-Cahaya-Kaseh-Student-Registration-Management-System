<?php
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

header('Content-Type: text/html; charset=UTF-8');

if (!isset($_GET['session_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'session_id not provided']);
    exit();
}

$session_id = $_GET['session_id'];

try {
    // Retrieve the Stripe Checkout session
    $session = $stripe->checkout->sessions->retrieve($session_id);
    $customer_id = $session->customer;
    $childID = $session->metadata->childID; // Access childID from session metadata

    if ($customer_id && $childID) {
        include "connection.php"; // Ensure this file contains your database connection settings

        // Update the database to mark the fee as paid
        $sql = "UPDATE child SET FeePaid = 1 WHERE childID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $childID);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Use the provided receipt link
        $receiptLink = 'https://invoice.stripe.com/i/acct_1PUoT9AzjBDvh5ju/test_YWNjdF8xUFVvVDlBempCRHZoNWp1LF9RbWZ4UUFaUUt4RjRoNENIM0x4NjJ0bjRFMDNYSENVLDExNTk0OTEyNQ0200ZonO4P5c?s=db';

        // Display thank you message with download link
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Thank You</title>
        </head>
        <body>
            <h1>Thank you for your payment!</h1>
            <p>You can download your receipt <a href='$receiptLink' target='_blank'>here</a>.</p>
            <p>You will be redirected shortly...</p>
            <script>
                setTimeout(function() {
                    window.location.href = 'registeredchild.php?customer_id={$customer_id}&child_id={$childID}';
                }, 6000); // Redirect after 6 seconds
            </script>
        </body>
        </html>";
    } else {
        throw new Exception("Customer or Child ID not found for session ID: $session_id");
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
