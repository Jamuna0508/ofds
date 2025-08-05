<?php
// Database configuration
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Ensure the password is correct for your MySQL server
$dbname = "fuel_delivery";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
