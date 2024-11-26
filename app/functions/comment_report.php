<?php
require(ROOT_PATH . '/app/fpdf/fpdf.php'); // Include FPDF library
require(ROOT_PATH . '/app/database/connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected options (statuses) from the request
    $options = isset($_POST['options']) ? $_POST['options'] : [];
    $e_id = isset($_POST['e_id']) ? $_POST['e_id'] : null;

    if (empty($options) || !$engagement_id) {
        die('No options selected or engagement ID missing.');
    }

    // Convert options array to a string for SQL query
    $statuses = implode("','", array_map('mysqli_real_escape_string', $options)); // Sanitize inputs
    $statuses = "'$statuses'"; // Prepare for SQL query

    // Database query to fetch comments
    $sql = "SELECT comment FROM qa_comments 
            WHERE status IN ($statuses) 
            AND engagement_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $engagement_id); // Bind engagement_id
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch comments
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row['comment'];
    }
    
    // Close statement
    $stmt->close();

    // Check if comments are available
    if (empty($comments)) {
        die('No comments found for the selected options.');
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
}
?>
