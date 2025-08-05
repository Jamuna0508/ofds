<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include authentication check if the file exists
$auth_check_path = '../auth_check.php';

// Check if the file exists and include it
if (file_exists($auth_check_path)) {
    include($auth_check_path);
} else {
    die('Auth check file not found');
}

// Check if station_id is set in session
if (!isset($_SESSION['station_id'])) {
    die("Station ID not set.");
}

$station_id = $_SESSION['station_id'];

// Query to fetch orders for the specific station_id
$sql = "SELECT * FROM orders WHERE station_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Orders for Fuel Station</title>";
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

// Display orders if there are any
if ($result->num_rows > 0) {
    echo "<h2>Orders for Fuel Station ID: $station_id</h2>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>Customer Name</th>";
    echo "<th>Order Date</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No orders found for Fuel Station ID: $station_id</p>";
}

// Close statement and connection
$stmt->close();
$conn->close();

echo "</body>";
echo "</html>";
?>
