<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Process form submission
if (isset($_POST['submit-client'])) {
    $idno = rand(1000000, 9999999); // Generate random idno

    // Sanitize input data
    $client_name = isset($_POST['c_client_name']) ? mysqli_real_escape_string($conn, $_POST['c_client_name']) : "";

    // Check if client already exists by name (if that's the intended behavior)
    $select = "SELECT * FROM clients WHERE client_name = '$client_name'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Client already exists!';
    } else {
        // Insert into database
        $insert = "INSERT INTO clients (idno, client_name) 
            VALUES ('$idno', NULLIF('$client_name', ''))";

        if (mysqli_query($conn, $insert)) {
            // Redirect after successful insertion
            header('Location: ' . BASE_URL . '/');
            exit; // Stop further script execution after redirect
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

// Display errors if any
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p>$err</p>";
    }
}
?>