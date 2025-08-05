<?php
// Start output buffering to prevent issues with header redirection
ob_start();

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch orders for a given station_id, excluding delivered orders
function getOrders($conn, $station_id) {
    $sql = "SELECT id, name, phone, shipping_address, fuel_type, quantity, total_price, payment_method, order_date 
            FROM orders 
            WHERE station_id = ? AND (delivery_status IS NULL OR delivery_status != 'Delivered')";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $station_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

// Function to fetch delivery agents with address based on fuel station address condition
function getDeliveryAgents($conn, $fuel_station_address) {
    // Adjust SQL query to filter delivery agents based on fuel station address condition
    $sql = "SELECT agent_id, agent_name, address 
            FROM delivery_agents 
            WHERE address LIKE ?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind the parameter using a variable to avoid passing by reference issue
    $param = "%$fuel_station_address%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();

    $agents = [];
    while ($row = $result->fetch_assoc()) {
        $agents[$row['agent_id']] = [
            'agent_name' => $row['agent_name'],
            'address' => $row['address']
        ];
    }
    return $agents;
}

// Check POST Data: Ensure order_id and agent_id are set in the POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['agent_id'])) {
    $order_id = $_POST['order_id'];
    $agent_id = $_POST['agent_id'];

    // Perform the assignment of order to agent
    $sql = "UPDATE orders SET agent_id = ?, delivery_status = 'Delivered' WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("ii", $agent_id, $order_id);
    if ($stmt->execute()) {
        // Redirect to customer_dashboard.php with a success message
        header("Location:customer_dashboard.php?message=Order+ID+$order_id+has+been+successfully+assigned+to+agent+ID+$agent_id+and+marked+as+delivered.");
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
}

// Query to select all fuel stations
$sql = "SELECT station_id, fs_name, address, city, state, pincode FROM fuel_stations";
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>All Fuel Stations</title>";
    echo "<style>";
    echo "/* General styling */";
    echo "body {";
    echo "    background-image: url('bk.jpg');";
    echo "    background-size: cover;";
    echo "    font-family: Arial, sans-serif;";
    echo "    background-color: #f0f0f0;";
    echo "    margin: 0;";
    echo "    padding: 0;";
    echo "}";
    echo ".container {";
    echo "    margin: 20px auto;";
    echo "    padding: 20px;";
    echo "    background-color: #fff;";
    echo "    background-image: url('bk.jpg');";
    echo "    background-size: cover;";
    echo "    border-radius: 8px;";
    echo "    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo "h2 {";
    echo "    color: #333;";
    echo "    margin-bottom: 20px;";
    echo "}";
    echo "table {";
    echo "    width: 100%;";
    echo "    border-collapse: collapse;";
    echo "    margin-bottom: 20px;";
    echo "}";
    echo "th, td {";
    echo "    padding: 10px;";
    echo "    text-align: left;";
    echo "    border-bottom: 1px solid #ddd;";
    echo "}";
    echo "th {";
    echo "    background-color: #f4f4f4;";
    echo "}";
    echo "tr:hover {";
    echo "    background-color: #f1f1f1;";
    echo "}";
    echo ".nav-links {";
    echo "    margin-top: 20px;";
    echo "}";
    echo ".nav-links a {";
    echo "    display: inline-block;";
    echo "    margin-right: 10px;";
    echo "    text-decoration: none;";
    echo "    padding: 8px 12px;";
    echo "    background-color: #007bff;";
    echo "    color: white;";
    echo "    border-radius: 4px;";
    echo "    transition: background-color 0.3s ease;";
    echo "}";
    echo ".nav-links a:hover {";
    echo "    background-color: #0056b3;";
    echo "}";
    echo "</style>";

    echo "</head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<h2>All Fuel Stations</h2>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Station ID</th>";
    echo "<th>Name</th>";
    echo "<th>Address</th>";
    echo "<th>City</th>";
    echo "<th>State</th>";
    echo "<th>Pincode</th>";
    echo "<th>Orders</th>"; // Column for viewing orders
    echo "<th>Assign Orders</th>"; // New column for assigning orders
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["station_id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["fs_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["city"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["state"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["pincode"]) . "</td>";

        // Retrieve and display orders count and details for the current station_id
        $station_id = $row['station_id'];
        $orders = getOrders($conn, $station_id);

        echo "<td>";
        echo "<ul>";
        foreach ($orders as $order) {
            echo "<li>";
            echo "Order ID: " . htmlspecialchars($order['id']) . ", ";
            echo "Customer: " . htmlspecialchars($order['name']) . ", ";
            echo "Phone: " . htmlspecialchars($order['phone']) . ", ";
            echo "Address: " . htmlspecialchars($order['shipping_address']) . ", ";
            echo "Fuel Type: " . htmlspecialchars($order['fuel_type']) . ", ";
            echo "Quantity: " . htmlspecialchars($order['quantity']) . ", ";
            echo "Total Price: $" . htmlspecialchars($order['total_price']) . ", ";
            echo "Payment Method: " . htmlspecialchars($order['payment_method']) . ", ";
            echo "Order Date: " . htmlspecialchars($order['order_date']);
            echo "</li>";
        }
        echo "</ul>";
        echo "</td>";

        // Display dropdown for assigning orders to delivery agents with addresses
        $fuel_station_address = $row['address'];
        $delivery_agents = getDeliveryAgents($conn, $fuel_station_address);
        echo "<td>";
        echo "<form action='' method='POST'>"; // Same page action
        echo "<input type='hidden' name='station_id' value='$station_id'>";
        echo "<select name='order_id'>";
        echo "<option value=''>Select Order</option>";
        foreach ($orders as $order) {
            echo "<option value='" . htmlspecialchars($order['id']) . "'>";
            echo "Order ID: " . htmlspecialchars($order['id']) . " - Customer: " . htmlspecialchars($order['name']);
            echo "</option>";
        }
        echo "</select>";
        echo "<br><br>";
        echo "<select name='agent_id'>";
        echo "<option value=''>Select Agent</option>";
        foreach ($delivery_agents as $agent_id => $agent_info) {
            echo "<option value='$agent_id'>";
            echo htmlspecialchars($agent_info['agent_name']) . " - " . htmlspecialchars($agent_info['address']);
            echo "</option>";
        }
        echo "</select>";
        echo "<button type='submit'>Assign Order</button>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
} else {
    echo "No fuel stations found.";
}

// Close connection
$conn->close();

// End output buffering and send output
ob_end_flush();
?>
