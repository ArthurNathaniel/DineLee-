<?php
// Include the database connection
include('db.php');

// Default to today if no date is provided
$date_filter = isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : date('Y-m-d');

// Query to get the total sales for the day
$total_query = "SELECT SUM(total_amount) AS total_day_sales FROM orders WHERE DATE(order_date) = '$date_filter'";
$total_result = mysqli_query($conn, $total_query) or die("Query Failed: " . mysqli_error($conn));
$total_row = mysqli_fetch_assoc($total_result);
$total_sales = $total_row['total_day_sales'] ? number_format($total_row['total_day_sales'], 2) : '0.00';

// Query to get total orders and amount by payment method for each cashier
$summary_query = "
  SELECT
    c.name AS cashier_name,
    o.payment_mode,
    COUNT(o.order_id) AS total_orders,
    SUM(o.total_amount) AS total_sales
  FROM
    orders o
  JOIN
    cashiers c ON c.id = o.cashier_id
  WHERE
    DATE(o.order_date) = '$date_filter'
  GROUP BY
    c.id, o.payment_mode
  ORDER BY
    c.name, o.payment_mode;
";
$summary_result = mysqli_query($conn, $summary_query) or die("Query Failed: " . mysqli_error($conn));

// Query to get the breakdown of orders
$breakdown_query = "
  SELECT
    o.order_id,
    c.name AS cashier_name,
    o.payment_mode,
    o.order_date,
    GROUP_CONCAT(f.food_name SEPARATOR ', ') AS food_items,
    GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities,
    GROUP_CONCAT(oi.price SEPARATOR ', ') AS prices,
    SUM(oi.total_price) AS total_price  -- Sum the total price of all items in the order
  FROM
    orders o
  JOIN
    cashiers c ON c.id = o.cashier_id
  JOIN
    order_items oi ON oi.order_id = o.order_id
  JOIN
    food_menu f ON f.id = oi.food_id
  WHERE
    DATE(o.order_date) = '$date_filter'
  GROUP BY
    o.order_id
  ORDER BY
    o.order_id;
";

$breakdown_result = mysqli_query($conn, $breakdown_query) or die("Query Failed: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Orders Summary</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_food.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="order_all">
        <div class="order_box">
            <h1>Daily Orders Summary - <?php echo $date_filter; ?></h1>

            <!-- Form to filter sales by date -->
            <form method="POST">
               <div class="forms">
               <label for="date">Select Date:</label>
               <input type="date" id="date" name="date" value="<?php echo $date_filter; ?>" required>
               </div>
                <div class="forms">
                <button type="submit">Filter</button>
                </div>
            </form>

<div class="forms">
<h3>Total Sales for the Day: GHS <?php echo $total_sales; ?></h3>

</div>
            <!-- Table: Orders Summary -->
           <div class="forms">
           <h2>Orders Summary</h2>
           </div>
            <table>
                <thead>
                    <tr>
                        <th>Cashier Name</th>
                        <th>Payment Mode</th>
                        <th>Total Orders</th>
                        <th>Total Sales (GHS)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($summary_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($summary_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['cashier_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_mode']); ?></td>
                                <td><?php echo $row['total_orders']; ?></td>
                                <td><?php echo number_format($row['total_sales'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data">No orders found for this date.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Table: Order Breakdown -->
        <div class="forms">
        <h2>Order Breakdown</h2>
        </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Cashier Name</th>
                        <th>Order Date & Time</th>
                        <th>Food Items</th>
                        <th>Total Amount</th>
                        <th>Payment Mode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($breakdown_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($breakdown_result)): ?>
                            <tr>
                                <td><?php echo $row['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['cashier_name']); ?></td>
                                <td><?php echo date('Y-m-d g:i A', strtotime($row['order_date'])); ?></td> <!-- Display date and time with AM/PM -->
                                <td class="left-align"><?php echo $row['food_items']; ?></td>
                                <td><?php echo number_format($row['total_price'], 2); ?></td> <!-- Display formatted total price -->
                                <td><?php echo htmlspecialchars($row['payment_mode']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-data">No orders found for this date.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php mysqli_close($conn); ?>
</body>
</html>


<style>
    table{
        margin-top: 0;
    }
</style>