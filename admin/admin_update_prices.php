<?php
session_start();
include('auth_check.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fuel_delivery";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable to hold update status message
$update_status = "";

// Update fuel prices in the database if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and process input data
    foreach ($_POST['prices'] as $fuel_type => $price) {
        $fuel_type = sanitize_input($fuel_type);
        $price = sanitize_input($price);
        
        // Update fuel prices in 'fuel_prices' table
        $sql = "UPDATE fuel_prices SET price_per_litre = ? WHERE fuel_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ds", $price, $fuel_type);
        
        if ($stmt->execute() === false) {
            $update_status .= "Error updating record for $fuel_type: " . $stmt->error . "<br>";
        }
    }
    
    if (empty($update_status)) {
        $update_status = "Fuel prices updated successfully.";
    }
}

// Fetch current fuel prices from 'fuel_prices' table
$sql = "SELECT fuel_type, price_per_litre FROM fuel_prices";
$result = $conn->query($sql);

$prices = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prices[$row['fuel_type']] = $row['price_per_litre'];
    }
} else {
    echo "No fuel prices found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Fuel Prices</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .status {
            margin-top: 20px;
            padding: 10px;
            background-color: #e7f3fe;
            border: 1px solid #b3d4fc;
            color: #31708f;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Fuel Prices</h2>
        <?php if (!empty($update_status)): ?>
            <div class="status"><?php echo $update_status; ?></div>
        <?php endif; ?>
        <form action="admin_update_prices.php" method="post">
            <?php foreach ($prices as $fuel_type => $price): ?>
                <div class="form-group">
                    <label for="<?php echo htmlspecialchars($fuel_type); ?>"><?php echo htmlspecialchars($fuel_type); ?> (INR/L):</label>
                    <input type="number" name="prices[<?php echo htmlspecialchars($fuel_type); ?>]" value="<?php echo htmlspecialchars($price); ?>" step="0.01" required>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="Update Prices">
        </form>
        <button onclick="window.location.href='admin_dashboard.php';">Back to Dashboard</button>
    </div>
</body>
</html>
