<?php
// add Client
    if (isset($_POST['add_client'])) {
        // Generate a random ID number
        $idno = rand(1000000, 9999999);

        // Sanitize input data
        $client_name = isset($_POST['c_client_name']) ? trim($_POST['c_client_name']) : "";

        // Check if client already exists
        $select = $conn->prepare("SELECT idno FROM clients WHERE idno = ?");
        $select->bind_param("i", $idno); // "i" for integer
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $error[] = 'Client already exists!';
        } else {
            // Insert the new client into the database
            $insert = $conn->prepare("INSERT INTO clients (idno, client_name) VALUES (?, NULLIF(?, ''))");
            $insert->bind_param("is", $idno, $client_name); // "i" for integer, "s" for string

            if ($insert->execute()) {
                header('location:' . BASE_URL . '/');
                exit; // Ensure script stops execution after redirecting
            } else {
                $error[] = 'Error: ' . $conn->error;
            }
        }

        // Close prepared statements
        $select->close();
        if (isset($insert)) $insert->close();
    }
// end Add Client

// add Engagement
    if (isset($_POST['add_engagement'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Generate a unique ID
        $e_idno = rand(1000000, 9999999);

        // Sanitize and validate input data
        $e_client_name = isset($_POST['e_client_name']) ? trim($_POST['e_client_name']) : ""; 
        $e_engagement_type = isset($_POST['e_engagement_type']) ? trim($_POST['e_engagement_type']) : "";
        $year = isset($_POST['year']) ? trim($_POST['year']) : "";
        $report_start = isset($_POST['report_start']) ? trim($_POST['report_start']) : "";
        $report_end = isset($_POST['report_end']) ? trim($_POST['report_end']) : "";
        $report_as_of = isset($_POST['report_as_of']) ? trim($_POST['report_as_of']) : "";
        $manager = isset($_POST['manager']) ? trim($_POST['manager']) : "";
        $senior = isset($_POST['senior']) ? trim($_POST['senior']) : "";
        $staff = isset($_POST['staff']) ? trim($_POST['staff']) : "";
        $leadsheet_due = isset($_POST['leadsheet_due']) ? trim($_POST['leadsheet_due']) : "";
        $field_work_week = isset($_POST['field_work_week']) ? trim($_POST['field_work_week']) : "";
        $senior_dol = isset($_POST['senior_dol']) ? trim($_POST['senior_dol']) : "";
        $staff_1_dol = isset($_POST['staff_1_dol']) ? trim($_POST['staff_1_dol']) : "";
        $staff_2_dol = isset($_POST['staff_2_dol']) ? trim($_POST['staff_2_dol']) : "";

        // Debug parameters
        echo "Debug Parameters:<br>";
        echo "e_idno: $e_idno<br>";
        echo "e_client_name: $e_client_name<br>";
        echo "e_engagement_type: $e_engagement_type<br>";
        // Add other variables as needed

        // Prepare query
        $stmt = $conn->prepare(
            "INSERT INTO engagement (idno, client_name, engagement_type, year, report_start, report_end, report_as_of, manager, senior, staff, leadsheets_due, field_work_week, senior_dol, staff_1_dol, staff_2_dol)
            VALUES (?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))"
        );

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssss",
            $e_idno,
            $e_client_name,
            $e_engagement_type,
            $year,
            $report_start,
            $report_end,
            $report_as_of,
            $manager,
            $senior,
            $staff,
            $leadsheet_due,
            $field_work_week,
            $senior_dol,
            $staff_1_dol,
            $staff_2_dol
        );

        if ($stmt->execute()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
    }
// end Add Engagement

// add QA Comment
    if (isset($_POST['submit_qa_comment'])) {
        // Generate a unique ID for the comment
        $idno = rand(1000000, 9999999);

        // Sanitize input data
        $qa_engagement_id = isset($_POST['qa_engagement_id']) ? trim($_POST['qa_engagement_id']) : "";
        $qa_client_name = isset($_POST['qa_client_name']) ? trim($_POST['qa_client_name']) : "";
        $control_ref = isset($_POST['control_ref']) ? trim($_POST['control_ref']) : "";
        $cell_reference = isset($_POST['cell_reference']) ? trim($_POST['cell_reference']) : "";
        $comment_by = isset($_POST['comment_by']) ? trim($_POST['comment_by']) : "";
        $control = isset($_POST['control']) ? trim($_POST['control']) : "";
        $qa_comment = isset($_POST['qa_comment']) ? trim($_POST['qa_comment']) : "";

        // Check if QA comment already exists using prepared statement
        $stmt = $conn->prepare("SELECT * FROM qa_comments WHERE idno = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $idno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error[] = 'QA Comment already exists!';
        } else {
            // Prepare the insert query using prepared statements
            $stmt = $conn->prepare(
                "INSERT INTO qa_comments (idno, engagement_id, client_name, control_ref, cell_reference, comment_by, control, qa_comment)
                VALUES (?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))"
            );

            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            // Bind parameters to the prepared statement
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

            // Execute the query
            if ($stmt->execute()) {
                header('location: ' . BASE_URL . '/');
                exit; // Ensure script stops execution after redirecting
            } else {
                $error[] = 'Error: ' . $stmt->error; // Display error from the statement
            }

            $stmt->close();
        }
    }
// end Add QA Comment

// Completed Engagement
if (isset($_POST['complete_engagement'])) {
    // Debugging: Check if POST data is being sent
    var_dump($_POST);

    // Sanitize input data
    $engagement_id = isset($_POST['engagement_id']) ? (int) trim($_POST['engagement_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : "";

    // Debugging: Check if engagement_id and status are correct
    echo "Engagement ID: " . $engagement_id . "<br>";
    echo "Status: " . $status . "<br>";

    // Check database connection
    if (!$conn) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare the UPDATE query
    $update = $conn->prepare("UPDATE engagement SET status = ? WHERE engagement_id = ?");
    
    // Check if prepare was successful
    if ($update === false) {
        die("Prepare failed: " . $conn->error); // Print detailed error if prepare fails
    }

    // Bind parameters to the prepared statement
    $update->bind_param("si", $status, $engagement_id); // "s" for string, "i" for integer

    // Execute the query
    if ($update->execute()) {
        // Check if rows were updated
        if ($update->affected_rows > 0) {
            // Success: Redirect to the desired page
            header('Location: ' . BASE_URL . '/');
            exit; // Ensure script stops execution after redirect
        } else {
            // No rows updated, maybe engagement_id doesn't exist or status is already set
            echo '<div class="error">No changes made. Please verify engagement ID and current status.</div>';
        }
    } else {
        // Error: Display detailed error message
        echo '<div class="error">Error executing query: ' . $update->error . '</div>';
    }

    // Close prepared statement
    $update->close();
}

// end Complete Engagement
?>