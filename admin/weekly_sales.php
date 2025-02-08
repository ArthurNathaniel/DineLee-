<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Get selected year (default to current year)
$selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Fetch weekly sales grouped by cashier for the selected year
$query = "
    SELECT 
        WEEK(o.order_date, 1) AS week_number,
        c.name AS cashier,
        SUM(o.total_amount) AS total_sales
    FROM orders o
    JOIN cashiers c ON o.cashier_id = c.id
    WHERE YEAR(o.order_date) = '$selected_year'
    GROUP BY WEEK(o.order_date, 1), c.name
    ORDER BY WEEK(o.order_date, 1), c.name;
";
$result = mysqli_query($conn, $query);

// Store weekly sales with cashiers
$weekly_sales = [];
$total_sales_year = 0; // Total sales for the year
$weekly_totals = []; // Store total sales per week

while ($row = mysqli_fetch_assoc($result)) {
    $week = $row['week_number'];
    $cashier = $row['cashier'];
    $sales = $row['total_sales'];

    // Store sales under the respective week and cashier
    $weekly_sales[$week][$cashier] = $sales;
    $weekly_totals[$week] = ($weekly_totals[$week] ?? 0) + $sales;
    $total_sales_year += $sales; // Add to total yearly sales
}

// Get the last week of the selected year
$last_week = 52; // Show all 52 weeks
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Sales Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Weekly Sales Report</h2>

            <!-- Form to select the year -->
            <form method="POST">
                <div class="forms">
                    <label for="year">Select Year:</label>
                    <select name="year" required>
                        <?php
                        for ($year = 2024; $year <= 2090; $year++) {
                            echo "<option value='$year' " . ($year == $selected_year ? 'selected' : '') . ">$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="forms">
                    <button type="submit">View Report</button>
                </div>
            </form>

            <!-- Table to display sales for each week and cashier -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Cashier</th>
                        <th>Total Sales (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($week = 1; $week <= $last_week; $week++) {
                        if (isset($weekly_sales[$week])) {
                            foreach ($weekly_sales[$week] as $cashier => $sales) {
                                echo "<tr>
                                        <td>Week {$week}</td>
                                        <td>{$cashier}</td>
                                        <td>GH₵ " . number_format($sales, 2) . "</td>
                                      </tr>";
                            }
                            // Display total for the week
                            echo "<tr style='font-weight: bold;'>
                                    <td colspan='2'>Total for Week {$week}</td>
                                    <td>GH₵ " . number_format($weekly_totals[$week], 2) . "</td>
                                  </tr>";
                        } else {
                            echo "<tr>
                                    <td>Week {$week}</td>
                                    <td>—</td>
                                    <td>GH₵ 0.00</td>
                                  </tr>";
                        }
                    }
                    ?>
                    <tr style="font-weight: bold; background-color: #f2f2f2;">
                        <td colspan="2">Total Sales for <?php echo $selected_year; ?></td>
                        <td>GH₵ <?php echo number_format($total_sales_year, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
