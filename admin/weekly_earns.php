<?php 
session_start();
include 'db.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get selected year (default to the current year)
$selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Get total weeks in the selected year
$total_weeks = date("W", strtotime($selected_year . "-12-28"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Earnings Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Weekly Earnings Report</h2>

            <!-- Form to select year -->
            <form method="POST">
                <div class="forms">
                    <label for="year">Select Year:</label>
                    <select name="year" id="year" onchange="this.form.submit()">
                        <?php
                        for ($year = date('Y'); $year >= 2020; $year--) {
                            echo "<option value='$year' " . ($selected_year == $year ? 'selected' : '') . ">$year</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>

            <!-- Table to display sales, expenses, and profit/loss for all weeks -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Sales (GH₵)</th>
                        <th>Total Expenses (GH₵)</th>
                        <th>Profit / Loss (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($week = 1; $week <= $total_weeks; $week++) {
                        $start_date = date('Y-m-d', strtotime($selected_year . "W" . str_pad($week, 2, '0', STR_PAD_LEFT)));
                        $end_date = date('Y-m-d', strtotime($start_date . ' +6 days'));

                        // Fetch total sales for the week
                        $sales_query = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                        $sales_result = mysqli_query($conn, $sales_query);
                        $sales_row = mysqli_fetch_assoc($sales_result);
                        $total_sales = $sales_row['total_sales'] ?? 0;

                        // Fetch total expenses for the week
                        $expenses_query = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE DATE(date) BETWEEN '$start_date' AND '$end_date'";
                        $expenses_result = mysqli_query($conn, $expenses_query);
                        $expenses_row = mysqli_fetch_assoc($expenses_result);
                        $total_expenses = $expenses_row['total_expenses'] ?? 0;

                        // Calculate profit or loss
                        $profit_loss = $total_sales - $total_expenses;
                        $profit_color = $profit_loss >= 0 ? "green" : "red";

                        echo "<tr>
                                <td>Week $week</td>
                                <td>$start_date</td>
                                <td>$end_date</td>
                                <td>GH₵ " . number_format($total_sales, 2) . "</td>
                                <td>GH₵ " . number_format($total_expenses, 2) . "</td>
                                <td style='color: $profit_color; font-weight: bold;'>GH₵ " . number_format($profit_loss, 2) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
