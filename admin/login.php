<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); // This can be either phone or email
    $password = $_POST['password'];

    // Check if the identifier is email or phone
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM admins WHERE email='$identifier'";
    } else {
        $sql = "SELECT * FROM admins WHERE phone='$identifier'";
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Start the session and store admin data
            session_start();
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            header('Location: dashboard.php'); // Redirect to the dashboard
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email or phone number');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <div class="auth_all">
        <div class="auth_box">
            <div class="logo"></div>
        <div class="title">
        <h2>Admin Login</h2>
        </div>
            <form method="POST">
                <div class="forms">
                    <label>Email/Phone:</label>
                    <input type="text" placeholder="Enter your email or phone number" name="identifier" required>
                </div>

                <div class="forms">
                    <label>Password:</label>
                    <input type="password" placeholder="Enter your password" id="password" name="password" required>
                </div>
<br>
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
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</body>

</html>