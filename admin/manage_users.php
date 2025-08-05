<?php
session_start();
include('auth_check.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Fetch users from the database
$conn = new mysqli("127.0.0.1", "root", "", "fuel_delivery");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$sql = "SELECT id, username FROM users";
$result = $conn->query($sql);

$users = array();
if ($result !== false) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?php echo $user['id']; ?>">Edit</a>
                            <a href="delete_user.php?user_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="window.location.href='admin_dashboard.php';">Back to Dashboard</button>
    </div>
</body>
</html>
