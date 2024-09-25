<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>4 Year Old Activities</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
            min-height: 100vh;
            overflow-y: auto;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 800px;
            margin: 40px 0;
            text-align: left;
        }

        h1 {
            color: #333;
            font-size: 40px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #549DB7;
            padding-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }

        .syllabus h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .syllabus p {
            margin-left: 20px;
            color: #555;
        }

        button {
            padding: 12px 25px;
            margin-top: 20px;
            background: linear-gradient(135deg, #52B4B7, #549DB7);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
            font-size: 16px;
            text-transform: uppercase;
            display: inline-block;
        }

        button:hover {
            background: linear-gradient(135deg, #549DB7, #52B4B7);
            transform: translateY(-2px);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-container a {
            text-decoration: none;
            margin: 0 10px;
        }

        .fees {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .fees h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
            text-transform: uppercase;
        }

        .fees ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fees li {
            margin-bottom: 10px;
            font-size: 18px;
            color: #555;
        }

        .fees li span:first-child {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Activities for 4-Year-Olds</h1>
        
        <?php
        include "connection.php"; // Corrected to the connection file name

        // Query to fetch content items for Foundation (class_id = 1)
        $sql_content = "SELECT category, content FROM content_items WHERE class_id = 1";
        $result_content = $conn->query($sql_content);

        // Group content by category
        $content_by_category = [];
        if ($result_content->num_rows > 0) {
            while ($row = $result_content->fetch_assoc()) {
                $category = htmlspecialchars($row['category']);
                $content = htmlspecialchars($row['content']);
                if (!isset($content_by_category[$category])) {
                    $content_by_category[$category] = [];
                }
                $content_by_category[$category][] = $content;
            }
        } else {
            echo "No content items found.";
        }
        
        // Display grouped content
        foreach ($content_by_category as $category => $contents) {
            echo "<h2>" . $category . "</h2>";
            echo "<p>" . implode(", ", $contents) . "</p>";
        }
        
        // Query to fetch fees for Foundation (class_id = 1)
        $sql_fees = "SELECT fee_type, amount FROM fees WHERE class_id = 1";
        $result_fees = $conn->query($sql_fees);
        
        // Display fees
        echo "<h2>Fees</h2>";
        echo "<ul>";
        if ($result_fees->num_rows > 0) {
            while ($row = $result_fees->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['fee_type']) . ": RM" . number_format($row['amount'], 2) . "</li>";
            }
        } else {
            echo "<li>No fees found.</li>";
        }
        echo "</ul>";
        
        // Close connection
        $conn->close();
        ?>
        
        <div class="btn-container">
            <a href="register.php">
                <button type="button">Register</button>
            </a>
            <a href="mainpage.php">
                <button type="button">Back</button>
            </a>
        </div>
    </div>
</body>
</html>
