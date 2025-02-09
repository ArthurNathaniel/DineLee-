<?php
session_start();
include 'db.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO managers (name, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        if ($stmt->execute()) {
            echo "<script>alert('Manager registered successfully!');</script>";
        } else {
            echo "<script>alert('Error registering manager.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Manager</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
    <script>
        function togglePassword() {
            var password = document.getElementById("password");
            var confirmPassword = document.getElementById("confirm_password");
            if (password.type === "password") {
                password.type = "text";
                confirmPassword.type = "text";
            } else {
                password.type = "password";
                confirmPassword.type = "password";
            }
        }
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="auth_all">
        <div class="auth_box">
            <h2>Register Manager</h2>
            <form method="POST">
                <div class="forms">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="forms">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="forms">
                    <label>Phone:</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="forms">
                    <label>Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="forms">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <div class="form">
                    <input type="checkbox" onclick="togglePassword()"> Show Password
                </div>
                <div class="forms">
                    <button type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
