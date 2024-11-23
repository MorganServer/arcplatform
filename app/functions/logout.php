<?php
// LOGOUT
function logoutUser($conn)
{
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        if (isset($_SESSION['email'])) {
            // Sanitize email input
            $email = $conn->real_escape_string($_SESSION['email']);
            
            // Update the logged_in status in the database
            $sql = "UPDATE users SET logged_in = 0 WHERE email = '$email'";
            
            if ($conn->query($sql)) {
                // Clear session data
                session_unset();
                session_destroy();
                
                // Redirect to login page
                header("Location: " . BASE_URL . "/");
                exit;
            } else {
                // Log error for debugging purposes
                error_log("Logout Error: " . $conn->error);
                echo "Error logging out.";
            }
        } else {
            // Redirect if no session email exists
            header("Location: " . BASE_URL . "/");
            exit;
        }
    }
}
?>
