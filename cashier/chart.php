<?php
// Include the database connection
include('db.php');

// Query to get the most ordered food items
$food_query = "
  SELECT
    f.food_name,
    SUM(oi.quantity) AS total_orders
  FROM
    order_items oi
  JOIN
    food_menu f ON f.id = oi.food_id
  GROUP BY
    f.food_name
  ORDER BY
    total_orders DESC
  LIMIT 10;
";

$food_result = mysqli_query($conn, $food_query) or die("Query Failed: " . mysqli_error($conn));

// Prepare data for the food chart
$food_names = [];
$food_orders = [];
while ($row = mysqli_fetch_assoc($food_result)) {
    $food_names[] = $row['food_name'];
    $food_orders[] = (int)$row['total_orders'];
}

// Query to get payment mode distribution from the `orders` table
$payment_query = "
  SELECT
    payment_mode,
    COUNT(*) AS total_payments
  FROM
    orders
  GROUP BY
    payment_mode
";

$payment_result = mysqli_query($conn, $payment_query) or die("Query Failed: " . mysqli_error($conn));

// Prepare data for the payment chart
$payment_modes = [];
$payment_counts = [];
while ($row = mysqli_fetch_assoc($payment_result)) {
    $payment_modes[] = $row['payment_mode'];
    $payment_counts[] = (int)$row['total_payments'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Orders & Payment Modes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_food.css">

</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="order_all">
        <div class="order_box">
            <h1>Most Ordered Foods</h1>

            <!-- Line Chart for Most Ordered Foods -->
            <div class="chart-container">
                <canvas id="foodChart"></canvas>
            </div>

            <h1>Payment Methods Used</h1>

            <!-- Pie Chart for Payment Methods -->
            <div class="chart-container">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        // Line Chart for Most Ordered Foods
        var ctx1 = document.getElementById('foodChart').getContext('2d');
        var foodChart = new Chart(ctx1, {
            type: 'line',
            type: 'bar',
            data: {
                labels: <?php echo json_encode($food_names); ?>,
                datasets: [{
                    label: 'Total Orders',
                    data: <?php echo json_encode($food_orders); ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 99, 132)',
                        'rgb(102, 255, 102)',
                        'rgb(153, 51, 204)',
                        'rgb(255, 51, 255)',
                        'rgb(51, 255, 255)',
                        'rgb(51, 204, 255)',
                        'rgb(204, 102, 255)',
                        'rgb(255, 204, 255)',
                        'rgb(204, 255, 204)',
                        'rgb(255, 204, 102)',
                        'rgb(102, 204, 255)',
                        'rgb(255, 153, 51)',
                        'rgb(51, 153, 255)',
                        'rgb(255, 255, 153)',
                        'rgb(204, 204, 255)',
                        'rgb(102, 255, 204)',
                        'rgb(255, 102, 204)',
                        'rgb(204, 255, 102)',
                        'rgb(255, 102, 102)',
                        'rgb(204, 102, 102)',
                        'rgb(102, 102, 204)',
                        'rgb(102, 204, 102)',
                        'rgb(255, 204, 204)',
                        'rgb(102, 153, 255)',
                        'rgb(204, 204, 102)',
                        'rgb(102, 255, 255)',
                        'rgb(153, 255, 153)',
                        'rgb(255, 153, 255)',
                        'rgb(255, 102, 51)',
                        'rgb(204, 51, 204)',
                        'rgb(102, 102, 102)',
                        'rgb(255, 255, 255)',
                        'rgb(255, 0, 255)',
                        'rgb(255, 128, 0)',
                        'rgb(128, 255, 0)',
                        'rgb(0, 255, 128)',
                        'rgb(0, 255, 255)',
                        'rgb(255, 0, 0)',
                        'rgb(255, 128, 128)',
                        'rgb(128, 128, 255)',
                        'rgb(0, 128, 255)',
                        'rgb(255, 255, 0)',
                        'rgb(255, 255, 128)',
                        'rgb(128, 255, 255)',
                        'rgb(255, 64, 128)',
                        'rgb(64, 255, 128)',
                        'rgb(128, 64, 255)',
                    ]

                    // borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart for Payment Methods
        var ctx2 = document.getElementById('paymentChart').getContext('2d');
        var paymentChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($payment_modes); ?>,
                datasets: [{
                    label: 'Payments Count',
                    data: <?php echo json_encode($payment_counts); ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>

</html>