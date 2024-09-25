<?php
include "connection.php";

// Get the selected year from the form submission, or default to the current year
if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
} else {
    $selectedYear = date('Y');
}

// Query to retrieve paid students with their names for each month in the selected year
$sql = "SELECT DATE_FORMAT(p.payment_date, '%Y-%m') as month, 
               GROUP_CONCAT(c.full_name SEPARATOR ', ') as paid_students
        FROM child c
        LEFT JOIN payments p ON c.childID = p.childID AND DATE_FORMAT(p.payment_date, '%Y') = '$selectedYear'
        WHERE p.amount IS NOT NULL
        GROUP BY month
        ORDER BY month";

$result = $conn->query($sql);

$monthlyData = [];
while ($row = $result->fetch_assoc()) {
    $month = $row['month'];
    $paidStudents = $row['paid_students'];
    
    // Split the paid students into an array
    $paidStudentsArray = explode(', ', $paidStudents);
    
    $monthlyData[$month] = [
        'month_name' => date('F', strtotime($month)),
        'paid_count' => count($paidStudentsArray),
        'paid_students' => $paidStudentsArray
    ];
}

$conn->close();

// Generate data for each month of the selected year, ensuring all months are included
$months = [];
$paidCounts = [];
$paidStudents = [];
for ($m = 1; $m <= 12; $m++) {
    $monthKey = sprintf('%04d-%02d', $selectedYear, $m);
    $monthName = date('F', mktime(0, 0, 0, $m, 1, $selectedYear));
    
    $months[] = $monthName;
    if (isset($monthlyData[$monthKey])) {
        $paidCounts[] = $monthlyData[$monthKey]['paid_count'];
        $paidStudents[] = $monthlyData[$monthKey]['paid_students'];
    } else {
        $paidCounts[] = 0;
        $paidStudents[] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Fee Payment Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        header {
            background-color: #52B4B7;
            color: white;
            text-align: center;
            padding: 10px 20px;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        .chart-container {
            position: relative;
            width: 80%;
            margin: 0 auto;
        }

        .form-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .form-container select, .form-container button {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #52B4B7;
            color: white;
        }

    </style>
</head>
<body>

<header>
    <h1>Fee Payment Reports</h1>
</header>

<div class="container">
    <div class="form-container">
        <form method="POST" action="">
            <select name="year">
                <?php for ($y = 2024; $y <= 2030; $y++): ?>
                    <option value="<?php echo $y; ?>" <?php if ($selectedYear == $y) echo 'selected'; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit">View Report</button>
        </form>
    </div>
    <h2>Payment Status for <?php echo $selectedYear; ?></h2>
    <div class="chart-container">
        <canvas id="paymentChart"></canvas>
    </div>
    <div class="table-container">
        <h2>Total Paid Students by Month</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Paid Students</th>
                    <th>Names</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($months as $index => $monthName): ?>
                    <tr>
                        <td><?php echo $monthName; ?></td>
                        <td><?php echo $paidCounts[$index]; ?></td>
                        <td><?php echo implode(', ', $paidStudents[$index]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="adminmainpage.php" class="back-button">Back to Main Page</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('paymentChart').getContext('2d');
    const months = <?php echo json_encode($months); ?>;
    const paidCounts = <?php echo json_encode($paidCounts); ?>;
    const paidStudents = <?php echo json_encode($paidStudents); ?>;
    
    const paymentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Paid Students',
                data: paidCounts,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const dataIndex = context.dataIndex;
                            const paidStudentsList = paidStudents[dataIndex];
                            return paidStudentsList.join(', ');
                        }
                    }
                },
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Fee Payment Status'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Students'
                    },
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>

</body>
</html>
