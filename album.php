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

// Delete image if delete request is sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_image'])) {
    $imageId = $_POST['image_id'];
    $sql_delete = "DELETE FROM images WHERE id = $imageId";

    if (mysqli_query($conn, $sql_delete)) {
        echo "Image deleted successfully";
    } else {
        echo "Error deleting image: " . mysqli_error($conn);
    }
    exit; // Stop further execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album | Tadika Cahaya Kaseh</title>
    <style>
        /* Reset margins and paddings */
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .app {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure app takes at least full viewport height */
            position: relative; /* Needed for absolute positioning of delete buttons */
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 10px 20px;
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
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
        }

        .actions button:hover {
            background-color: #0056b3;
        }

        main {
            flex: 1;
            background: #f4f4f9;
            padding: 20px;
            overflow-y: auto; /* Enable vertical scrolling */
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            align-items: flex-start;
        }

        .image-item {
            position: relative;
            max-width: 300px; /* Limit image item width */
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensure images do not overflow */
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .image-item:hover {
            transform: translateY(-5px);
        }

        .image-item img {
            width: 100%;
            height: 200px; /* Fixed height for images */
            object-fit: cover; /* Maintain aspect ratio and cover the container */
            border-bottom: 1px solid #ddd; /* Optional: Add border between image and title */
        }

        .image-item p {
            padding: 10px;
            margin: 0;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
        }

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: rgba(255, 0, 0, 1);
        }

        .actions-bottom {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            position: sticky;
            bottom: 0;
            background-color: white;
            padding: 10px 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .actions-bottom a {
            display: block;
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .actions-bottom a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="app">
        <header>
            <h1>Album | Tadika Cahaya Kaseh</h1>
            <div class="actions">
            </div>
        </header>
        <main>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $imageId = $row['id'];
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
                <button class="delete-button" onclick="deleteImage(<?php echo $imageId; ?>)">X</button>
                <img src="<?php echo $base64Image; ?>" alt="<?php echo htmlspecialchars($imageName); ?>">
                <p><?php echo htmlspecialchars($imagedesc); ?></p>
            </div>
            <?php
            }
            ?>
        </main>
        <div class="actions-bottom">
            <a href="staffmainpage.php">Back</a>
            <a href="insert_image.php">Edit</a>
        </div>
    </div>
    <script>
        // Function to view image details (replace with your specific implementation)
        function viewImage(base64Image, imageName, imageDesc) {
            alert(`Image Name: ${imageName}\nDescription: ${imageDesc}`);
            // Implement your image view logic here (e.g., lightbox, modal)
        }

        // Function to delete image via AJAX
        function deleteImage(imageId) {
            if (confirm("Are you sure you want to delete this image?")) {
                // AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "album.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert(xhr.responseText); // Display response from server
                        // Optional: Remove the deleted image from the UI
                    }
                };
                xhr.send("delete_image=true&image_id=" + imageId);
            }
        }
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
