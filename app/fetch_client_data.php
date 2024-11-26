<?php
// Include your database connection
include(ROOT_PATH . '/app/database/connection.php');

// Check if the client_id is passed via GET request
if (isset($_GET['client_id'])) {
    $client_id = $_GET['client_id'];

    // Query to get client details based on client_id
    $sql = "SELECT * FROM clients WHERE client_id = '$client_id'";
    $result = mysqli_query($conn, $sql);

    // Check if the query is successful
    if ($result && mysqli_num_rows($result) > 0) {
        $client = mysqli_fetch_assoc($result);

        // Return client data as JSON
        echo json_encode([
            'client_name' => $client['client_name'],
            'primary_contact' => $client['primary_contact'],
            'contact_email' => $client['contact_email'],
            'has_logo' => $client['logo'] // Assuming 'logo' column is 1 if client has logo
        ]);
    } else {
        // Return an error if no client is found
        echo json_encode(['error' => 'Client not found']);
    }
} else {
    echo json_encode(['error' => 'Client ID not provided']);
}
?>
