<?php
session_start();
include 'db.php'; // Database connection

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    // Update order status to "cancelled" and reset total amount
    $update_query = "UPDATE orders SET status='cancelled', total_amount=0 WHERE order_id='$order_id'";
    mysqli_query($conn, $update_query);

    // Remove all items from order_items table for the canceled order
    $delete_items_query = "DELETE FROM order_items WHERE order_id='$order_id'";
    mysqli_query($conn, $delete_items_query);

    $_SESSION['success'] = "Order #$order_id has been cancelled successfully.";
    header("Location: cancel_order.php");
    exit();
}

// Fetch all orders and their food items
$query = "
    SELECT o.order_id, o.order_date, o.total_amount, o.payment_mode, o.cashier_name, o.status,
           fm.food_name, oi.quantity, oi.price
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN food_menu fm ON oi.food_id = fm.id
    ORDER BY o.order_date DESC
";

$result = mysqli_query($conn, $query);

// Organize data by order ID
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'total_amount' => $row['total_amount'],
            'payment_mode' => $row['payment_mode'],
            'cashier_name' => $row['cashier_name'],
            'status' => $row['status'],
            'items' => []
        ];
    }
    // Fix: Use food_name instead of product_name
    if ($row['food_name']) {
        $orders[$order_id]['items'][] = [
            'food_name' => $row['food_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Orders</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_food.css">
</head>
<body>
    <?php include 'sidebar.php';?>
    <div class="order_all">
    <div class="order_box">
    <h2>Cancel Orders</h2>

    <?php if (isset($_SESSION['success'])) { ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php } ?>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Cashier Name</th>
                <th>Order Date & Time</th>
                <th>Total Amount</th>
                <th>Payment Mode</th>
                <th>Food Items</th>
                <!-- <th>Status</th> -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['cashier_name']; ?></td>
                    <td><?php echo date('Y-m-d g:i A', strtotime($order['order_date'])); ?></td>
                    <td>GH₵<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo $order['payment_mode']; ?></td>
                    <td>
                        <!-- <ul> -->
                            <?php foreach ($order['items'] as $item) { ?>
                                <p><?php echo $item['food_name']; ?> (<?php echo $item['quantity']; ?>) - GH₵<?php echo number_format($item['price'], 2); ?>,</p>
                            <?php } ?>
                        <!-- </ul> -->
                    </td>
                    <!-- <td><?php echo ucfirst($order['status']); ?></td> -->
                    <td>
                        <?php if ($order['status'] == 'pending') { ?>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="cancel_order" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                            </form>
                        <?php } else { ?>
                            <button disabled>Cancelled</button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    </div>
</body>
</html>
<style>
    table button{
        background-color: #cc1827;
    color: #fff;
    border: none;
    padding:15px ;
    border-radius: 10px;
    }
</style>