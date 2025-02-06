<?php
include 'db.php';

if (isset($_GET['query'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['query']);

    $sql = "SELECT * FROM food_menu WHERE food_name LIKE '%$search_query%'";
    $result = mysqli_query($conn, $sql);
    $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($foods);
}
?>
