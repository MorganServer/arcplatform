<?php
// Include your database connection
include(ROOT_PATH . '/app/database/connection.php');

// Check if the client_id is passed via GET request
if (isset($_GET['client_id'])) {
    $client_id = $_GET['client_id'];

    // Debug: Log the client_id received in the GET request
    error_log("Received client_id: " . $client_id);

    // Query to get client details based on client_id
    $sql = "SELECT * FROM clients WHERE client_id = '$client_id'";
    
    // Debug: Log the SQL query for verification
    error_log("SQL Query: " . $sql);
    
    $result = mysqli_query($conn, $sql);

    // Check if the query is successful
    if ($result && mysqli_num_rows($result) > 0) {
        $client = mysqli_fetch_assoc($result);

        // Debug: Log the fetched client data
        error_log("Fetched client data: " . print_r($client, true));

        // Return client data as JSON
        echo json_encode([
            'client_name' => $client['client_name'],
            'primary_contact' => $client['primary_contact'],
            'contact_email' => $client['contact_email'],
            'has_logo' => $client['logo'] // Assuming 'logo' column is 1 if client has logo
        ]);
    } else {
        // Log an error if no client is found
        error_log("No client found with ID: " . $client_id);
        echo json_encode(['error' => 'Client not found']);
    }
} else {
    // Log an error if client_id is not provided
    error_log("Client ID not provided");
    echo json_encode(['error' => 'Client ID not provided']);
}
?>