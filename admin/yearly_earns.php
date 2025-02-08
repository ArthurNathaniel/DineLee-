<?php 
session_start();
include 'db.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Earnings Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Yearly Earnings Report (2024 - 2050)</h2>

            <!-- Table to display sales, expenses, and profit/loss for all years -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Total Sales (GH₵)</th>
                        <th>Total Expenses (GH₵)</th>
                        <th>Profit / Loss (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($year = 2024; $year <= 2050; $year++) {
                        // Fetch total sales for the year
                        $sales_query = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE YEAR(order_date) = '$year'";
                        $sales_result = mysqli_query($conn, $sales_query);
                        $sales_row = mysqli_fetch_assoc($sales_result);
                        $total_sales = $sales_row['total_sales'] ?? 0;

                        // Fetch total expenses for the year
                        $expenses_query = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE YEAR(date) = '$year'";
                        $expenses_result = mysqli_query($conn, $expenses_query);
                        $expenses_row = mysqli_fetch_assoc($expenses_result);
                        $total_expenses = $expenses_row['total_expenses'] ?? 0;

                        // Calculate profit or loss
                        $profit_loss = $total_sales - $total_expenses;
                        $profit_color = $profit_loss >= 0 ? "green" : "red";

                        echo "<tr>
                            <td>$year</td>
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
