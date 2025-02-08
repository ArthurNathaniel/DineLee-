<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch all expenses
$query = "SELECT * FROM expenses ORDER BY date DESC, id DESC";
$result = mysqli_query($conn, $query);

// Calculate total expenses
$totalQuery = "SELECT SUM(amount) AS total FROM expenses";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalExpenses = $totalRow['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <?php include '../cdn.php'; ?>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="auth_alls">
        <div class="auth_box">
            <h2>Recorded Expenses</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount (GH₵)</th>
                        </tr>
                    </thead>
                    <tbody>
                      

                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$count}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['date']}</td>
                                    <td>{$row['description']}</td>
                                    <td>GH₵ " . number_format($row['amount'], 2) . "</td>
                                </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; color:red;'>No expenses recorded yet.</td></tr>";
                        }
                        ?>
                          <!-- Display Total Expenses First -->
                          <tr style="background-color: #f8d7da; font-weight: bold; color: #721c24;">
                            <td colspan="4" style="text-align: right;">Total Expenses:</td>
                            <td>GH₵ <?php echo number_format($totalExpenses, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
