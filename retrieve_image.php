<?php
// Example of retrieving and displaying images

// Database connection (assuming it's already established)
$conn = mysqli_connect('localhost', 'root', '', 'your_database');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch images from database
$sql = "SELECT id, image_name, image_data, image_type FROM images";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching images: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Images</title>
    <style>
        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .image-item {
            text-align: center;
        }
        .image-item img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="image-container">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $imageData = $row['image_data'];
            $imageName = $row['image_name'];
            $imageType = $row['image_type'];

            // Construct the data URL with Base64 encoding
            $base64Image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);

            ?>
            <div class="image-item">
                <img src="<?php echo $base64Image; ?>" alt="<?php echo htmlspecialchars($imageName); ?>">
                <p><?php echo htmlspecialchars($imageName); ?></p>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
