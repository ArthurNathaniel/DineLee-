<?php
session_start();
include 'db.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get selected date (default to today)
$selected_date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

// Fetch total sales for the selected date
$sales_query = "
    SELECT SUM(total_amount) AS total_sales 
    FROM orders 
    WHERE DATE(order_date) = '$selected_date'
";
$sales_result = mysqli_query($conn, $sales_query);
$sales_row = mysqli_fetch_assoc($sales_result);
$total_sales = $sales_row['total_sales'] ?? 0;

// Fetch total expenses for the selected date
$expenses_query = "
    SELECT SUM(amount) AS total_expenses 
    FROM expenses 
    WHERE DATE(date) = '$selected_date'
";
$expenses_result = mysqli_query($conn, $expenses_query);
$expenses_row = mysqli_fetch_assoc($expenses_result);
$total_expenses = $expenses_row['total_expenses'] ?? 0;

// Calculate profit or loss
$profit_loss = $total_sales - $total_expenses;
$profit_color = $profit_loss >= 0 ? "green" : "red";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Earnings Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Daily Earnings Report</h2>

            <!-- Form to select a specific date -->
            <form method="POST">
                <div class="forms">
                    <label for="date">Select Date:</label>
                    <input type="date" name="date" value="<?php echo $selected_date; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                <div class="forms">
                    <button type="submit">View Report</button>
                </div>
            </form>

            <!-- Table to display sales, expenses, and profit/loss -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Sales (GH₵)</th>
                        <th>Total Expenses (GH₵)</th>
                        <th>Profit / Loss (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $selected_date; ?></td>
                        <td>GH₵ <?php echo number_format($total_sales, 2); ?></td>
                        <td>GH₵ <?php echo number_format($total_expenses, 2); ?></td>
                        <td style="color: <?php echo $profit_color; ?>; font-weight: bold;">
                            GH₵ <?php echo number_format($profit_loss, 2); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
