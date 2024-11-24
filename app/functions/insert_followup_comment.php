<?php
// Include your database connection file
include('../database/connection.php');

if (isset($_POST['submit_followup_comment'])) {
    // Capture form data
    $qa_id = $_POST['qa_id'];
    $engagement_id = $_POST['engagement_id'];
    $followup_comment = $_POST['followup_comment'];

    // Generate a random 6-digit number for the idno
    do {
        $idno = rand(100000, 999999);
        // Check if the random idno already exists
        $checkQuery = "SELECT idno FROM followup_qa_comments WHERE idno = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $idno);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0); // If idno already exists, regenerate

    // Insert the new follow-up comment into the database
    $insertQuery = "INSERT INTO followup_qa_comments (idno, qa_id, engagement_id, followup_comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiis", $idno, $qa_id, $engagement_id, $followup_comment);

    if ($stmt->execute()) {
        echo "Follow-up comment added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
