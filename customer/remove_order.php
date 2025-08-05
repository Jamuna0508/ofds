<?php
session_start();
include('auth_check.php'); // Ensure user is logged in

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Check if order_id is provided and is a valid number
if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Database connection
    $conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery"); // Adjust DB credentials as needed

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete order
    $sql = "DELETE FROM orders WHERE id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("i", $order_id);

    // Execute the statement
    $result = $stmt->execute();

    // Check execution result
    if ($result === false) {
        die("Query failed: " . $stmt->error);
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Redirect back to customer dashboard with success message
    $_SESSION['delivery_message'] = "Order removed successfully.";
    header("Location: customer_dashboard.php");
    exit();
} else {
    // Invalid or missing order_id
    $_SESSION['delivery_message'] = "Failed to remove order. Invalid order ID.";
    header("Location: customer_dashboard.php");
    exit();
}
?>
