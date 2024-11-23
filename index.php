<?php
date_default_timezone_set('America/Denver');
require_once "app/database/connection.php";
require_once "path.php";
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['first_name'] . " " . $user["last_name"];

        // Redirect to a dashboard or homepage
        header("Location: " . BASE_URL . "/dashboard/index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
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
                    <!-- <a href="#">Create an Account</a> -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
