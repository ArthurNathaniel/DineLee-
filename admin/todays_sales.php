<?php
// Include the database connection
include('db.php');

// Default to today if no date is provided
$date_filter = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

// Query to get sales for a specific day
$query = "
  SELECT
    c.name AS cashier_name,
    COUNT(o.order_id) AS total_orders,
    SUM(o.total_amount) AS total_sales
  FROM
    cashiers c
  JOIN
    orders o ON c.id = o.cashier_id
  WHERE
    DATE(o.order_date) = '$date_filter'
  GROUP BY
    c.id;
";

// Execute the query
$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales by Cashier for <?php echo $date_filter; ?></title>
</head>
<body>
    <h1>Sales by Cashier for <?php echo $date_filter; ?></h1>

    <!-- Form to filter sales by date -->
    <form method="POST">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" value="<?php echo $date_filter; ?>" required>
        <button type="submit">Filter</button>
    </form>

    <!-- Table to display sales for the selected date -->
    <table border="1">
        <thead>
            <tr>
                <th>Cashier Name</th>
                <th>Total Orders</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the result set and display the sales
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['cashier_name'] . "</td>";
                echo "<td>" . $row['total_orders'] . "</td>";
                echo "<td>" . number_format($row['total_sales'], 2) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
