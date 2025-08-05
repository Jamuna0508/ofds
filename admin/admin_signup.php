<?php
// Start session (if not already started)
session_start();

// Database connection parameters
$servername = "127.0.0.1";  // or "localhost" if MySQL server is on the same machine
$db_username = "root";      // MySQL username
$db_password = "";          // MySQL password
$dbname = "fuel_delivery";  // Name of your database

// Initialize variables
$username = $password = $confirm_password = $error = '';

// Check if signup form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs (you should add more validation)
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate password and confirm password match
    if ($password != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create connection
        $conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare SQL statement to insert new admin
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                // Successful signup
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: admin_login.php"); // Redirect to login page after signup
                exit;
            } else {
                $error = "Error executing query: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Prepare statement failed: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
     <style>
        /* General styling */
        body {
            background-image: url('bk.jpg');
            background-size: cover;
           
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            
            width: 380px;
            margin: 20px auto; /* Center the container horizontally */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            color: #ff0000;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Signup</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <input type="submit" value="Sign Up">
            <?php if (!empty($error)): ?>
                <div class="message">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
        </form>
        <div class="message">
            <p>Already have an account? <a href="admin_login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
