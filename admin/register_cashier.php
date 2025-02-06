<?php
session_start();
include 'db.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if fields are empty
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Check if cashier already exists
        $check_sql = "SELECT * FROM cashiers WHERE email = '$email' OR phone = '$phone'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Cashier already exists with this email or phone number!');</script>";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new cashier
            $sql = "INSERT INTO cashiers (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Cashier registered successfully!'); window.location.href='view_cashiers.php';</script>";
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
    <title>Register Cashier</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <script>
        function togglePassword(fieldId, toggleId) {
            let field = document.getElementById(fieldId);
            let toggle = document.getElementById(toggleId);
            if (field.type === "password") {
                field.type = "text";
                toggle.textContent = "🙈";
            } else {
                field.type = "password";
                toggle.textContent = "👁️";
            }
        }
    </script>
</head>

<body>
<?php include 'sidebar.php'; ?>
<div class="auth_all">
    <div class="auth_box">
        <h2>Register Cashier</h2>
        <form method="POST">
            <div class="forms">
                <label>Full Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="forms">
                <label>Phone Number:</label>
                <input type="text" name="phone" required>
            </div>

            <div class="forms">
                <label>Email Address:</label>
                <input type="email" name="email" required>
            </div>

            <div class="forms">
                <label>Password:</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" required>
                    <span id="togglePassword" style="position: absolute; right: 10px; top: 10px; cursor: pointer;" onclick="togglePassword('password', 'togglePassword')">👁️</span>
                </div>
            </div>

            <div class="forms">
                <label>Confirm Password:</label>
                <div style="position: relative;">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 10px; cursor: pointer;" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')">👁️</span>
                </div>
            </div>

            <div class="forms">
                <button type="submit">Register Cashier</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
