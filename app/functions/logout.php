<?php
function logoutUser($conn)
{
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        // Update the logged_in status to 0 in the database
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $stmt = $conn->prepare("UPDATE users SET logged_in = 0 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
        }

        // Destroy the session and redirect to the login page
        session_destroy();
        header("Location: " . BASE_URL);
        exit; // Prevent further execution
    }
}
?>
