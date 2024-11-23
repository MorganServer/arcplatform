<?php
require_once "../database/connection.php";
require_once "../../path.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Update the logged_in status to 0
    $updateSql = "UPDATE users SET logged_in = 0 WHERE user_id = $userId";
    if ($conn->query($updateSql) === TRUE) {
        // Destroy the session
        session_unset();
        session_destroy();

        // Redirect to the login page
        header("Location: " . BASE_URL . "/");
        exit();
    } else {
        echo "Error updating logout status: " . $conn->error;
    }
} else {
    // If no user is logged in, redirect to login page
    header("Location: " . BASE_URL . "/");
    exit();
}
?>
