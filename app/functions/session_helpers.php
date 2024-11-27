<?php
session_start();
define('BASE_URL', 'your_base_url'); // Replace with your base URL.

function redirectIfNotLoggedIn()
{
    // Check if the user is inactive for 5 minutes (300 seconds)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
        // Mark user as logged out in the database
        setUserLoggedInStatus($_SESSION['email'], 0);

        // Destroy session and redirect to login page
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/");
        exit;
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();

    // Check if the user is logged in
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/");
        exit;
    }
}

function redirectIfLoggedIn()
{
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/client_list");
        exit;
    }
}

// Function to update the logged_in status in the database
function setUserLoggedInStatus($email, $status)
{
    // Connect to the database
    $db = new mysqli('localhost', 'username', 'password', 'database_name'); // Replace with your database credentials

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Update the logged_in status
    $stmt = $db->prepare("UPDATE users SET logged_in = ? WHERE email = ?");
    $stmt->bind_param('is', $status, $email);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
?>
