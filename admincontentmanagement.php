<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Main Page</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
        }

        nav {
            background-color: #52B4B7;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        section {
            padding: 20px;
        }

        .dashboard-overview {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .user-management,
        .content-management,
        .settings-configuration,
        .reports-analytics,
        .system-maintenance,
        .help-support {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .logout-btn {
            background-color: #f44336;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Main Page</h1>
    </header>

    <nav>
        <ul>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#user-management">User Management</a></li>
            <li><a href="admincontentmanagement.php">Content Management</a></li>
            <li><a href="#settings-configuration">Settings & Configuration</a></li>
            <li><a href="#reports-analytics">Reports & Analytics</a></li>
            <li><a href="#system-maintenance">System Maintenance</a></li>
            <li><a href="adminhelp&support.php">Help & Support</a></li>
            <li><button class="logout-btn" onclick="logout()">Logout</button></li>
        </ul>
    </nav>

    <section id="dashboard" class="dashboard-overview">
        <h2>Dashboard Overview</h2>
        <!-- Add your dashboard content here -->
    </section>

    <section id="user-management" class="user-management">
        <h2>User Management</h2>
        <!-- Add your user management tools here -->
    </section>

    <section id="content-management" class="content-management">
        <h2>Content Management</h2>
        <!-- Add your content management tools here -->
    </section>

    <section id="settings-configuration" class="settings-configuration">
        <h2>Settings & Configuration</h2>
        <!-- Add your settings and configuration options here -->
    </section>

    <section id="reports-analytics" class="reports-analytics">
        <h2>Reports & Analytics</h2>
        <!-- Add your reports and analytics tools here -->
    </section>

    <section id="system-maintenance" class="system-maintenance">
        <h2>System Maintenance</h2>
        <!-- Add your system maintenance utilities here -->
    </section>

    <section id="help-support" class="help-support">
        <h2>Help & Support</h2>
        <!-- Add your help and support resources here -->
    </section>

    <script>
        function logout() {
            // Implement logout functionality here
            alert("Logout button clicked. Implement logout functionality.");
        }
    </script>
</body>
</html>
