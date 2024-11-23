<?php
// LOGOUT
function logoutUser($conn)
{
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        // Update the logged_in status to 0 in the database
        $email = $_SESSION['email']; // Assuming 'username' is the column where the username is stored in your users table
        $sql = "UPDATE users SET logged_in='0' WHERE email='$email'";
        mysqli_query($conn, $sql); // <-- Added semicolon here
        
        // Destroy the session and redirect to the login page
        session_destroy();
        header("Location: " . BASE_URL);
        exit; // Prevent further execution
    }
}

// END LOGOUT
?>