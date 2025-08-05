<?php
session_start();

// Database connection parameters
$servername = "127.0.0.1";
$username_db = "root";
$password_db = ""; // Assuming empty password for local development
$dbname = "fuel_delivery";

// Initialize variables
$username = $password = $confirm_password = $error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input (you should add more robust validation)
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate form data
    if ($password != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create connection
        $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare SQL statement to insert admin data
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['signup_success'] = true;
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: login.php"); // Redirect to login page after successful signup
                exit;
            } else {
                $error = "Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Prepare failed: " . mysqli_error($conn);
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
                <div class="message error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
        </form>
        <div class="message">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
