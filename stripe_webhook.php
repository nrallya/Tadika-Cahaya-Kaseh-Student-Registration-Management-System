<?php
require 'vendor/autoload.php'; // Make sure you have Stripe's PHP library installed via Composer
include "connection.php"; // Ensure this file contains your database connection settings

// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/apikeys
\Stripe\Stripe::setApiKey('sk_test_51PUoT9AzjBDvh5jueJJCWKrJZ23XAskI9E62MzkzJsxylnPnSbQL2JPEPdaNHBMdLtk0Bj1GucSDAa6yIwGwqHie009iv3BBRG');

// You can find your endpoint's secret in your webhook settings
$endpoint_secret = 'whsec_YourEndpointSecretHere';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;

        // Fulfill the purchase...
        handleCheckoutSession($session);
        break;
    // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);

function handleCheckoutSession($session) {
    global $conn;

    $childID = $session->metadata->childID;

    $sql = "UPDATE child SET FeePaid = 1 WHERE childID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $childID);
    $stmt->execute();
    $stmt->close();
}
?>
