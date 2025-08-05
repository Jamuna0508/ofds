<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fuel_delivery";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare SQL statement to fetch orders
$sql = "SELECT id,name, phone, email, shipping_address, order_details FROM orders";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Check if there are any orders
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Orders</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Shipping Address</th><th>Order Details</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['shipping_address'] . "</td>";
            echo "<td>" . $row['order_details'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No orders found.";
    }
} else {
    echo "Error fetching orders: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
