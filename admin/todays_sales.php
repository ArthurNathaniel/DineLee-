<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Default to today if no date is provided
$date_filter = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

// Query to get sales for a specific day
$query = "
    SELECT
        c.name AS cashier_name,
        COUNT(o.order_id) AS total_orders,
        SUM(o.total_amount) AS total_sales
    FROM
        cashiers c
    JOIN
        orders o ON c.id = o.cashier_id
    WHERE
        DATE(o.order_date) = '$date_filter'
    GROUP BY
        c.id;
";
$result = mysqli_query($conn, $query);

// Query to calculate total sales for the selected date
$total_query = "
    SELECT SUM(o.total_amount) AS grand_total
    FROM orders o
    WHERE DATE(o.order_date) = '$date_filter';
";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$grand_total = $total_row['grand_total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales by Cashier for <?php echo $date_filter; ?></title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Sales by Cashier for <?php echo $date_filter; ?></h2>

            <!-- Form to filter sales by date -->
            <form method="POST">
                <div class="forms">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $date_filter; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                <div class="forms">
                    <button type="submit">Filter</button>
                </div>
            </form>

            <!-- Table to display sales for the selected date -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Cashier Name</th>
                        <th>Total Orders</th>
                        <th>Total Sales (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['cashier_name']}</td>";
                        echo "<td>{$row['total_orders']}</td>";
                        echo "<td>GH₵ " . number_format($row['total_sales'], 2) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    <!-- Total Sales Row -->
                    <tr style="background-color: #f8d7da; font-weight: bold; color: #721c24;">
                        <td colspan="2" style="text-align: right;">Total Sales:</td>
                        <td>GH₵ <?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>
</body>

</html>
