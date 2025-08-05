<?php
session_start();
include('auth_check.php'); // Ensure user is logged in

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery"); // Adjust DB credentials as needed

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all fuel prices
$sql = "SELECT fuel_id, fuel_type, price_per_litre, last_updated FROM fuel_prices";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$result = $stmt->execute();

if ($result === false) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();

$fuel_prices = array();
while ($row = $result->fetch_assoc()) {
    $fuel_prices[] = $row;
}

$stmt->close();

// Close connection
$conn->close();

// Update fuel price handling
$update_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $fuel_id = $_POST['fuel_id'];
    $new_price = $_POST['new_price'];

    // Update fuel price in database
    $conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $update_sql = "UPDATE fuel_prices SET price_per_litre = ?, last_updated = CURRENT_TIMESTAMP WHERE fuel_id = ?";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("di", $new_price, $fuel_id);
    $result = $stmt->execute();

    if ($result === false) {
        $update_message = "Failed to update fuel price.";
    } else {
        $update_message = "Fuel price updated successfully.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Update Fuel Prices</title>
    <style>
        /* General styling */
        body {
            background-image: url('ad.jpg');
            background-size: cover;
           
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            background-image: url('ad.jpg');
            background-size: cover;
            width: 800px;
            margin: 20px auto; /* Center the container horizontally */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .update-form {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .update-form label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .update-form input[type="number"] {
            width: 200px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .update-form button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-form button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
            border-radius: 4px;
        }

        .success-message {
            margin-top: 10px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard - Update Fuel Prices</h2>

        <?php if (!empty($update_message)): ?>
            <?php if (strpos($update_message, 'successfully') !== false): ?>
                <div class="success-message"><?php echo $update_message; ?></div>
            <?php else: ?>
                <div class="message"><?php echo $update_message; ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <h3>Current Fuel Prices:</h3>
        <table>
            <thead>
                <tr>
                    <th>Fuel ID</th>
                    <th>Fuel Type</th>
                    <th>Price per Litre</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fuel_prices as $fuel): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fuel['fuel_id']); ?></td>
                        <td><?php echo htmlspecialchars($fuel['fuel_type']); ?></td>
                        <td><?php echo htmlspecialchars($fuel['price_per_litre']); ?></td>
                        <td><?php echo htmlspecialchars($fuel['last_updated']); ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="update-form">
                                <input type="hidden" name="fuel_id" value="<?php echo $fuel['fuel_id']; ?>">
                                <label for="new_price">New Price per Litre:</label>
                                <input type="number" id="new_price" name="new_price" step="0.01" required>
                                <button type="submit" name="update">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="nav-links">
           <a href="admin_dashboard.php">Go back to Dashboard</a>
           <a href="add_fuel_station.php">Add Fuel Station</a>
           <a href="add_delivery_agent.php">Add Delivery Agent</a>

        </div>
    </div>
</body>
</html>
