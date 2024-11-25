<?php

// Check if the form is submitted
if (isset($_POST['submit-client'])) {
    // Get the client name from the form
    $client_name = mysqli_real_escape_string($conn, $_POST['c_client_name']);
    
    // Insert into the database
    $sql = "INSERT INTO clients (client_name) VALUES ('$client_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New client added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    // Close connection
    $conn->close();
}
?>