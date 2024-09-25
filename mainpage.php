<?php
session_start();
include 'connection.php'; // Include your database connection

$pageTitle = "Mainpage";
// Handle notification sending
$notification_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notify_parents'])) {
    $message = "Your child is ready for pickup!";
    $notification_type = "pickup"; // You can define different types if needed

    // Insert notification into the database
    $stmt = $conn->prepare("INSERT INTO notifications (message, notification_type, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $message, $notification_type);

    if ($stmt->execute()) {
        $notification_sent = true; // Flag that the notification was sent
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>

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

        .hero {
            position: relative;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #fff;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.7); /* Stronger shadow for better visibility */
        }

        .hero p {
            font-size: 24px;
            color: #fff;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7); /* Increase the shadow effect */
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

        .sections {
            padding: 50px 0;
            background-color: #f1f8ff; /* Light blue background */
        }

        .section {
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

            .hero {
                height: 300px;
            }

            .hero img {
                height: 300px;
            }

            .hero h1 {
                font-size: 32px;
                font-weight: bold;
                margin-bottom: 10px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            }

            .hero p {
                font-size: 18px;
            }

            .btn {
                font-size: 16px;
                padding: 10px 20px;
            }
        }

        /* Chatbox Styles */
        #chatIcon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #52B4B7;
            color: white;
            padding: 15px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
        }

        #chatIcon:hover {
            background-color: #3399ff;
        }

        .chatbox {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }

        .chatbox-header {
            background-color: #52B4B7;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbox-body {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
            flex: 1;
        }

        .chatbox-footer {
            display: flex;
            align-items: center;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .chatbox-footer input[type="text"] {
            width: 80%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .chatbox-footer button {
            background-color: #52B4B7;
            color: white;
            border: none;
            padding: 8px 10px;
            margin-left: 5px;
            cursor: pointer;
            border-radius: 4px;
        }

        .chatbox-footer button:hover {
            background-color: #3399ff;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 8px;
            background-color: #52B4B7;
            color: white;
            max-width: 70%;
        }

        .chat-message.staff {
            background-color: #ddd;
            color: #333;
            margin-left: auto;
        }

        .chat-message.user {
            background-color: #52B4B7;
            color: white;
            margin-left: auto;
        }

        /* Notification Pop-Up Styles */
        #notificationPopup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 2px solid #52B4B7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            display: none; /* Initially hidden */
            width: 300px;
            text-align: center;
        }

        #notificationPopup h3 {
            margin-bottom: 15px;
            color: #333;
        }

        #notificationPopup button {
            background-color: #52B4B7;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #notificationPopup button:hover {
            background-color: #3399ff;
        }

        /* Overlay Styles */
        #popupOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1500;
            display: none; /* Initially hidden */
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
                    <li><a href="register.php" class="btn-register"><i class="fas fa-user-plus"></i> Register Child</a></li>
                    <li><a href="login.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="hero">
        <img src="gallery.jpg" alt="Welcome Image">
        <div class="hero-content">
            <h1>Welcome to Tadika Cahaya Kaseh</h1>
            <p>Explore our programs and facilities</p>
        </div>
    </div>
    

    <section id="class-updates" class="section">
        <h2>Today's Class Updates</h2>
        <?php
        // Check if session class_id is set
        if (isset($_SESSION['class_id'])) {
            $class_id = $_SESSION['class_id']; // Get class_id from the session
        } else {
            // If class_id is not set, set it to a default value (e.g., 1)
            $class_id = 1; 
        }

        $today = date("Y-m-d");

        // Debugging: print class ID and today's date
        // Remove these after testing

        echo "<p>Date: $today</p>";

        // Ensure the class_id is defined and execute the query
        if (isset($class_id)) {
            $stmt_updates = $conn->prepare("SELECT update_text FROM daily_update WHERE class_id = ? AND date = ?");
            if ($stmt_updates === false) {
                echo "Error preparing the statement: " . $conn->error;
            } else {
                $stmt_updates->bind_param("is", $class_id, $today);

                if ($stmt_updates->execute()) {
                    $stmt_updates->bind_result($update_text);

                    if ($stmt_updates->fetch()) {
                        echo "<p>$update_text</p>";
                    } else {
                        echo "<p>No updates for today.</p>";
                    }
                } else {
                    echo "Error executing query: " . $stmt_updates->error;
                }

                $stmt_updates->close();
            }
        } else {
            echo "<p>Class ID not found. Please try again later.</p>";
        }
        ?>
    </section>

    <section id="notifications" class="section" style="display: none;">
    <h2>Notifications</h2>
    <?php
    // Fetch the latest notification
    $stmt = $conn->prepare("SELECT message, created_at FROM notifications ORDER BY created_at DESC LIMIT 1");
    if ($stmt->execute()) {
        $stmt->bind_result($message, $created_at);
        if ($stmt->fetch()) {
            echo "<p><strong>New Notification:</strong> $message</p>";
        } else {
            echo "<p>No new notifications.</p>";
        }
    } else {
        echo "Error fetching notifications: " . $stmt->error;
    }
    ?>
</section>
</section>


    <div class="container sections">
        <div id="programs" class="section">
            <h2>Our Programs</h2>
            <p>Discover the curriculum and activities tailored for each age group.</p>
            <a href="syllabus.php" class="btn"><i class="fas fa-book"></i> View Syllabus</a>
        </div>

        <div class="section">
            <h2>Kindergarten Gallery</h2>
            <p>Explore photos and highlights from our kindergarten activities and events.</p>
            <a href="kindergarten.php" class="btn"><i class="fas fa-camera"></i> View Gallery</a>
        </div>

        <div class="section">
            <h2>Notice to Parents</h2>
            <p>Please visit the kindergarten during office hours (Monday to Friday, 8:00 a.m - 10:00 a.m) after registering and paying the fees.</p>
            <p>A notification sound will sounded when your child is ready to be pick up at the kindergarden.</p>

        </div>
    </div>

    <!-- Chatbox UI -->
    <div class="chatbox">
        <div class="chatbox-header">
            <h4>Chat with Us</h4>
            <button id="closeChatbox">&times;</button>
        </div>
        <div class="chatbox-body" id="chatboxBody">
            <!-- Messages will be loaded here -->
        </div>
        <div class="chatbox-footer">
            <input type="text" id="chatMessage" placeholder="Type your message...">
            <button id="sendChat"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <!-- Chatbox Icon -->
    <div id="chatIcon">
        <i class="fas fa-comments"></i>
    </div>

  <!-- Notification Pop-Up -->
<div id="popupOverlay" style="display:none;"></div>
<div id="notificationPopup" style="display:none;">
    <h3>Have you taken your child?</h3>
    <button id="popupYes">Yes</button>
    <button id="popupNo">No</button>
</div>


    <footer>
        <p>&copy; <?php echo date("Y"); ?> Tadika Cahaya Kaseh. All rights reserved.</p>
    </footer>

    <!-- JavaScript Section -->
    <script>
        // Chatbox Toggle
        document.getElementById('chatIcon').addEventListener('click', function() {
            document.querySelector('.chatbox').style.display = 'flex';
        });

        document.getElementById('closeChatbox').addEventListener('click', function() {
            document.querySelector('.chatbox').style.display = 'none';
        });

        // Send message
        document.getElementById('sendChat').addEventListener('click', function() {
            let message = document.getElementById('chatMessage').value;

            if (message.trim() !== '') {
                // Add message to chatbox
                let chatboxBody = document.getElementById('chatboxBody');
                let userMessage = document.createElement('div');
                userMessage.className = 'chat-message user';
                userMessage.textContent = message;
                chatboxBody.appendChild(userMessage);

                // Scroll to bottom
                chatboxBody.scrollTop = chatboxBody.scrollHeight;

                // Send message to server
                sendMessageToServer(message);

                // Clear the input
                document.getElementById('chatMessage').value = '';
            }
        });

        // Handle message sending to server
        function sendMessageToServer(message) {
            // This is where you would send the message to your server-side script via AJAX
            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({message: message})
            })
            .then(response => response.json())
            .then(data => {
                // Handle server response
                if (data.reply) {
                    let chatboxBody = document.getElementById('chatboxBody');
                    let staffMessage = document.createElement('div');
                    staffMessage.className = 'chat-message staff';
                    staffMessage.textContent = data.reply;
                    chatboxBody.appendChild(staffMessage);

                    // Scroll to bottom
                    chatboxBody.scrollTop = chatboxBody.scrollHeight;
                }
            })
            .catch(error => console.error('Error:', error));
        }

    </script>
            
    <script>
        if (notificationSent) {
                // Play the notification sound
                var audio = document.getElementById('notification-sound');
                if (audio) {
                    audio.play();
                }

                // Show a popup message
                var overlay = document.getElementById('popup-overlay');
                var closeButton = document.getElementById('popup-close');

                if (overlay) {
                    overlay.style.display = 'block';
                }

                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        if (overlay) {
                            overlay.style.display = 'none';
                        }
                    });
                }
            }
    // Function to show the notification pop-up
    function showNotificationPopup() {
        document.getElementById('popupOverlay').style.display = 'block';
        document.getElementById('notificationPopup').style.display = 'block';
    }

    // Function to hide the notification pop-up
    function hideNotificationPopup() {
        document.getElementById('popupOverlay').style.display = 'none';
        document.getElementById('notificationPopup').style.display = 'none';
    }

    document.getElementById('notifyButton').addEventListener('click', function() {
        // Send a request to the server to trigger the notification
        fetch('trigger_notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'notify' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotificationPopup();
            } else {
                console.error('Failed to trigger notification');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Close popup when 'Yes' or 'No' is clicked
    document.getElementById('popupYes').addEventListener('click', function() {
        hideNotificationPopup();
        // Additional functionality for 'Yes'
    });

    document.getElementById('popupNo').addEventListener('click', function() {
        hideNotificationPopup();
        // Additional functionality for 'No'
    });

    // Poll the server for new notifications
    function checkForNotifications() {
        fetch('check_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.newNotification) {
                showNotificationPopup();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Check for notifications every 30 seconds
    setInterval(checkForNotifications, 30000);
</script>



</body>
</html>
