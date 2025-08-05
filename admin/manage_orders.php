<?php
session_start();
include('auth_check.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Fetch orders from the database
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders
$sql = "SELECT order_id, customer_name, fuel_type, quantity_litres, total_price FROM orders";
$result = $conn->query($sql);

$orders = array();
if ($result !== false) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Fuel Type</th>
                    <th>Quantity (Litres)</th>
                    <th>Total Price (INR)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $order['fuel_type']; ?></td>
                        <td><?php echo $order['quantity_litres']; ?></td>
                        <td><?php echo $order['total_price']; ?></td>
                        <td>
                            <a href="edit_order.php?order_id=<?php echo $order['order_id']; ?>">Edit</a>
                            <a href="delete_order.php?order_id=<?php echo $order['order_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="window.location.href='admin_dashboard.php';">Back to Dashboard</button>
    </div>
</body>
</html>
