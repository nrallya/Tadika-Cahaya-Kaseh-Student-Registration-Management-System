<?php
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set your new Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51PUoT9AzjBDvh5ju5lII7XrcqcezBjxlYqB8mJ54DbkOrB3hCHMgDWzPOuhqJCmvHrB91m8dWb5ryBL00kIKiGSw00H5HG2dBY');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost';

// Retrieve childID from POST data
$childID = $_POST['childID'];
$customerEmail = 'customer@example.com'; // Replace with dynamic email if available

try {
    // Create a new customer
    $customer = \Stripe\Customer::create([
        'email' => $customerEmail,
    ]);

    // Retrieve the child's information (you need to implement this based on your database structure)
    $childDetails = retrieveChildDetails($childID); // Implement your own function to fetch child details

    // Create a checkout session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'mode' => 'payment',
        'invoice_creation' => ['enabled' => true],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => 'Registration Fee', // Replace with your product name
                        'description' => 'Registration Fee for ' . $childDetails['full_name'], // Example description
                    ],
                    'unit_amount' => 48000, // Replace with the actual amount in cents (e.g., RM50.00)
                ],
                'quantity' => 1,
            ],
        ],
        'success_url' => $YOUR_DOMAIN . '/TDK/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
        'customer' => $customer->id, // Use the created customer ID
        'metadata' => [
            'childID' => $childID, // Add childID to metadata
        ],
    ]);

    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Function to retrieve child details - replace this with your own database query logic
function retrieveChildDetails($childID)
{
    // Example implementation - replace with your actual database query
    include "connection.php"; // Include your database connection script

    $sql = "SELECT full_name FROM child WHERE childID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $childID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return null; // Handle case where childID is not found
    }
}
?>
