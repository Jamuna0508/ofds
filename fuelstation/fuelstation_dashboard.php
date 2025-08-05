<?php
// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include authentication check if the file exists
$auth_check_path = '../auth_check.php';
if (file_exists($auth_check_path)) {
    include($auth_check_path);
} else {
    // Handle the missing file scenario here, if needed
    die('Auth check file not found');
}

// Function to fetch orders based on fuel station number
function fetchOrdersForFuelStation($fuelStationNumber, $conn) {
    // Prepare SQL statement to select orders for the given fuel station number
    $sql = "SELECT id, name, phone, email, shipping_address, fuel_type, quantity, total_price, payment_method, order_date
            FROM orders
            WHERE fuel_station_number = ?";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $fuelStationNumber);

    $result = $stmt->execute();
    if ($result === false) {
        die("Query failed: " . $stmt->error);
    }

    // Get result set
    $result = $stmt->get_result();

    // Fetch orders into an array
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    // Close statement
    $stmt->close();

    return $orders;
}

// Query to select all fuel stations
$sql = "SELECT * FROM fuel_stations";
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
    echo "table {";
    echo "  width: 100%;";
    echo "  border-collapse: collapse;";
    echo "}";
    echo "table, th, td {";
    echo "  border: 1px solid black;";
    echo "  padding: 8px;";
    echo "}";
    echo "th {";
    echo "  background-color: #f2f2f2;";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<h2>All Fuel Stations</h2>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Fuel Station Name</th>";
    echo "<th>Address</th>";
    echo "<th>City</th>";
    echo "<th>State</th>";
    echo "<th>Pincode</th>";
    echo "<th>Orders</th>"; // New column header for orders
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars(isset($row["fs_name"]) ? $row["fs_name"] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(isset($row["address"]) ? $row["address"] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(isset($row["city"]) ? $row["city"] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(isset($row["state"]) ? $row["state"] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(isset($row["pincode"]) ? $row["pincode"] : 'N/A') . "</td>";

        // Fetch orders for this fuel station number
        $fuelStationNumber = $row['station_id']; // Assuming station_id is the column name in fuel_stations table
        $orders = fetchOrdersForFuelStation($fuelStationNumber, $conn);

        echo "<td>";
        if (count($orders) > 0) {
            echo "<ul>";
            foreach ($orders as $order) {
                echo "<li>Order ID: " . $order['id'] . "</li>";
                // Display other order details as needed
            }
            echo "</ul>";
        } else {
            echo "No orders";
        }
        echo "</td>";

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</body>";
    echo "</html>";
} else {
    echo "No fuel stations found.";
}

// Close connection
$conn->close();
?>
