<?php
$servername = "localhost";
$username = "dbuser"; // Update with your DB username
$password = "DBuser123!"; // Update with your DB password
$dbname = "arcplatform"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
