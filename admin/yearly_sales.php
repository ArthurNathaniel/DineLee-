<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch yearly sales grouped by cashier
$query = "
    SELECT 
        YEAR(o.order_date) AS year,
        c.name AS cashier,
        SUM(o.total_amount) AS total_sales
    FROM orders o
    JOIN cashiers c ON o.cashier_id = c.id
    WHERE YEAR(o.order_date) >= 2024
    GROUP BY YEAR(o.order_date), c.name
    ORDER BY YEAR(o.order_date), c.name;
";
$result = mysqli_query($conn, $query);

// Store yearly sales with cashiers
$yearly_sales = [];
$total_sales_all_years = 0; // Total sales across all years
$yearly_totals = []; // Store total sales per year

while ($row = mysqli_fetch_assoc($result)) {
    $year = $row['year'];
    $cashier = $row['cashier'];
    $sales = $row['total_sales'];

    // Store sales under the respective year and cashier
    $yearly_sales[$year][$cashier] = $sales;
    $yearly_totals[$year] = ($yearly_totals[$year] ?? 0) + $sales;
    $total_sales_all_years += $sales; // Add to total sales
}

// Get current year
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Sales Report by Cashier</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Yearly Sales Report by Cashier</h2>

            <!-- Table to display yearly sales for each cashier -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Cashier</th>
                        <th>Total Sales (GH₵)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($year = 2024; $year <= $current_year; $year++) {
                        if (isset($yearly_sales[$year])) {
                            foreach ($yearly_sales[$year] as $cashier => $sales) {
                                echo "<tr>
                                        <td>{$year}</td>
                                        <td>{$cashier}</td>
                                        <td>GH₵ " . number_format($sales, 2) . "</td>
                                      </tr>";
                            }
                            // Display total for the year
                            echo "<tr style='font-weight: bold;'>
                                    <td colspan='2'>Total for {$year}</td>
                                    <td>GH₵ " . number_format($yearly_totals[$year], 2) . "</td>
                                  </tr>";
                        } else {
                            echo "<tr>
                                    <td>{$year}</td>
                                    <td>—</td>
                                    <td>GH₵ 0.00</td>
                                  </tr>";
                        }
                    }
                    ?>
                    <tr style="font-weight: bold; background-color: #f2f2f2;">
                        <td colspan="2">Total Sales for All Years</td>
                        <td>GH₵ <?php echo number_format($total_sales_all_years, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
