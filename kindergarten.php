<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch images from database
$sql = "SELECT id, image_name, image_data, image_type, image_desc FROM images";
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
    <title>Event Gallery | Tadika Cahaya Kaseh</title>
    <style>
        /* Reset margins and paddings */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .app {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure app takes at least full viewport height */
            overflow-x: hidden; /* Hide horizontal overflow */
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .actions {
            display: flex;
            align-items: center;
        }

        .actions button {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #fff;
            color: #52B4B7;
            border: 2px solid #52B4B7;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
        }

        .actions button:hover {
            background-color: #52B4B7;
            color: white;
        }
        
        main {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
            justify-items: center;
            overflow-y: auto; /* Enable vertical scrolling */
            flex-grow: 1; /* Allow main content to grow */
        }

        h1 {
            font-size: 24px;
            font-weight: normal;
            margin-bottom: 20px;
        }

        .image-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .image-item:hover {
            transform: translateY(-5px);
        }

        .image-item img {
            width: 100%;
            height: 250px; /* Fixed height for images */
            object-fit: cover; /* Ensure images cover the container while maintaining aspect ratio */
        }

        .image-item p {
            padding: 10px;
            margin: 0;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="app">
        <header>
            <h1>Event Gallery | Tadika Cahaya Kaseh</h1>
            <div class="actions">
                <button id="back-btn">Back</button>
            </div>
        </header>
        <main>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $imageData = $row['image_data'];
                $imageName = $row['image_name'];
                $imageType = $row['image_type'];
                $imagedesc = $row["image_desc"];

                // Construct the data URL with Base64 encoding
                $base64Image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);

                // Check if base64 encoding was successful
                if (!$base64Image) {
                    echo "Error encoding image: " . mysqli_error($conn);
                    continue; // Skip this image and proceed to the next
                }
            ?>
            <div class="image-item">
                <img src="<?php echo $base64Image; ?>" alt="<?php echo htmlspecialchars($imageName); ?>">
                <p><?php echo htmlspecialchars($imagedesc); ?></p>
            </div>
            <?php
            }
            ?>
        </main>
    </div>
    <script>
        // Event listener for back button
        document.getElementById('back-btn').addEventListener('click', function() {
            window.location.href = 'mainpage.php';
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
