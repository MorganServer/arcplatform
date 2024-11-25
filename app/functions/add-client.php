<?php
if (isset($_POST['add_client'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $client_name = isset($_POST['c_client_name']) ? mysqli_real_escape_string($conn, $_POST['c_client_name']) : ""; 
    
    // Check if asset already exists
    $select = "SELECT * FROM clients WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Client already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "INSERT INTO clients (idno, client_name) 
            VALUES ('$idno', NULLIF('$client_name', ''))";

        if (mysqli_query($conn, $insert)) {
            header('location:' . BASE_URL . '/');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>