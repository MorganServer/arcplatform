<?php
// Ensure session starts before any output
session_start();

require_once "path.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', __DIR__ . '/php_errors.log'); // Log errors to a file

// Use absolute paths for includes
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/database/connection.php'; 

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the input data safely
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Keep password in plain form for MD5 hashing

    // Validate the email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare the SQL statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email); // Bind the email parameter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User exists, fetch their details
                $user = $result->fetch_assoc();

                // Hash the entered password with MD5 and compare with the stored hash
                if (md5($password) === $user['password']) {
                    // Password matches, update the user's logged_in status
                    $userId = $user['user_id'];
                    $updateSql = "UPDATE users SET logged_in = 1 WHERE user_id = ?";
                    if ($updateStmt = $conn->prepare($updateSql)) {
                        $updateStmt->bind_param("i", $userId);
                        $updateStmt->execute();

                        if ($updateStmt->affected_rows > 0) {
                            // Successfully updated logged_in status

                            // Set session variables
                            $_SESSION['user_id'] = $user['user_id'];
                            $_SESSION['account_type'] = $user['account_type'];
                            $_SESSION['email'] = $user['email'];
                            $_SESSION['full_name'] = $user['first_name'] . " " . $user['last_name'];

                            // Redirect to the dashboard or homepage
                            header("Location: " . BASE_URL . "/client_list");
                            exit();
                        } else {
                            $error = "Failed to update login status. Please try again.";
                        }
                    }
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Database error. Please try again later.";
        }
    } else {
        $error = "Invalid email format.";
    }
}

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ARC Platform</title>
    <link rel="stylesheet" href="assets/css/login_styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <img src="assets/images/login_logo.png" width="150" alt="">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <button type="submit" class="login-button">Login</button>
                <div class="extra-links">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
