<?php
include 'db.php'; // Include the database connection

// Check if food item ID is passed in the URL
if (isset($_GET['id'])) {
    $food_id = $_GET['id'];

    // Fetch food item details from the database to get the image file name
    $sql = "SELECT * FROM food_menu WHERE id = '$food_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $food_item = mysqli_fetch_assoc($result);

        // Get the food image file name
        $food_image = $food_item['food_image'];

        // Delete the food item from the database
        $sql_delete = "DELETE FROM food_menu WHERE id = '$food_id'";

        if (mysqli_query($conn, $sql_delete)) {
            // Delete the associated image file from the uploads folder
            $image_path = 'uploads/' . $food_image;
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
            echo "<script>alert('Food item deleted successfully!'); window.location.href='view_menu.php';</script>";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "Food item not found.";
    }
} else {
    echo "Food item ID is required.";
}
?>
