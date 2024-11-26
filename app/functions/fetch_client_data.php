<?php
// fetch_client_data.php


// Check if the client_id is passed
if (isset($_GET['client_id'])) {
    $client_id = $_GET['client_id'];
    
    // Query the database for the client data
    $sql = "SELECT * FROM clients WHERE client_id = '$client_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $client = mysqli_fetch_assoc($result);
        echo json_encode([
            'client_name' => $client['client_name'],
            'primary_contact' => $client['primary_contact'],
            'contact_email' => $client['contact_email'],
            'has_logo' => $client['logo'] // Assuming logo column is 1 if the client has a logo
        ]);
    } else {
        echo json_encode(['error' => 'Client not found']);
    }
} else {
    echo json_encode(['error' => 'Client ID not provided']);
}
?>
