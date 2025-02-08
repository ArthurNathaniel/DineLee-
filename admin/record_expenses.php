<?php
session_start();
include 'db.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $date = date('Y-m-d'); // Automatically selects today's date
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = floatval($_POST['amount']);

    // Check if the same expense (name, description, amount) already exists
    $check_query = "SELECT * FROM expenses WHERE name='$name' AND description='$description' AND amount='$amount' AND date='$date'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This expense has already been recorded today!'); window.location.href='record_expenses.php';</script>";
        exit();
    }

    // Insert the expense if it doesn't exist
    $query = "INSERT INTO expenses (name, date, description, amount) VALUES ('$name', '$date', '$description', '$amount')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Expense recorded successfully!'); window.location.href='record_expenses.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='record_expenses.php';</script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Expenses</title>
    <?php include '../cdn.php' ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="auth_all">
        <div class="auth_box">
            <h2>Record an Expense</h2>

            <form method="POST">
                <div class="forms">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>
                </div>

                <div class="forms">
                    <label for="date">Date:</label>
                    <input type="text" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="forms">
                    <label for="description">Description:</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="forms">
                    <label for="amount">Amount (GH₵):</label>
                    <input type="number" name="amount" step="0.01" required>
                </div>

                <div class="forms">
                    <button type="submit">Record Expense</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
