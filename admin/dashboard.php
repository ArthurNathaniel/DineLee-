<?php
session_start(); // Start the session to access admin details

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Get the admin's full name from session
$full_name = $_SESSION['full_name'];

// Get the current date and time
date_default_timezone_set('Africa/Accra'); // Set the timezone for your location
$current_date_time = date('l, F j, Y h:i A'); // Format: Day, Month date, Year Time (AM/PM)

// Determine the time of day and set the greeting message
$hour = date('H'); // Get the current hour in 24-hour format

// Greet the admin based on the time of day in Twi
if ($hour >= 5 && $hour < 12) {
    $greeting = "Mema wo akye, $full_name!"; // Good morning
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Mema wo aha, $full_name!"; // Good afternoon
} else {
    $greeting = "Mema wo adwo, $full_name!"; // Good evening
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include '../cdn.php'?>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<?php include 'sidebar.php'?>
    <h1><?php echo $greeting; ?></h1> <!-- Greet the admin -->
    <p>Today is <?php echo $current_date_time; ?></p> <!-- Show the current date and time -->

    <h3>Admin Dashboard</h3>
    <p>Manage your restaurant system from here.</p>
    <!-- Add more features and links for the dashboard as needed -->
</body>
</html>
