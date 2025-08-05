<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth check to ensure user is logged in
include('auth_check.php');

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery"); // Adjust DB credentials as needed

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch delivery agents from database
$sql_agents = "SELECT * FROM delivery_agents";
$result_agents = $conn->query($sql_agents);
$agents = [];

if ($result_agents->num_rows > 0) {
    while ($row = $result_agents->fetch_assoc()) {
        $agents[] = $row;
    }
} else {
    $no_agents_message = "No delivery agents found.";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Delivery Agents</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>View Delivery Agents</h2>
        <?php if (!empty($no_agents_message)): ?>
            <p><?php echo $no_agents_message; ?></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Agent ID</th>
                        <th>Agent Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agents as $agent): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($agent['agent_id']); ?></td>
                            <td><?php echo htmlspecialchars($agent['agent_name']); ?></td>
                            <td><?php echo htmlspecialchars($agent['phone']); ?></td>
                            <td><?php echo htmlspecialchars($agent['address']); ?></td>
                            <td><?php echo htmlspecialchars($agent['availability']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
