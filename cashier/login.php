<?php
session_start();
include 'db.php'; // Database connection

// If cashier is already logged in, redirect to dashboard
if (isset($_SESSION['cashier_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = ""; // Variable to store login error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); // Phone or Email
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if user exists
    $sql = "SELECT * FROM cashiers WHERE (email = '$identifier' OR phone = '$identifier') LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) { // Verify password
            if ($row['status'] == 'disabled') {
                $error = "Your account is disabled. Contact admin.";
            } else {
                $_SESSION['cashier_id'] = $row['id'];
                $_SESSION['cashier_name'] = $row['name'];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Login</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth_all">
        <div class="auth_box">
            <div class="logo"></div>
         <div class="title">
         <h2>Cashier Login</h2>
         </div>
            <?php if ($error): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="forms">
                    <label>Email or Phone:</label>
                    <input type="text" placeholder="Enter your email or phone number" name="identifier" required>
                </div>
                <div class="forms">
                    <label>Password:</label>
                    <input type="password" placeholder="Enter your password" name="password" id="password" required>
                   
                </div>
                <div class="form">
                <input type="checkbox" onclick="togglePassword()"> Show Password
                </div>
                <div class="forms">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            var password = document.getElementById("password");
            password.type = password.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
