<?php
session_start();

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Syllabus";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f7f7f7;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            color: #52B4B7;
            margin-bottom: 20px;
        }

        .syllabus-section {
            margin-bottom: 20px;
        }

        .syllabus-section h2 {
            font-size: 24px;
            color: #52B4B7;
            margin-bottom: 10px;
        }

        .syllabus-section p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .syllabus-list {
            list-style-type: none;
            padding: 0;
        }

        .syllabus-list li {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $pageTitle; ?></h1>
        
        <!-- 4 Years Old Syllabus Section -->
        <div class="syllabus-section">
            <h2>4 Years Old</h2>
            <ul class="syllabus-list">
                <li>Introduction to colors and shapes</li>
                <li>Basic counting and number recognition</li>
                <li>Storytelling and rhymes</li>
                <li>Interactive play and group activities</li>
            </ul>
        </div>

        <!-- 5 Years Old Syllabus Section -->
        <div class="syllabus-section">
            <h2>5 Years Old</h2>
            <ul class="syllabus-list">
                <li>Advanced counting and basic arithmetic</li>
                <li>Letter recognition and phonics</li>
                <li>Art and craft activities</li>
                <li>Introduction to basic science concepts</li>
            </ul>
        </div>

        <!-- 6 Years Old Syllabus Section -->
        <div class="syllabus-section">
            <h2>6 Years Old</h2>
            <ul class="syllabus-list">
                <li>Reading and simple sentence formation</li>
                <li>Basic addition and subtraction</li>
                <li>Exploration of nature and environment</li>
                <li>Creative writing and storytelling</li>
            </ul>
        </div>
    </div>
</body>
</html>
