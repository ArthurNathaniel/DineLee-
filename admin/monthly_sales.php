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

// Fetch monthly sales grouped by cashier for the selected year
$query = "
    SELECT 
        MONTH(o.order_date) AS month,
        c.name AS cashier,
        SUM(o.total_amount) AS total_sales
    FROM orders o
    JOIN cashiers c ON o.cashier_id = c.id
    WHERE YEAR(o.order_date) = '$selected_year'
    GROUP BY MONTH(o.order_date), c.name
    ORDER BY MONTH(o.order_date), c.name;
";
$result = mysqli_query($conn, $query);

// Store monthly sales with cashiers
$monthly_sales = [];
$total_sales_year = 0; // Total sales for the year
$monthly_totals = []; // Store total sales per month

while ($row = mysqli_fetch_assoc($result)) {
    $month = $row['month'];
    $cashier = $row['cashier'];
    $sales = $row['total_sales'];

    // Store sales under the respective month and cashier
    $monthly_sales[$month][$cashier] = $sales;
    $monthly_totals[$month] = ($monthly_totals[$month] ?? 0) + $sales;
    $total_sales_year += $sales; // Add to total yearly sales
}

// Month names
$months = [
    1 => "January", 2 => "February", 3 => "March", 4 => "April",
    5 => "May", 6 => "June", 7 => "July", 8 => "August",
    9 => "September", 10 => "October", 11 => "November", 12 => "December"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Monthly Sales Report</h2>

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

            <!-- Table to display sales for each month and cashier -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Cashier</th>
                        <th>Total Sales (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($months as $num => $name) {
                        if (isset($monthly_sales[$num])) {
                            foreach ($monthly_sales[$num] as $cashier => $sales) {
                                echo "<tr>
                                        <td>{$name}</td>
                                        <td>{$cashier}</td>
                                        <td>GH₵ " . number_format($sales, 2) . "</td>
                                      </tr>";
                            }
                            // Display total for the month
                            echo "<tr style='font-weight: bold;'>
                                    <td colspan='2'>Total for {$name}</td>
                                    <td>GH₵ " . number_format($monthly_totals[$num], 2) . "</td>
                                  </tr>";
                        } else {
                            echo "<tr>
                                    <td>{$name}</td>
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
