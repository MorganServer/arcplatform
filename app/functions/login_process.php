<?php
session_start();
include('../database/connection.php'); // Database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = md5($password); // Hash the password using MD5

    // Validate input
    if (!empty($email) && !empty($password)) {
        // Prepare the SQL query
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name']; // Assuming the table has a 'name' column

            // Redirect to dashboard or another page
            header("Location:" . echo ROOT_PATH . "dashboard/index.php");
            exit();
        } else {
            // Invalid credentials
            $_SESSION['error'] = "Invalid email or password.";
            header("Location:" . echo ROOT_PATH . "index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location:" . echo ROOT_PATH . "index.php");
        exit();
    }
} else {
    // Redirect back to login if accessed directly
    header("Location:" . echo ROOT_PATH . "index.php");
    exit();
}
?>
