<?php
session_start(); 
include 'db.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch food categories for the select dropdown
$sql = "SELECT * FROM food_categories";
$result = mysqli_query($conn, $sql);

// Handle the form submission
if (isset($_POST['submit'])) {
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    
    // Check if the food item with the same name and price already exists
    $check_sql = "SELECT * FROM food_menu WHERE food_name = '$food_name' AND price = '$price'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Food item with this name and price already exists.');</script>";
    } else {
        // Handle file upload
        $image_name = $_FILES['food_image']['name'];
        $image_tmp_name = $_FILES['food_image']['tmp_name'];
        $image_size = $_FILES['food_image']['size'];
        $image_error = $_FILES['food_image']['error'];

        // Check if image upload was successful
        if ($image_error === 0) {
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($image_ext, $allowed_exts)) {
                if ($image_size < 5000000) { // 5MB limit
                    $new_image_name = uniqid('', true) . '.' . $image_ext;
                    $image_upload_path = 'uploads/' . $new_image_name;

                    // Move the uploaded image to the desired folder
                    if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                        // Insert the food item into the database
                        $sql = "INSERT INTO food_menu (food_name, price, category_id, food_image) 
                                VALUES ('$food_name', '$price', '$category_id', '$new_image_name')";
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Food item added successfully!'); window.location.href='view_menu.php';</script>";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Error uploading image.";
                    }
                } else {
                    echo "File size is too large.";
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, PNG, GIF files are allowed.";
            }
        } else {
            echo "Error uploading file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Menu</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
<?php include 'sidebar.php'?>
    <div class="auth_all">
        <div class="auth_box">

            <div class="logo"></div>
            <div class="title">
                <h2>Add Food Menu</h2>
            </div>

            <!-- Food Menu Form -->
            <form action="add_menu.php" method="POST" enctype="multipart/form-data">
                <div class="forms">
                    <label for="food_name">Food Name:</label>
                    <input type="text" name="food_name" id="food_name" required>
                </div>

                <div class="forms">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" required>
                </div>

                <div class="forms">
                    <label for="category">Category:</label>
                    <select name="category_id" id="category" required>
                        <option value="" selected hidden>Select Category</option>
                        <?php
                        // Output categories as options
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="forms">
                    <label for="food_image">Food Image:</label>
                    <input type="file" name="food_image" id="food_image" required>
                </div>

                <div class="forms">
                    <button type="submit" name="submit">Add Food</button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
