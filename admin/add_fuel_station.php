<?php
// Ensure session is started properly
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('auth_check.php'); // Ensure admin is logged in

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery"); // Adjust DB credentials as needed

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form data
$fs_name = $address = $city = $state = $pincode = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input data
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Validate and sanitize input
    $fs_name = sanitize_input($_POST["fs_name"]);
    $address = sanitize_input($_POST["address"]);
    $city = sanitize_input($_POST["city"]);
    $state = sanitize_input($_POST["state"]);
    $pincode = sanitize_input($_POST["pincode"]);

    // Prepare and execute SQL insert statement
    $sql = "INSERT INTO fuel_stations (fs_name, address, city, state, pincode) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("sssss", $fs_name, $address, $city, $state, $pincode);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: view_fuel_stations.php"); // Redirect to view fuel stations page after successful insertion
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fuel Station</title>
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

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="number"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Fuel Station</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="fs_name" name="fs_name" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="pincode">Pincode:</label>
            <input type="number" id="pincode" name="pincode" required>

            <input type="submit" value="Add Fuel Station">
        </form>
    </div>
</body>
</html>
