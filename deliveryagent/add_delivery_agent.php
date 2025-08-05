<?php
// Start session (assuming auth_check.php handles session start)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth_check.php only if it exists
$auth_check_file = __DIR__ . '/auth_check.php'; // Adjust path if needed

if (file_exists($auth_check_file)) {
    include($auth_check_file);
} else {
    die("Error: auth_check.php not found.");
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare data from form
    $agent_id = $_POST['agent_id'];
    $agent_name = $_POST['agent_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $availability = $_POST['availability'];

    // SQL insert statement
    $sql = "INSERT INTO delivery_agents (agent_id, agent_name, phone, address, availability)
            VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("issss", $agent_id, $agent_name, $phone, $address, $availability);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Delivery agent added successfully.";
    } else {
        $message = "Error adding delivery agent: " . $stmt->error;
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Delivery Agent</title>
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type=text], input[type=number] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Delivery Agent</h2>
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="agent_id">Agent ID:</label>
                <input type="number" id="agent_id" name="agent_id" required>
            </div>
            <div class="form-group">
                <label for="agent_name">Agent Name:</label>
                <input type="text" id="agent_name" name="agent_name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability:</label>
                <input type="text" id="availability" name="availability" required>
            </div>
            <button type="submit">Add Agent</button>
        </form>
    </div>
</body>
</html>
