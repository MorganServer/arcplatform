<?php 

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // Hash the password using MD5

    // Query to check the user's credentials
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, fetch their details
        $user = $result->fetch_assoc();

        // Update the user's logged_in status
        $userId = $user['user_id'];
        $updateSql = "UPDATE users SET logged_in = 1 WHERE user_id = $userId";
        if ($conn->query($updateSql) === TRUE) {
            // Successfully updated logged_in status

            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['first_name'] . " " . $user["last_name"];

            // Redirect to a dashboard or homepage
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        } else {
            $error = "Failed to update login status. Please try again.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}

?>