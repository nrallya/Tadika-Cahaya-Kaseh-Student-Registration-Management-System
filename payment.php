<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect the user to the login page or display a message
    header("Location: login.php"); // Adjust the URL to your login page
    exit();
}

include "connection.php"; // Ensure this file contains your database connection settings

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include Stripe PHP library and set your secret API key
require_once 'vendor/autoload.php'; // Adjust the path as needed
\Stripe\Stripe::setApiKey('sk_test_51PUoT9AzjBDvh5ju5lII7XrcqcezBjxlYqB8mJ54DbkOrB3hCHMgDWzPOuhqJCmvHrB91m8dWb5ryBL00kIKiGSw00H5HG2dBY'); // Replace with your actual Stripe secret key
?>
