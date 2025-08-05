<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Optionally, you can include additional checks such as role-based access control
// Example: Restrict access based on user roles
/*
if ($_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}
*/

// Optionally, you can include other session-related checks or setups as needed

?>
