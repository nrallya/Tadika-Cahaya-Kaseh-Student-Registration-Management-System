<?php
$pageTitle = "Parent Management";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get total number of parents
function getTotalParents($conn) {
    $sql = "SELECT COUNT(*) as totalParents FROM parent";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalParents'];
    } else {
        return 0; // Return 0 if there's an error or no parents found
    }
}

// Fetch parent and child data joined together
$sql = "SELECT p.parentID, p.father_name, p.fatherIC, p.fatherOcc, p.fatherOccAddr, p.fathersalary, p.fatherPhoneNum, 
               p.mother_name, p.motherIC, p.motherOcc, p.motherOccAdd, p.mothersalary, p.motherPhoneNum, 
               p.EmergencyContact
        FROM parent p";
     
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        h1 {
            color: white;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            color: white;
            margin-right: 5px;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        .parent-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f2f2f2;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .parent-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hide child details */
        }

        .details-table th, .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .details-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        .details-table td {
            background-color: #fff;
        }

        .action-links {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .add-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .back-btn-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .parent-section {
                padding: 10px;
            }

            .details-table th, .details-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1><?php echo $pageTitle; ?></h1>
</header>

<div class="container">
    <div class="add-btn-container">
        <a href="adminaddparent.php" class="back-btn">Add New Parent</a>
    </div>

    <p>Total Parents: <?php echo getTotalParents($conn); ?></p>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        $currentParentID = null;
        while ($row = mysqli_fetch_assoc($result)) {
            // Display parent section header and details
            if ($currentParentID !== $row["parentID"]) {
                if ($currentParentID !== null) {
                    echo "</table>"; // Close previous details-table
                    echo "<div class='action-links'>";
                    echo "<a class='action-btn edit-btn' href='admineditparent.php?parentID=" . $currentParentID . "'>Edit Parent</a>";
                    echo "<a class='action-btn delete-btn' href='#' data-id='" . $currentParentID . "' onclick='deleteParent(" . $currentParentID . ");'>Delete Parent</a>";
                    echo "</div>";
                    echo "</div>"; // Close previous parent section
                }
                echo "<div class='parent-section'>";
                echo "<div class='parent-header' onclick='toggleDetails(this)'>";
                echo "Father Name: " . $row["father_name"];
                echo "<span class='toggle-details'>(click to toggle)</span>";
                echo "</div>";
                echo "<table class='details-table'>";
                echo "<tr><th>Father Name</th><td>" . $row["father_name"] . "</td></tr>";
                echo "<tr><th>Father IC</th><td>" . $row["fatherIC"] . "</td></tr>";
                echo "<tr><th>Father Occupation</th><td>" . $row["fatherOcc"] . "</td></tr>";
                echo "<tr><th>Father Occ. Address</th><td>" . $row["fatherOccAddr"] . "</td></tr>";
                echo "<tr><th>Father Salary</th><td>" . $row["fathersalary"] . "</td></tr>";
                echo "<tr><th>Father Phone Number</th><td>" . $row["fatherPhoneNum"] . "</td></tr>";
                echo "<tr><th>Mother Name</th><td>" . $row["mother_name"] . "</td></tr>";
                echo "<tr><th>Mother IC</th><td>" . $row["motherIC"] . "</td></tr>";
                echo "<tr><th>Mother Occupation</th><td>" . $row["motherOcc"] . "</td></tr>";
                echo "<tr><th>Mother Occ. Address</th><td>" . $row["motherOccAdd"] . "</td></tr>";
                echo "<tr><th>Mother Salary</th><td>" . $row["mothersalary"] . "</td></tr>";
                echo "<tr><th>Mother Phone Number</th><td>" . $row["motherPhoneNum"] . "</td></tr>";
                echo "<tr><th>Emergency Contact</th><td>" . $row["EmergencyContact"] . "</td></tr>";
                echo "</table>";
                echo "<table class='details-table'>";
                echo "<tr><th colspan='2'>Child Details</th></tr>";
                $currentParentID = $row["parentID"];
            }

            
        }
        echo "</table>"; // Close last child details table
        echo "<div class='action-links'>";
        echo "<a class='action-btn edit-btn' href='admineditparent.php?parentID=" . $currentParentID . "'>Edit Parent</a>";
        echo "<a class='action-btn delete-btn' href='#' data-id='" . $currentParentID . "' onclick='deleteParent(" . $currentParentID . ");'>Delete Parent</a>";
        echo "</div>";
        echo "</div>"; // Close last parent section
    } else {
        echo "<p>No records found</p>";
    }
    ?>

    <!-- Back Button -->
    <div class="back-btn-container">
        <a href="adminmainpage.php" class="back-btn">Back</a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to toggle child details table visibility
        function toggleDetails(element) {
            var detailsTable = element.nextElementSibling;
            if (detailsTable.style.display === "none" || detailsTable.style.display === "") {
                detailsTable.style.display = "table";
                element.querySelector(".toggle-details").textContent = "(click to hide)";
            } else {
                detailsTable.style.display = "none";
                element.querySelector(".toggle-details").textContent = "(click to show)";
            }
        }

        // Attach click event listener to all parent headers
        var toggleDetailsElements = document.querySelectorAll('.parent-header');
        toggleDetailsElements.forEach(function(element) {
            element.addEventListener('click', function() {
                toggleDetails(this);
            });
        });
    });

    function deleteParent(parentID) {
        if (confirm("Are you sure you want to delete this parent and all associated children?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "deleteparent.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Reload the page to reflect the changes
                        location.reload();
                    } else {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.send("parentID=" + parentID);
        }
    }
</script>

</body>
</html>
<?php mysqli_close($conn); ?>
