<?php
// LOGOUT
function logoutUser($conn)
{
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        if (isset($_SESSION['email'])) {
            // Update the logged_in status to 0 in the database
            $email = $conn->real_escape_string($_SESSION['email']); // Use real_escape_string to sanitize input
            $sql = "UPDATE users SET logged_in = 0 WHERE email = '$email'";
            
            if (mysqli_query($conn, $sql)) {
                // Logout successful
                session_unset(); // Clear all session variables
                session_destroy(); // Destroy the session
                header("Location: index.php"); // Redirect to login page
                exit; // Stop further execution
            } else {
                // Handle query failure
                echo "Error logging out: " . mysqli_error($conn);
            }
        } else {
            // Handle missing session email (user likely already logged out)
            header("Location: index.php");
            exit;
        }
    }
}
?>
