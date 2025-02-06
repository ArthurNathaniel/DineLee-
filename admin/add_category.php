<?php
session_start(); 
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = mysqli_real_escape_string($conn, trim($_POST['category_name'])); // Trim and sanitize input

    // Check if the category name is empty
    if (empty($category_name)) {
        echo "<script>alert('Category name cannot be empty!');</script>";
    } else {
        // Check for duplicates
        $check_sql = "SELECT * FROM food_categories WHERE category_name = '$category_name'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Category already exists!');</script>";
        } else {
            // Insert the category name into the database
            $sql = "INSERT INTO food_categories (category_name) VALUES ('$category_name')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Category added successfully!'); window.location.href='add_category.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Category</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
<?php include 'sidebar.php'?>
    <div class="auth_all">
        <div class="auth_box">

    <h2>Add Food Category</h2>

    <form method="POST">
       <div class="forms">
       <label>Food Category Name:</label>
       <input type="text" name="category_name" required>
       </div>

       <div class="forms">
       <button type="submit">Add Category</button>
       </div>
    </form>
    
</body>
</html>
