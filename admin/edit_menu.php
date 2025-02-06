<?php
include 'db.php'; // Include the database connection

// Check if food item ID is passed in the URL
if (isset($_GET['id'])) {
    $food_id = $_GET['id'];

    // Fetch food item details from the database
    $sql = "SELECT * FROM food_menu WHERE id = '$food_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $food_item = mysqli_fetch_assoc($result);
    } else {
        echo "Food item not found.";
        exit();
    }
} else {
    echo "Food item ID is required.";
    exit();
}

// Fetch food categories for the select dropdown
$sql_categories = "SELECT * FROM food_categories";
$result_categories = mysqli_query($conn, $sql_categories);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Food Menu</title>
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
                <h2>Edit Food Menu</h2>
            </div>

            <!-- Food Menu Edit Form -->
            <form action="edit_menu.php?id=<?php echo $food_item['id']; ?>" method="POST" enctype="multipart/form-data">
                <div class="forms">
                    <label for="food_name">Food Name:</label>
                    <input type="text" name="food_name" id="food_name" value="<?php echo $food_item['food_name']; ?>" required>
                </div>

                <div class="forms">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" value="<?php echo $food_item['price']; ?>" required>
                </div>

                <div class="forms">
                    <label for="category">Category:</label>
                    <select name="category_id" id="category" required>
                        <option value="">Select Category</option>
                        <?php
                        // Output categories as options
                        while ($category = mysqli_fetch_assoc($result_categories)) {
                            $selected = ($category['id'] == $food_item['category_id']) ? "selected" : "";
                            echo "<option value='" . $category['id'] . "' $selected>" . $category['category_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="forms">
                    <label for="food_image">Food Image:</label>
                    <input type="file" name="food_image" id="food_image">
                    <br>
                    <img src="uploads/<?php echo $food_item['food_image']; ?>" alt="Food Image" width="100">
                </div>

                <div class="forms">
                    <button type="submit" name="submit">Update Food</button>
                </div>
            </form>

            <?php
            // Handle form submission for editing food menu item
            if (isset($_POST['submit'])) {
                $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
                $price = mysqli_real_escape_string($conn, $_POST['price']);
                $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

                // Check if new image is uploaded
                if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] == 0) {
                    $image_name = $_FILES['food_image']['name'];
                    $image_tmp_name = $_FILES['food_image']['tmp_name'];
                    $image_size = $_FILES['food_image']['size'];
                    $image_error = $_FILES['food_image']['error'];

                    // Handle file upload
                    if ($image_error === 0) {
                        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                        $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
                        if (in_array($image_ext, $allowed_exts)) {
                            if ($image_size < 5000000) { // 5MB limit
                                $new_image_name = uniqid('', true) . '.' . $image_ext;
                                $image_upload_path = 'uploads/' . $new_image_name;

                                // Move the uploaded image to the desired folder
                                if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                                    // Delete the old image from the server
                                    unlink('uploads/' . $food_item['food_image']);
                                } else {
                                    echo "Error uploading image.";
                                    exit();
                                }
                            } else {
                                echo "File size is too large.";
                                exit();
                            }
                        } else {
                            echo "Invalid file type. Only JPG, JPEG, PNG, GIF files are allowed.";
                            exit();
                        }
                    } else {
                        echo "Error uploading file.";
                        exit();
                    }
                } else {
                    // Use the existing image if no new one is uploaded
                    $new_image_name = $food_item['food_image'];
                }

                // Update the food menu in the database
                $sql_update = "UPDATE food_menu SET food_name = '$food_name', price = '$price', category_id = '$category_id', food_image = '$new_image_name' WHERE id = '$food_id'";

                if (mysqli_query($conn, $sql_update)) {
                    echo "<script>alert('Food item updated successfully!'); window.location.href='view_menu.php';</script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
            ?>

        </div>
    </div>
</body>
</html>
