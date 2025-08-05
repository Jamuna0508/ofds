<?php
session_start();

// Database connection parameters
$servername = "127.0.0.1";
$username_db = "root";
$password_db = "";
$dbname = "fuel_delivery";

// Initialize variables
$username = $password = $error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input (you should add more validation)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create connection
    $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL statement to fetch admin data
    $sql = "SELECT username, password FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $db_username, $db_password);
            mysqli_stmt_fetch($stmt);

            // Verify password
            if ($db_username && password_verify($password, $db_password)) {
                // Successful login, set session variables
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_loggedin'] = true;
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Error executing query: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Prepare statement failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .message {
            margin-top: 10px;
            text-align: center;
        }
        .message a {
            color: #4CAF50;
            text-decoration: none;
        }
        .message a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
            <?php if (!empty($error)): ?>
                <div class="message error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
        </form>
        <div class="message">
            <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        </div>
    </div>
</body>
</html>
