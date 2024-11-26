<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection
include('database/connection.php'); // Adjust the path as needed

header('Content-Type: application/json');

// Check if the engagement_id is provided
if (isset($_GET['engagement_id'])) {
    $engagement_id = mysqli_real_escape_string($conn, $_GET['engagement_id']); // Sanitize input

    // Debug: Log the engagement ID
    error_log("Received engagement_id: $engagement_id");

    // Prepare and execute the SQL query
    $sql = "SELECT * FROM engagement WHERE engagement_id = '$engagement_id'";
    error_log("SQL Query: $sql");

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $engagement = mysqli_fetch_assoc($result);

        // Debug: Log fetched engagement data
        error_log("Fetched engagement data: " . print_r($engagement, true));

        // Return engagement data as JSON
        echo json_encode([
            'engagement_id' => $engagement['engagement_id'],
            'client_name' => $engagement['client_name'],
            'engagement_type' => $engagement['engagement_type'],
            'year' => $engagement['year'],
            'report_start' => $engagement['report_start'],
            'report_end' => $engagement['report_end'],
            'report_as_of' => $engagement['report_as_of'],
            'manager' => $engagement['manager'],
            'senior' => $engagement['senior'],
            'staff' => $engagement['staff'],
            'leadsheets_due' => $engagement['leadsheets_due'],
            'field_work_week' => $engagement['field_work_week'],
            'senior_dol' => $engagement['senior_dol'],
            'staff_1_dol' => $engagement['staff_1_dol'],
            'staff_2_dol' => $engagement['staff_2_dol'],
        ]);
    } else {
        // Log error and return message if no results
        error_log("No engagement found with ID: $engagement_id");
        echo json_encode(['error' => 'Engagement not found']);
    }
} else {
    // Log error and return message if engagement_id is not provided
    error_log("Engagement ID not provided");
    echo json_encode(['error' => 'Engagement ID not provided']);
}
?>
