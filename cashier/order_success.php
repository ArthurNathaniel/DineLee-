<?php
session_start();

// Ensure that the cart is empty after the order is successfully placed
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Display success message
$order_message = "Your order has been placed successfully! Thank you for your purchase.";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_success.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="success_container">
        <div class="success_message">
            <h2>Order Success</h2>
            <p><?php echo $order_message; ?></p>
            <a href="1.php" class="btn_return">Go Back to Order Food</a>
        </div>
    </div>
</body>

</html>
