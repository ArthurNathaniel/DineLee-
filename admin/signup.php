<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

        // Check if email or phone already exists
        $check = "SELECT * FROM admins WHERE email='$email' OR phone='$phone'";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email or Phone already exists!');</script>";
        } else {
            // Insert admin into the database
            $sql = "INSERT INTO admins (full_name, phone, email, password) VALUES ('$full_name', '$phone', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Admin registered successfully!'); window.location.href='login.php';</script>";
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
    <title>Dinelee - Signup</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <div class="auth_all">
        <div class="auth_box">
            <h2>Admin Signup</h2>
            <form method="POST">
            <div class="forms">
            <label>Full Name:</label>
            <input type="text" name="full_name" required>
            </div>

              <div class="forms">
              <label>Phone:</label>
                <input type="text" name="phone" required>

              </div>
               <div class="forms">
               <label>Email:</label>
               <input type="email" name="email" required>
               </div>

           <div class="forms">
           <label>Password:</label>
           <input type="password" id="password" name="password" required>
           </div>

             <div class="forms">
             <label>Confirm Password:</label>
             <input type="password" id="confirm_password" name="confirm_password" required>
             </div>

                <div class="form">
                <input type="checkbox" onclick="togglePassword()"> Show Password
                </div>
<div class="forms">
    
<button type="submit">Signup</button>
</div>
            </form>
        </div>
    </div>
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
</body>

</html>