<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch images from database
$sql = "SELECT id, image_name, image_path FROM images";
$result = $conn->query($sql);

// Initialize an array to store images
$images = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

// Close database connection
$conn->close();

// JSON encode the images array
echo json_encode($images);
?>
