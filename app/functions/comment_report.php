<?php
require(ROOT_PATH . '/app/fpdf/fpdf.php'); // Include FPDF library

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected options from the request
    $options = isset($_POST['options']) ? $_POST['options'] : [];

    // Example comments data
    $comments = [
        'new' => ["New Comment 1", "New Comment 2"],
        'follow-up' => ["Follow-Up Comment 1", "Follow-Up Comment 2"],
        'completed' => ["Completed Comment 1", "Completed Comment 2"]
    ];

    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Comment Report', 0, 1, 'C');
    $pdf->Ln(10);

    // Add selected comments to the PDF
    foreach ($options as $option) {
        if (isset($comments[$option])) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, ucfirst($option) . ' Comments:', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            foreach ($comments[$option] as $comment) {
                $pdf->Cell(0, 10, "- $comment", 0, 1);
            }
            $pdf->Ln(5);
        }
    }

    // Output the PDF
    $pdf->Output('D', 'Comment_Report.pdf'); // Forces download
}
?>
