<?php
session_start();

// Database connection
include "connection.php";  // Adjust the path as per your actual setup

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $imageName = $_FILES["image"]["name"];
    $imageTmpName = $_FILES["image"]["tmp_name"];
    $imageType = $_FILES["image"]["type"];
    $imageDesc = isset($_POST['image_desc']) ? $_POST['image_desc'] : '';

    // Validate uploaded file
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($imageType, $allowedTypes)) {
        die("Error: Only JPG or PNG files are allowed.");
    }

    // Read image data
    $imageData = file_get_contents($imageTmpName);

    // Insert image data into database
    $sql = "INSERT INTO images (image_name, image_data, image_type, image_desc) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $imageName, $imageData, $imageType, $imageDesc);
        mysqli_stmt_execute($stmt);

        // Set session variable for success message
        $_SESSION['image_inserted'] = true;

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error inserting image: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        .form-group input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Insert Image</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Select Image:</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png" required>
            </div>
            <div class="form-group">
                <label for="image_desc">Image Description:</label>
                <input type="text" id="image_desc" name="image_desc" placeholder="Enter image description">
            </div>
            <div class="form-group">
                <button type="submit">Upload Image</button>
            </div>
        </form>
    </div>

    <?php
    // Check if session variable is set and show popup message
    if (isset($_SESSION['image_inserted']) && $_SESSION['image_inserted']) {
        echo '<script>alert("Image inserted successfully!");</script>';
        unset($_SESSION['image_inserted']); // Unset session variable
    }
    ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="album.php">Back</a>
    </div>
</body>
</html>
