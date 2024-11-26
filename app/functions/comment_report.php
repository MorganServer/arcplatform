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

    try {
        // Get selected options (statuses) from the request
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        $e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : null; // Ensure it's an integer

        // Debugging output
        // Check if options are selected
        if (empty($options)) {
            throw new Exception('No statuses selected. Please choose at least one option.');
        }

        // Ensure e_id is not empty or invalid
        if (empty($e_id) || !is_numeric($e_id)) {
            throw new Exception('Invalid Engagement ID.');
        }

        // Sanitize and prepare statuses for SQL query
        $statuses = implode("','", array_map([$conn, 'real_escape_string'], $options));
        $statuses = "'$statuses'"; // Prepare for SQL query

        // Fetch engagement name
        $sql = "SELECT * FROM engagement WHERE engagement_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $e_id); // Bind engagement_id
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $engagement = $result->fetch_assoc();
            $client_name = $engagement['client_name'];
            $engagement_type = $engagement['engagement_type'];
            $year = $engagement['year'];
            $engagement_name = $client_name . " - " . $year . " " . $engagement_type;
        } else {
            throw new Exception('Engagement not found.');
        }
        $stmt->close();

        // Log the prepared query for comments
        $sql = "SELECT qa_comment, control_ref, cell_reference, comment_by, status 
                FROM qa_comments 
                WHERE status IN ($statuses) AND engagement_id = ?";

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

        // Fetch comments and group by status
        $comments_by_status = [];
        while ($row = $result->fetch_assoc()) {
            $comments_by_status[$row['status']][] = $row;
        }

        $stmt->close();

        // Check if no comments were found
        if (empty($comments_by_status)) {
            throw new Exception('No comments found for the selected statuses.');
        }

        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "Comment Report - $engagement_name", 0, 1, 'C');
        $pdf->Ln(10);

        // Add comments for the selected options
        foreach ($options as $option) {
            if (isset($comments_by_status[$option])) {
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, ucfirst($option) . ' Comments:', 0, 1);
                $pdf->SetFont('Arial', '', 12);

                foreach ($comments_by_status[$option] as $comment) {
                    $pdf->Cell(0, 10, "Control Reference: " . $comment['control_ref'], 0, 1);
                    $pdf->Cell(0, 10, "Cell Reference: " . $comment['cell_reference'], 0, 1);
                    $pdf->Cell(0, 10, "Comment By: " . $comment['comment_by'], 0, 1);
                    $pdf->MultiCell(0, 10, "Comment: " . $comment['qa_comment']);
                    $pdf->Ln(5);
                }
            }
        }

        // Output the PDF
        $pdf->Output('D', 'Comment_Report.pdf'); // Forces download

    } catch (Exception $e) {
        // Display error message to user
        die('Error: ' . $e->getMessage());
    }
}

?>
