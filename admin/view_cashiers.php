<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Toggle cashier status (Disable or Enable)
if (isset($_GET['toggle_id'])) {
    $cashier_id = $_GET['toggle_id'];
    $new_status = $_GET['status'] == 'active' ? 'disabled' : 'active';

    $sql = "UPDATE cashiers SET status = '$new_status' WHERE id = $cashier_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Cashier status updated successfully!'); window.location.href='view_cashiers.php';</script>";
    } else {
        echo "<script>alert('Error updating status.');</script>";
    }
}

// Fetch all cashiers
$sql = "SELECT * FROM cashiers";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cashiers</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="auth_alls">
    <div class="auth_box">
        <h2>View Cashiers</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="view_cashiers.php?toggle_id=<?= $row['id'] ?>&status=<?= $row['status'] ?>" 
                        onclick="return confirm('Are you sure you want to change this cashier\'s status?');">
                        <?= $row['status'] == 'active' ? 'Disable' : 'Enable' ?>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
