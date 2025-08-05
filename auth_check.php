$auth_check_path = '../auth_check.php';
echo "Attempting to include: $auth_check_path<br>";

if (file_exists($auth_check_path)) {
    echo "File exists: $auth_check_path<br>";
    include($auth_check_path);
} else {
    die('Auth check file not found');
}
