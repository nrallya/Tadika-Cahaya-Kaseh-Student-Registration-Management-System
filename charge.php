<?php
// Include Stripe PHP library and set your secret API key
require_once 'vendor/autoload.php'; // Adjust the path as needed
\Stripe\Stripe::setApiKey('sk_test_51PUoT9AzjBDvh5juTwsysoiGCmzHTK6iV4fRjcgm7IeLwhPe4hZWzXhS4O4ATS30GL41LNkV1GeFsmBKirCBSDbB001F5o78JX'); // Replace with your actual Stripe secret key

include "connection.php"; // Ensure this file contains your database connection settings

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the childID from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['childID']) && isset($_POST['stripeToken'])) {
    $childID = $_POST['childID'];
    $stripeToken = $_POST['stripeToken'];

    // Replace with actual payment amount logic if dynamic
    $amount = 48000; // Amount in cents (e.g., 480 MYR)

    try {
        // Create a charge: this will charge the user's card
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'myr',
            'description' => 'Payment for Child ID: ' . $childID,
            'source' => $stripeToken, // Token from the Stripe Checkout form
        ]);

        // Charge was successful, update the database
        $sql = "UPDATE child SET feePaid = 1 WHERE childID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $childID);
        $stmt->execute();

        // Redirect or display success message
        header("Location: success.php");
        exit;
    } catch (\Stripe\Exception\CardException $e) {
        // Card was declined, handle decline here
        $error = $e->getError()->message;
    } catch (\Stripe\Exception\RateLimitException $e) {
        // Too many requests made to the API too quickly
        $error = "Too many requests. Please try again later.";
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        // Invalid parameters were supplied to Stripe's API
        $error = "Invalid request. Please check your details and try again.";
    } catch (\Stripe\Exception\AuthenticationException $e) {
        // Authentication with Stripe's API failed
        $error = "Authentication failed. Please contact support.";
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        // Network communication with Stripe failed
        $error = "Network communication failed. Please try again.";
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Generic error occurred
        $error = "An error occurred. Please try again later.";
    }

    // Redirect back to payment page with error message if charge failed
    header("Location: payment.php?error=" . urlencode($error));
    exit;
} else {
    // Invalid request
    header("Location: payment.php");
    exit;
}
?>
