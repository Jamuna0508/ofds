echo "<pre>";
print_r($_POST);
echo "</pre>";
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if order_id and agent_id are set in the POST data
if (isset($_POST['order_id']) && isset($_POST['agent_id'])) {
    $order_id = $_POST['order_id'];
    $agent_id = $_POST['agent_id'];

    // Update the order with the assigned agent and mark as delivered
    $sql = "UPDATE orders SET agent_id = ?, delivery_status = 'Delivered' WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("ii", $agent_id, $order_id);
    if ($stmt->execute()) {
        // Redirect to customer_dashboard.php with a success message
        header("Location: customer_dashboard.php?message=Order+ID+$order_id+has+been+successfully+assigned+to+agent+ID+$agent_id+and+marked+as+delivered.");
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
} else {
    echo "Order ID and Agent ID are required.";
}

// Close connection
$conn->close();
?>
