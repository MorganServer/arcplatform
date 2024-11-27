<?php
// add QA Comment

$error = [];
$success = false;

if (isset($_POST['submit_qa_comment'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize and validate inputs
    $qa_engagement_id = trim($_POST['qa_engagement_id'] ?? "");
    $qa_client_name = trim($_POST['qa_client_name'] ?? "");
    $control_ref = trim($_POST['control_ref'] ?? "");
    $cell_reference = trim($_POST['cell_reference'] ?? "");
    $comment_by = trim($_POST['comment_by'] ?? "");
    $control = trim($_POST['control'] ?? "");
    $qa_comment = trim($_POST['qa_comment'] ?? "");

    // Check for empty required fields
    if (empty($qa_engagement_id) || empty($control_ref) || empty($cell_reference) || empty($comment_by) || empty($control) || empty($qa_comment)) {
        $error[] = "All fields are required.";
    }

    if (empty($error)) {
        // Check if QA comment ID already exists
        $stmt = $conn->prepare("SELECT * FROM qa_comments WHERE idno = ?");
        if (!$stmt) {
            $error[] = "Prepare failed (Select): " . $conn->error;
        } else {
            $stmt->bind_param("s", $idno);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error[] = "QA Comment with this ID already exists!";
            } else {
                // Insert new QA comment
                $stmt = $conn->prepare(
                    "INSERT INTO qa_comments (idno, engagement_id, client_name, control_ref, cell_reference, comment_by, control, qa_comment)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );
                if ($stmt) {
                    $stmt->bind_param(
                        "ssssssss",
                        $idno,
                        $qa_engagement_id,
                        $qa_client_name,
                        $control_ref,
                        $cell_reference,
                        $comment_by,
                        $control,
                        $qa_comment
                    );

                    if ($stmt->execute()) {
                        $success = true; // Indicate success
                    } else {
                        $error[] = "Insert failed: " . $stmt->error;
                    }
                } else {
                    $error[] = "Prepare failed (Insert): " . $conn->error;
                }
                $stmt->close();
            }
        }
    }
}

// Display errors or success
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<script>console.error('Error: " . addslashes($err) . "');</script>";
        echo "<div class='alert alert-danger'>$err</div>";
    }
}

if ($success) {
    echo "<script>console.log('QA Comment successfully added with ID: $idno');</script>";
    echo "<div class='alert alert-success'>QA Comment added successfully!</div>";
}

// end Add QA Comment

?>