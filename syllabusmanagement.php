<?php
session_start();

// Database connection parameters
include "connection.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in staff's ID from session
$staffID = $_SESSION['staffID'];

// Fetch the class_id associated with the staff member
$stmt = $conn->prepare("SELECT class_id FROM staff WHERE staffID = ?");
$stmt->bind_param("i", $staffID);
$stmt->execute();
$stmt->bind_result($class_id);
$stmt->fetch();
$stmt->close();

// Fetch existing content items for the specific class grouped by category
$sql_content = "SELECT content_id, category, content FROM content_items WHERE class_id = ? ORDER BY category";
$stmt_content = $conn->prepare($sql_content);
$stmt_content->bind_param("i", $class_id);
$stmt_content->execute();
$result_content = $stmt_content->get_result();

// Handle form submissions for content management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add content
    if (isset($_POST['add_content'])) {
        $category = $_POST['category'];
        $content = $_POST['content'];
        
        // Prepare SQL statement for inserting content
        $stmt = $conn->prepare("INSERT INTO content_items (class_id, category, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $class_id, $category, $content);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Content added successfully.";
        } else {
            $_SESSION['error'] = "Error adding content: " . $stmt->error;
        }
        
        $stmt->close();
        header("Location: syllabusmanagement.php"); // Redirect to prevent form resubmission
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Syllabus</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 15px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-bottom: 20px;
        }

        .add-content-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .add-content-form h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #333;
        }

        .add-content-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .add-content-form input[type="text"], .add-content-form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .add-content-form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background-color 0.3s;
        }

        .add-content-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .existing-content {
            margin-top: 20px;
        }

        .category-heading {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }

        .category-list {
            list-style-type: none;
            padding: 0;
            margin-top: 10px;
        }

        .category-list li {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .category-list li .actions {
            margin-top: 5px;
        }

        .category-list li .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007BFF;
            font-size: 0.9rem;
        }

        .category-list li .actions a:hover {
            text-decoration: underline;
        }

        .message, .error {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .message {
            background-color: #28a745;
            color: #fff;
        }

        .error {
            background-color: #dc3545;
            color: #fff;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Syllabus for Your Class</h1>
    </header>
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="add-content-form">
            <h2>Add New Content</h2>
            <form action="syllabusmanagement.php" method="post">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required><br>
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="4" required></textarea><br>
                <input type="submit" name="add_content" value="Add Content">
            </form>
        </div>

        <div class="existing-content">
            <h2>Existing Content</h2>
            <?php
            $current_category = null;
            while ($row = $result_content->fetch_assoc()) {
                if ($row['category'] !== $current_category) {
                    // New category encountered, display category heading
                    $current_category = $row['category'];
                    echo "<h3 class='category-heading'>$current_category</h3>";
                    echo "<ul class='category-list'>";
                }
                // Display content item
                echo "<li>";
                echo htmlspecialchars($row['content']);
                echo "<div class='actions'>";
                echo "<a href='edit_content.php?id=" . $row['content_id'] . "'>Edit</a>";
                echo "<a href='delete_content.php?id=" . $row['content_id'] . "'>Delete</a>";
                echo "</div>";
                echo "</li>";
            }
            echo "</ul>";
            ?>
            <?php if ($result_content->num_rows === 0): ?>
                <p>No content items found.</p>
            <?php endif; ?>
        </div>

        <a href="staffmainpage.php" class="back-btn">&larr; Back</a>
    </div>
</body>
</html>
