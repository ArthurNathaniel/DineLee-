<?php
session_start(); 
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch all food categories from the database
$sql = "SELECT * FROM food_categories";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Food Categories</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
<?php include 'sidebar.php'?>
    <div class="auth_all">
        <div class="auth_box">
 
        <h2>Food Categories</h2>

    <!-- Link to go back to the dashboard -->
  

    <!-- Table to display categories -->
    <?php
    if (mysqli_num_rows($result) > 0) {
        // Output the list of food categories
        echo "<table>";
        echo "<tr><th>ID</th><th>Category Name</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['category_name'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align: center;'>No categories found.</p>";
    }
    ?>
        </div>
    </div>
</body>
</html>
