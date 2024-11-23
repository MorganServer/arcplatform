<?php
require_once "path.php";

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
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']); // Clear the error after displaying
            }
            ?>
            <form action="app/functions/login_process.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
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
