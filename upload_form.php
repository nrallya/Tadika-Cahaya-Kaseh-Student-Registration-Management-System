<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Image</title>
</head>
<body>
    <h2>Upload Image</h2>
    <form action="upload_image.php" method="post" enctype="multipart/form-data">
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image">
        <button type="submit" name="submit">Upload Image</button>
    </form>
</body>
</html>
