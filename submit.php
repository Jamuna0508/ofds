<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $message = htmlspecialchars($_POST['message']);

    // Here you can process the form data, like sending an email or saving to a database
    // For demonstration, let's just echo the data

    echo "Thank you, $name, for your message: $message";
} else {
    echo "Form submission error!";
}
?>
