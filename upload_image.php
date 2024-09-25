<?php
// Database connection
include "connection.php"; // Adjust as per your database connection script

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $image_name = $_FILES["image"]["name"];
    $image_data = file_get_contents($_FILES["image"]["tmp_name"]);
    $image_type = $_FILES["image"]["type"];

    // Prepare SQL statement
    $sql = "INSERT INTO images (image_name, image_data, image_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sbs", $image_name, $image_data, $image_type);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Image uploaded successfully.";
    } else {
        echo "Error uploading image: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
