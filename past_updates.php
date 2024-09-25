<?php
session_start();
include 'connection.php'; // Include your database connection

// Set the page title
$pageTitle = "Past Class Updates";

// Determine the class ID
$class_id = $_SESSION['class_id'] ?? 1; // Use session class_id or default to 1

// Get the current date
$today = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Tadika Cahaya Kaseh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        /* Include the same CSS styles as your main page for consistency */
        /* You can also extract common styles into a separate CSS file and include it here */

        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f1f8ff; /* Light blue background */
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background-color: #52B4B7; /* Blue header */
            color: white;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: #4AA8AB; /* Hover effect with lighter blue */
        }

        .section {
            padding: 50px 0;
            background-color: #f1f8ff; /* Light blue background */
            text-align: center;
        }

        .section h2 {
            font-size: 28px;
            color: #1a75ff; /* Dark blue text */
            margin-bottom: 15px;
        }

        .section p {
            font-size: 16px;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }

        .btn {
            background-color: #52B4B7; /* Blue button */
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 18px;
            display: inline-block;
            align-items: center;
        }

        .btn:hover {
            background-color: #3399ff; /* Lighter blue on hover */
        }

        .btn i {
            margin-right: 10px;
        }

        footer {
            background-color: #52B4B7; /* Dark blue footer */
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .btn {
                font-size: 16px;
                padding: 10px 20px;
            }
        }

        /* Additional Styles for Past Updates */
        .update-list {
            max-width: 800px;
            margin: 0 auto;
            text-align: left;
        }

        .update-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .update-date {
            font-weight: bold;
            color: #1a75ff;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo $pageTitle; ?></h1>
            <nav>
                <ul>
                    <li><a href="profileparent.php">Profile Settings</a></li>
                    <li><a href="registeredchild.php">Registered Child</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container sections">
        <div id="past-updates" class="section">
            <h2>Past Class Updates</h2>
            <?php
            // Fetch past updates from the database
            // Exclude today's update if desired
            $stmt_past_updates = $conn->prepare("SELECT date, update_text FROM daily_update WHERE class_id = ? AND date < ? ORDER BY date DESC");
            if ($stmt_past_updates === false) {
                echo "<p>Error preparing the statement: " . htmlspecialchars($conn->error) . "</p>";
            } else {
                $stmt_past_updates->bind_param("is", $class_id, $today);

                if ($stmt_past_updates->execute()) {
                    $stmt_past_updates->bind_result($update_date, $update_text);

                    // Check if there are any past updates
                    $has_updates = false;
                    echo '<div class="update-list">';
                    while ($stmt_past_updates->fetch()) {
                        $has_updates = true;
                        echo '<div class="update-item">';
                        echo '<div class="update-date">' . date("F j, Y", strtotime($update_date)) . '</div>';
                        echo '<div class="update-content">' . htmlspecialchars($update_text) . '</div>';
                        echo '</div>';
                    }
                    echo '</div>';

                    if (!$has_updates) {
                        echo "<p>No past updates available.</p>";
                    }
                } else {
                    echo "<p>Error executing query: " . htmlspecialchars($stmt_past_updates->error) . "</p>";
                }

                $stmt_past_updates->close();
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Tadika Cahaya Kaseh. All rights reserved.</p>
    </footer>
</body>
</html>
