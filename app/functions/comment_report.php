<?php
// Enable error reporting for debugging (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2)); // Adjust path as needed
}

require(ROOT_PATH . '/app/fpdf/fpdf.php');
require(ROOT_PATH . '/app/database/connection.php'); // Include your database connection file

// Debugging: Check if POST data is being received correctly
var_dump($_POST);
exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected options (statuses) and engagement_id from POST
    $options = isset($_POST['options']) ? $_POST['options'] : [];
    $e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : null; // Ensure it's an integer

    // Check if options or engagement_id is missing
    if (empty($options)) {
        die('No statuses selected. Please choose at least one option.');
    }

    if (!$e_id) {
        die('Engagement ID missing. Please provide a valid engagement ID.');
    }

    // Sanitize and prepare statuses for SQL
    $statuses = implode("','", array_map([$conn, 'real_escape_string'], $options));
    $statuses = "'$statuses'";

    // Prepare and execute the SQL query
    $sql = "SELECT qa_comment FROM qa_comments WHERE status IN ($statuses) AND engagement_id = ?";
    echo "SQL Query: " . $sql; // Output the SQL for debugging

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error); // Detailed error if prepare fails
    }

    $stmt->bind_param('i', $e_id); // Bind engagement_id
    if (!$stmt->execute()) {
        die("Error executing SQL statement: " . $stmt->error); // Detailed error if execute fails
    }

    $result = $stmt->get_result();

    // Fetch comments
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row['qa_comment'];
    }

    // Close statement
    $stmt->close();

    // Check if comments are available
    if (empty($comments)) {
        die('No comments found for the selected options.');
    }

    // Create the PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Comment Report', 0, 1, 'C');
    $pdf->Ln(10);

    foreach ($options as $option) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, ucfirst($option) . ' Comments:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        foreach ($comments as $comment) {
            $pdf->Cell(0, 10, "- $comment", 0, 1);
        }
        $pdf->Ln(5);
    }

    // Output the PDF
    $pdf->Output('D', 'Comment_Report.pdf'); // Forces download
}
?>
