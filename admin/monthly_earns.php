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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Earnings Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Monthly Earnings Report</h2>

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

            <!-- Table to display sales, expenses, and profit/loss -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Sales (GH₵)</th>
                        <th>Total Expenses (GH₵)</th>
                        <th>Profit / Loss (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($month = 1; $month <= 12; $month++) {
                        $start_date = date("Y-m-01", strtotime("$selected_year-$month-01"));
                        $end_date = date("Y-m-t", strtotime($start_date));

                        // Fetch total sales for the month
                        $sales_query = "
                            SELECT SUM(total_amount) AS total_sales 
                            FROM orders 
                            WHERE DATE(order_date) BETWEEN '$start_date' AND '$end_date'";
                        $sales_result = mysqli_query($conn, $sales_query);
                        $sales_row = mysqli_fetch_assoc($sales_result);
                        $total_sales = $sales_row['total_sales'] ?? 0;

                        // Fetch total expenses for the month
                        $expenses_query = "
                            SELECT SUM(amount) AS total_expenses 
                            FROM expenses 
                            WHERE DATE(date) BETWEEN '$start_date' AND '$end_date'";
                        $expenses_result = mysqli_query($conn, $expenses_query);
                        $expenses_row = mysqli_fetch_assoc($expenses_result);
                        $total_expenses = $expenses_row['total_expenses'] ?? 0;

                        // Calculate profit or loss
                        $profit_loss = $total_sales - $total_expenses;
                        $profit_color = $profit_loss >= 0 ? "green" : "red";
                    ?>
                    <tr>
                        <td><?php echo date("F", strtotime("$selected_year-$month-01")); ?></td>
                        <td>GH₵ <?php echo number_format($total_sales, 2); ?></td>
                        <td>GH₵ <?php echo number_format($total_expenses, 2); ?></td>
                        <td style="color: <?php echo $profit_color; ?>; font-weight: bold;">
                            GH₵ <?php echo number_format($profit_loss, 2); ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
