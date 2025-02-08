<?php
session_start(); 
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}
// Query to fetch order details with both date and time of order_date
$query = "
    SELECT 
        o.order_id, 
        o.order_date,  -- Get full DATETIME
        o.total_amount, 
        o.payment_mode, 
        o.cashier_name, 
        oi.food_id, 
        oi.quantity, 
        oi.price, 
        oi.total_price 
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    ORDER BY o.order_date DESC
";
$result = mysqli_query($conn, $query);

// Store the order details in an array by order_id
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_date' => $row['order_date'],
            'cashier_name' => $row['cashier_name'],
            'total_amount' => $row['total_amount'],
            'payment_mode' => $row['payment_mode'],
            'items' => []
        ];
    }

    $food_id = $row['food_id'];
    $food_query = "SELECT food_name FROM food_menu WHERE id = $food_id";
    $food_result = mysqli_query($conn, $food_query);
    $food = mysqli_fetch_assoc($food_result);

    $orders[$order_id]['items'][] = [
        'food_name' => $food['food_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'total_price' => $row['total_price']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_food.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="order_all">
        <div class="order_box">
            <h2>View Orders</h2>

            <?php if (count($orders) > 0) { ?>
                <table class="order_table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Cashier Name</th>
                            <th>Order Date & Time</th>
                            <th>Food Items</th>
                            <th>Total Amount</th>
                            <th>Payment Mode</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order_id => $order) { ?>
                            <tr>
                                <td><?php echo $order_id; ?></td>
                                <td><?php echo $order['cashier_name']; ?></td>
                                <td><?php echo date('Y-m-d g:i A', strtotime($order['order_date'])); ?></td> <!-- Display date and time with AM/PM -->
                                <td>
                                    <?php
                                    foreach ($order['items'] as $item) {
                                        echo $item['food_name'] . ' - ' . number_format($item['price'], 2) . ' x ' . $item['quantity'] . ',' . '<br>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo $order['payment_mode']; ?></td>
                                <!-- <td><button class="print-button" onclick="printReceipt(<?php echo $order_id; ?>)"><i class="fa-solid fa-print"></i></button></td> -->
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No orders found.</p>
            <?php } ?>
        </div>
    </div>
    <script>
        function printReceipt(orderId) {
            // Create a form to submit the order ID to the print page
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = 'print_receipt.php'; // Separate page for printing
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_id';
            input.value = orderId;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>
