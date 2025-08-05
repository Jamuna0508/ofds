<?php
session_start();
include('auth_check.php'); // Ensure admin is logged in

// Check if order_id is provided in URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details from database (example)
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fuel_delivery";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $order_id, $customer_name, $fuel_type, $quantity, $total_price);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order Details</title>
    <!-- CSS styles -->
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Details</h2>
        <table>
            <tr>
                <td><strong>Order ID:</strong></td>
                <td><?php echo $order_id; ?></td>
            </tr>
            <tr>
                <td><strong>Customer Name:</strong></td>
                <td><?php echo $customer_name; ?></td>
            </tr>
            <tr>
                <td><strong>Fuel Type:</strong></td>
                <td><?php echo $fuel_type; ?></td>
            </tr>
            <tr>
                <td><strong>Quantity (Litres):</strong></td>
                <td><?php echo $quantity; ?></td>
            </tr>
            <tr>
                <td><strong>Total Price (INR):</strong></td>
                <td><?php echo $total_price; ?></td>
            </tr>
            <!-- Add more details as needed -->
        </table>
        <div class="message">
            <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
            <p><a href="logout.php">Logout</a></p>
        </div>
    </div>
</body>
</html>
