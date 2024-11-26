<?php
// Enable error reporting for debugging (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define constants for root path and base URL
define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

// Include necessary files
require(ROOT_PATH . '/fpdf/fpdf.php'); // Include FPDF library
require(ROOT_PATH . '/database/connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize debugging log file (optional)
    $logFile = ROOT_PATH . '/logs/debug.log';
    file_put_contents($logFile, "---- Debug Log Start ----\n", FILE_APPEND);

    try {
        // Get selected options (statuses) from the request
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        $e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : null; // Ensure it's an integer

        // Debugging output
        file_put_contents($logFile, "Received Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

        // Check if options are selected
        if (empty($options)) {
            throw new Exception('No statuses selected. Please choose at least one option.');
        }

        // Ensure e_id is not empty or invalid
        if (empty($e_id) || !is_numeric($e_id)) {
            throw new Exception('Invalid Engagement ID.');
        }

        // Log inputs
        file_put_contents($logFile, "Selected Options: " . implode(',', $options) . "\nEngagement ID: $e_id\n", FILE_APPEND);

        // Sanitize and prepare statuses for SQL query
        $statuses = implode("','", array_map([$conn, 'real_escape_string'], $options));
        $statuses = "'$statuses'"; // Prepare for SQL query

        // Log the prepared query
        $sql = "SELECT qa_comment FROM qa_comments WHERE status IN ($statuses) AND engagement_id = ?";
        file_put_contents($logFile, "SQL Query: $sql\n", FILE_APPEND);

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('SQL statement preparation failed: ' . $conn->error);
        }

        $stmt->bind_param('i', $e_id); // Bind engagement_id
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception('SQL execution failed: ' . $stmt->error);
        }

        // Fetch comments
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row['qa_comment'];
        }

        $stmt->close();

        // Log the fetched comments
        file_put_contents($logFile, "Fetched Comments: " . print_r($comments, true) . "\n", FILE_APPEND);

        // Check if no comments were found
        if (empty($comments)) {
            throw new Exception('No comments found for the selected statuses.');
        }

        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Comment Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Add comments for the selected options
        foreach ($options as $option) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, ucfirst($option) . ' Comments:', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            foreach ($comments as $comment) {
                $pdf->Cell(0, 10, "- $comment", 0, 1);
            }
            $pdf->Ln(5);
        }

        // Log PDF creation success
        file_put_contents($logFile, "PDF created successfully.\n", FILE_APPEND);

        // Output the PDF
        $pdf->Output('D', 'Comment_Report.pdf'); // Forces download

    } catch (Exception $e) {
        // Log error
        file_put_contents($logFile, "Error: " . $e->getMessage() . "\n", FILE_APPEND);

        // Display error message to user
        die('Error: ' . $e->getMessage());
    }

    // Log end
    file_put_contents($logFile, "---- Debug Log End ----\n", FILE_APPEND);
}

?>
