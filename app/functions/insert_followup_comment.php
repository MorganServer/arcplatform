<?php
include('../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qa_id = $_POST['qa_id'];
    $engagement_id = $_POST['engagement_id'];
    $followup_comment = $_POST['followup_comment'];
    $followup_owner = $_POST['followup_owner'];

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
    $insertQuery = "INSERT INTO followup_qa_comments (idno, qa_id, engagement_id, followup_comment, followup_owner) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiiss", $idno, $qa_id, $engagement_id, $followup_comment, $followup_owner);
    $stmt->execute();

    // Fetch the newly inserted follow-up comment
    $followupSql = "SELECT * FROM followup_qa_comments WHERE idno = ?";
    $stmt = $conn->prepare($followupSql);
    $stmt->bind_param("i", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Format and return the new comment
    // $owner = htmlspecialchars($row['followup_owner']);
    $comment = htmlspecialchars($row['followup_comment']);
    $createdAt = date("F j, Y, g:i a", strtotime($row['followup_created']));
    
    echo "
    <div class='comment'>
        <div class='comment-header'>
            <span class='comment-time'>$createdAt</span>
            <span class='comment-author'>$followup_owner</span> <!-- Display the author here -->
        </div>
        <div class='comment-body mt-2'>
            <p>$comment</p>
        </div>
    </div>";

}
?>
