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
            <form>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Enter your password" required>
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
