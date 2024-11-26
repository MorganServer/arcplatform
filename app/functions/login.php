<?php
session_start(); // Ensure the session is started

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the input data safely
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Keep password in plain form for verification

    // Validate the email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare the SQL statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // Bind the email parameter

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, fetch their details
            $user = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Password matches, update the user's logged_in status
                $userId = $user['user_id'];
                $updateSql = "UPDATE users SET logged_in = 1 WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("i", $userId);
                $updateStmt->execute();

                if ($updateStmt->affected_rows > 0) {
                    // Successfully updated logged_in status

                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['account_type'] = $user['account_type'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['full_name'] = $user['first_name'] . " " . $user["last_name"];

                    // Redirect to the dashboard or homepage
                    header("Location: " . BASE_URL . "/client_list");
                    exit();
                } else {
                    $error = "Failed to update login status. Please try again.";
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email format.";
    }
}
?>
