<?php
// send_message.php

header('Content-Type: application/json');

// Get the message from the request
$request = json_decode(file_get_contents('php://input'), true);
$message = $request['message'];

// Simulate staff response (you can replace this with database operations or AI-driven responses)
$response = "Thank you for your message. please directly WhatsApp to this number for any inquiry 0124142213.";

// Send the response back
echo json_encode(['reply' => $response]);
