<?php
session_start(); 
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}
// Fetch food menu items
$sql = "SELECT food_menu.id, food_menu.food_name, food_menu.price, food_categories.category_name, food_menu.food_image
        FROM food_menu
        JOIN food_categories ON food_menu.category_id = food_categories.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Food Menu</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
<?php include 'sidebar.php'?>
    <div class="auth_alls">
        <div class="auth_box">
        
                <h2>Food Menu</h2>

            <!-- Food Menu Table -->
            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Food Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['food_name'] . "</td>";
                            echo "<td>" . number_format($row['price'], 2) . "</td>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td><img src='uploads/" . $row['food_image'] . "' alt='" . $row['food_name'] . "' width='100'></td>";
                            echo "<td><a href='edit_menu.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_menu.php?id=" . $row['id'] . "'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No food items found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>
