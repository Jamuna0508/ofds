<?php
session_start();

// Database connection parameters
$servername = "127.0.0.1";  // or "localhost" if MySQL server is on the same machine
$db_username = "root";      // MySQL username
$db_password = "";          // MySQL password
$dbname = "fuel_delivery";  // Name of your database

// Initialize variables
$username = $password = $error = '';

// Check if admin login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate username and password inputs (add more validation if needed)
    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        // Create connection
        $conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare SQL statement to fetch admin data
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $db_id, $db_username, $db_password);
                mysqli_stmt_fetch($stmt);

                // Verify password
                if ($db_username && password_verify($password, $db_password)) {
                    // Successful login, set session variables
                    $_SESSION['id'] = $db_id;
                    $_SESSION['admin_username'] = $db_username;
                    $_SESSION['admin_loggedin'] = true;
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header("Location: admin_dashboard.php");
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
                <div class="message">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
        </form>
        <div class="message">
            <p>Don't have an account? <a href="admin_signup.php">Sign up here</a>.</p>
        </div>
    </div>
</body>
</html>
