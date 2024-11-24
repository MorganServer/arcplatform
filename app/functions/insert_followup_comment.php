<?php
include('../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qa_id = $_POST['qa_id'];
    $engagement_id = $_POST['engagement_id'];
    $followup_comment = $_POST['followup_comment'];

    // Generate a random 6-digit number for idno
    do {
        $idno = rand(100000, 999999);
        $checkQuery = "SELECT idno FROM followup_qa_comments WHERE idno = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $idno);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    // Insert the follow-up comment
    $insertQuery = "INSERT INTO followup_qa_comments (idno, qa_id, engagement_id, followup_comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiis", $idno, $qa_id, $engagement_id, $followup_comment);
    $stmt->execute();

    // Fetch and display the updated list of follow-up comments
    $followupSql = "SELECT * FROM followup_qa_comments WHERE qa_id = ?";
    $stmt = $conn->prepare($followupSql);
    $stmt->bind_param("i", $qa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p>" . htmlspecialchars($row['followup_comment']) . "</p>";
    }
}
?>
