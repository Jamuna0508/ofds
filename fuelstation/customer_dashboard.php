<?php
session_start();
include('auth_check.php'); // Ensure user is logged in

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Display delivery success message if set
$delivery_message = isset($_SESSION['delivery_message']) ? $_SESSION['delivery_message'] : "";
unset($_SESSION['delivery_message']); // Clear the session variable after displaying once

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery"); // Adjust DB credentials as needed

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders for the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT id, name, phone, email, shipping_address, fuel_type, quantity, total_price, payment_method, order_date, delivery_status,payment_status
        FROM orders WHERE name = ?";
        
// Prepare the statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind the username parameter
$stmt->bind_param("s", $username);

// Execute the statement
$result = $stmt->execute();

// Check if execution was successful
if ($result === false) {
    die("Query failed: " . $stmt->error);
}

// Get result set
$result = $stmt->get_result();

// Fetch results into an array
$orders = array();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        /* General styling */
        body {
            background-image: url('bk.jpg');
            background-size: cover;
            width: 380px;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px auto; /* Center the container horizontally */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-image: url('bk.jpg');
            background-size: cover;
            width: 1500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .nav-links {
            margin-top: 20px;
            text-align: center;
        }

        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #0056b3;
        }

        /* Box styling for each order */
        .order-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-box h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .order-box table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-box table th, .order-box table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .order-box table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>!</h2>

        <?php if (!empty($delivery_message)): ?>
            <div class="success-message">
                <?php echo $delivery_message; ?>
            </div>
        <?php endif; ?>

        <h3>Your Orders:</h3>
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Shipping Address</th>
                        <th>Fuel Type</th>
                        <th>Quantity (Litres)</th>
                        <th>Total Price (INR)</th>
                        <th>Payment Method</th>
                        <th>Order Date</th>
                        <th>Delivery Status</th>
                        <th>payment Status</th>
                        <th>Action</th> <!-- Added Action column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td><?php echo $order['phone']; ?></td>
                            <td><?php echo $order['email']; ?></td>
                            <td><?php echo $order['shipping_address']; ?></td>
                            <td><?php echo $order['fuel_type']; ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo $order['total_price']; ?></td>
                            <td><?php echo $order['payment_method']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['delivery_status']; ?></td>
                            <td><?php echo $order['payment_status']; ?></td>
                            <td>
                                <form action="remove_order.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">
                <p>No orders found.</p>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="customer_order_form.php">Place a new order</a>
            <a href="../index.html">Go to home</a>
        </div>
    </div>
</body>
</html>
