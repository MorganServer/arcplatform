<?php
require(ROOT_PATH . '/app/fpdf/fpdf.php'); // Include FPDF library
require(ROOT_PATH . '/app/database/connection.php'); // Include your database connection file

// Enable error reporting for debugging (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get selected options (statuses) from the request
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        $e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : null; // Ensure it's an integer

        // Validate inputs
        if (empty($options)) {
            throw new Exception('No statuses selected. Please choose at least one option.');
        }

        if (!$e_id) {
            throw new Exception('Engagement ID missing. Please provide a valid engagement ID.');
        }

        // Sanitize and prepare statuses for SQL
        $statuses = implode("','", array_map([$conn, 'real_escape_string'], $options));
        $statuses = "'$statuses'";

        // Prepare and execute the SQL query
        $sql = "SELECT qa_comment FROM qa_comments WHERE status IN ($statuses) AND engagement_id = ?";
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
            $comments[] = $row['comment'];
        }

        $stmt->close();

        // Check if comments were fetched
        if (empty($comments)) {
            throw new Exception('No comments found for the selected options.');
        }

        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Comment Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Add comments to the PDF
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

    } catch (Exception $e) {
        // Display error message to user
        die('Error: ' . $e->getMessage());
    }
}
?>
