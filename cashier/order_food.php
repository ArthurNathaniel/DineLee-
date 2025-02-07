<?php
session_start();
include 'db.php'; // Include the database connection

// Check if cashier is logged in
if (!isset($_SESSION['cashier_id'])) {
    header("Location: login.php");
    exit;
}

$cashier_name = $_SESSION['cashier_name'];
date_default_timezone_set('Africa/Accra');
$current_date = date("l, d F Y H:i:s"); // Format: Thursday, 06 February 2025 14:30:45

// $current_date = date('l, F j, Y');
// $current_date = date('l, F j, Y'); // Date without time
// $current_date = date("l, d F Y"); // Format the date as "Thursday, 06 February 2025"

// Set number of items per page
$items_per_page = 5;

// Calculate the total number of food items
$sql_count = "SELECT COUNT(*) AS total_items FROM food_menu";
$count_result = mysqli_query($conn, $sql_count);
$count_data = mysqli_fetch_assoc($count_result);
$total_items = $count_data['total_items'];

// Calculate the total number of pages
$total_pages = ceil($total_items / $items_per_page);

// Get the current page from the URL, default to page 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $items_per_page;

// Get food items for the current page
$sql = "SELECT * FROM food_menu LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $sql);
$foods = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle cart actions (add, remove items)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        // Add item to the cart
        $food_id = $_POST['food_id'];
        $quantity = $_POST['quantity'];

        // If no quantity is entered, default to 1
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Store cart in session
        if (isset($_SESSION['cart'][$food_id])) {
            // If item already in cart, update the quantity
            $_SESSION['cart'][$food_id]['quantity'] += $quantity;
        } else {
            // Otherwise, add new item to the cart
            $_SESSION['cart'][$food_id] = [
                'food_id' => $food_id,
                'quantity' => $quantity
            ];
        }
    }

    if (isset($_POST['remove_from_cart'])) {
        // Remove item from the cart
        $food_id = $_POST['food_id'];
        unset($_SESSION['cart'][$food_id]);
    }

    // Handle payment mode
    if (isset($_POST['payment_mode'])) {
        $_SESSION['payment_mode'] = $_POST['payment_mode'];
    }
}

// Calculate total price
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $food_id = $item['food_id'];
        // Fetch food details
        $food_sql = "SELECT * FROM food_menu WHERE id = $food_id";
        $food_result = mysqli_query($conn, $food_sql);
        $food = mysqli_fetch_assoc($food_result);
        $total += (float)$food['price'] * (int)$item['quantity'];  // Ensuring correct type casting
    }
}

// Check if payment mode is selected
$payment_mode = isset($_SESSION['payment_mode']) ? $_SESSION['payment_mode'] : '';


if (isset($_POST['submit_order'])) {
    // Ensure there's something in the cart before processing
    if (!empty($_SESSION['cart'])) {
        // Get the cashier ID and other details from session
        $cashier_id = $_SESSION['cashier_id'];
        $cashier_name = $_SESSION['cashier_name'];
        $order_date = date('Y-m-d h:i:s A');  // Correct 12-hour format with AM/PM

        $total_amount = $total;  // The total amount calculated earlier
        $payment_mode = $_POST['payment_mode'];  // Payment method selected

        // Insert the order details into the `orders` table
        $insert_order_sql = "INSERT INTO orders (cashier_id, cashier_name, order_date, total_amount, payment_mode) 
                             VALUES ('$cashier_id', '$cashier_name', '$order_date', '$total_amount', '$payment_mode')";

        if (mysqli_query($conn, $insert_order_sql)) {
            // Get the last inserted order ID
            $order_id = mysqli_insert_id($conn);

            // Insert each food item in the cart into the `order_items` table
            foreach ($_SESSION['cart'] as $item) {
                $food_id = $item['food_id'];
                $quantity = $item['quantity'];

                // Fetch food price for this item
                $food_sql = "SELECT price FROM food_menu WHERE id = $food_id";
                $food_result = mysqli_query($conn, $food_sql);
                $food = mysqli_fetch_assoc($food_result);
                $price = $food['price'];

                // Calculate the total price for this item
                $total_price = $price * $quantity;

                // Insert the order item into the `order_items` table
                $insert_item_sql = "INSERT INTO order_items (order_id, food_id, quantity, price, total_price) 
                                    VALUES ('$order_id', '$food_id', '$quantity', '$price', '$total_price')";
                mysqli_query($conn, $insert_item_sql);
            }

            // Clear the cart after the order is saved
            unset($_SESSION['cart']);

            // Redirect or display a success message
            header("Location: view_orders.php");
            exit;
        } else {
            // Handle any errors during order insertion
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Food</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/order_food.css">
    <script>
    function searchFood() {
        const searchQuery = document.getElementById("search_input").value.trim().toLowerCase();
        const foodMenu = document.querySelector(".food_menu");
        const noMatchMessage = document.getElementById("no_match_message");

        if (searchQuery === "") {
            location.reload(); // Reload page to restore pagination when search is empty
            return;
        }

        fetch(`search_food.php?query=${searchQuery}`)
            .then(response => response.json())
            .then(data => {
                foodMenu.innerHTML = "";
                if (data.length === 0) {
                    noMatchMessage.style.display = "block";
                } else {
                    noMatchMessage.style.display = "none";
                    data.forEach(food => {
                        foodMenu.innerHTML += `
                            <div class="food_card">
                                <img src="../admin/uploads/${food.food_image}" alt="${food.food_name}" class="food-image">
                                <h3>${food.food_name}</h3>
                                <p>Price: GHS ${parseFloat(food.price).toFixed(2)}</p>
                                <form action="order_food.php" method="POST">
                                    <input type="number" name="quantity" min="1" placeholder="Enter the Quantity" required>
                                    <input type="hidden" name="food_id" value="${food.id}">
                                    <div class="add_to_cart">
                                        <button type="submit" name="add_to_cart"><i class="fa-solid fa-cart-plus"></i></button>
                                    </div>
                                </form>
                            </div>
                        `;
                    });
                }
            })
            .catch(error => console.error('Error fetching search results:', error));
    }
</script>

</head>
    <?php include 'sidebar.php' ?>
    <div class="order_all">
        <div class="order_box">
            <h2>Order Food</h2>
            <form action="order_food.php" method="POST">
                <!-- Cashier Name and Date -->
                <div class="forms">
                    <label for="cashier_name">Cashier: </label>
                    <input type="text" id="cashier_name" name="cashier_name" value="<?= $cashier_name ?>" readonly>
                </div>

                <!-- <div class="forms">
                    <label for="current_date">Date: </label>
                    <input type="text" id="current_date" name="current_date" value="<?= $current_date ?>" readonly>
                </div> -->
                <!-- <div class="forms">
    <label for="current_date">Date: </label>
    <input type="text" id="current_date" name="current_date" value="<?= $current_date ?>" readonly>
</div> -->
<div class="forms">
    <label for="current_date">Date & Time: </label>
    <input type="text" id="current_date" name="current_date" value="<?= $current_date ?>" readonly>
</div>
        

                <!-- Search Input -->
                <div class="forms">
                    <label for="search_input">Search Food:</label>
                    <input type="text" id="search_input" onkeyup="searchFood()" placeholder="Search by food name...">
                </div>

           <!-- Food Menu -->
<div class="food_menu">
    <?php
    foreach ($foods as $food):
    ?>
        <div class="food_card">
            <img src="../admin/uploads/<?= $food['food_image'] ?>" alt="<?= $food['food_name'] ?>" class="food-image">
            <h3><?= $food['food_name'] ?></h3>
            <p>Price: GHS <?= number_format($food['price'], 2) ?></p>
            <form action="order_food.php" method="POST">
                <input type="number" name="quantity" min="1" placeholder="Enter the Quantity" >
                <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                <div class="add_to_cart">
                    <button type="submit" name="add_to_cart"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<!-- No Match Found Message -->
<p id="no_match_message" style="display: none; color: red;">No food items match your search.</p>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="order_food.php?page=<?= $page - 1 ?>" class="prev">Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="order_food.php?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="order_food.php?page=<?= $page + 1 ?>" class="next">Next</a>
    <?php endif; ?>
</div>

            </form>

            <!-- Cart Display -->
            <h3>Your Cart</h3>
            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart'])):
                        foreach ($_SESSION['cart'] as $item):
                            $food_id = $item['food_id'];
                            $food_sql = "SELECT * FROM food_menu WHERE id = $food_id";
                            $food_result = mysqli_query($conn, $food_sql);
                            $food = mysqli_fetch_assoc($food_result);
                            $total_price = (float)$food['price'] * (int)$item['quantity'];
                    ?>
                            <tr>
                                <td><?= $food['food_name'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>GHS <?= number_format($food['price'], 2) ?></td>
                                <td>GHS <?= number_format($total_price, 2) ?></td>
                                <td>
                                    <form action="order_food.php" method="POST">
                                        <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                                        <div class="remove_from_cart">
                                     <button type="submit" name="remove_from_cart"><i class="fa-solid fa-trash-can"></i></button>
                                     </div>
                                    </form>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>

            <div class="forms">
                <h3>Total: GHS <?= number_format($total, 2) ?></h3>
            </div>

            <!-- Payment Mode -->
            <form action="order_food.php" method="POST">
                <div class="forms">
                    <label for="payment_mode">Select Payment Mode: </label>
                    <select name="payment_mode" id="payment_mode">
                        <option value="" selected hidden>Select Payment Method</option>
                        <option value="momo" <?= $payment_mode == 'momo' ? 'selected' : '' ?>>Mobile Money</option>
                        <option value="cash" <?= $payment_mode == 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="bank_transfer" <?= $payment_mode == 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                        <option value="other" <?= $payment_mode == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="forms">
                    <button type="submit" name="submit_order">Confirm Order</button>
                </div>
            </form>

           

        </div>
    </div>

    <!-- <script>
flatpickr("#current_date", {
    dateFormat: "l, d F Y",  // Format the date as "Thursday, 06 February 2025"
    defaultDate: "today",    // Default to today's date
    minDate: "today",        // Disable past dates
    maxDate: "today",        // Disable future dates
    locale: {
        firstDayOfWeek: 1 // Optionally set the first day of the week (1 = Monday)
    },
    mobile: {
        enabled: true // Enable mobile mode for better display on small screens
    }
});


</script> -->
</body>

</html>
