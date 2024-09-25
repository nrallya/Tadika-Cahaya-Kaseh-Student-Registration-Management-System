<?php
session_start();
if (!isset($_SESSION['staffID'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content_id = $_POST['content_id'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE content_items SET category = ?, content = ? WHERE content_id = ?");
    $stmt->bind_param("ssi", $category, $content, $content_id);

    if ($stmt->execute()) {
        header("Location: syllabusmanagement.php");
        exit(); // Ensure no further output after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Fetch content_id from $_GET['id'] if it exists
    $content_id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($content_id !== null) {
        $stmt = $conn->prepare("SELECT * FROM content_items WHERE content_id = ?");
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "Content ID is not specified.";
        exit(); // Or handle this case according to your application's logic
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical; /* Allow vertical resizing of textarea */
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: inline-block;
            color: #007BFF;
            text-decoration: none;
            margin-top: 10px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Content</h2>

        <?php if ($row): ?>
            <form action="edit_content.php" method="post">
                <input type="hidden" name="content_id" value="<?php echo $row['content_id']; ?>">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required><br>
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required><?php echo htmlspecialchars($row['content']); ?></textarea><br>
                <button type="submit">Update Content</button>
            </form>
            <a class="back-link" href="syllabusmanagement.php">&larr; Back to Syllabus Management</a>
        <?php else: ?>
            <p>No content found for the specified ID.</p>
            <a class="back-link" href="syllabusmanagement.php">&larr; Back to Syllabus Management</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
    $conn->close();
}
?>
