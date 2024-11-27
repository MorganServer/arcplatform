<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// add Client
    if (isset($_POST['add_client'])) {
        // Generate a random ID number
        $idno = rand(1000000, 9999999);
    
        // Sanitize input data
        $client_name = isset($_POST['c_client_name']) ? trim($_POST['c_client_name']) : "";
        $primary_contact = isset($_POST['c_primary_contact']) ? trim($_POST['c_primary_contact']) : "";
        $contact_email = isset($_POST['c_contact_email']) ? trim($_POST['c_contact_email']) : "";
        
        // Check if logo is provided (checkbox)
        $has_logo = isset($_POST['has_logo']) ? 1 : 0;
    
        // If a logo is provided, convert client name to lowercase and replace spaces with underscores
        $logo = $has_logo ? strtolower(str_replace(' ', '_', $client_name)) : null;
    
        // Generate a random color
        $random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    
        // Check if client already exists
        $select = $conn->prepare("SELECT idno FROM clients WHERE idno = ?");
        $select->bind_param("i", $idno); // "i" for integer
        $select->execute();
        $result = $select->get_result();
    
        if ($result->num_rows > 0) {
            $error[] = 'Client already exists!';
        } else {
            // Insert the new client into the database
            $insert = $conn->prepare("INSERT INTO clients (idno, client_name, primary_contact, contact_email, logo, random_color) 
                                      VALUES (?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), ?, ?)");
            $insert->bind_param("isssss", $idno, $client_name, $primary_contact, $contact_email, $logo, $random_color); // "i" for integer, "s" for string
        
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


// add qa comment
    if (isset($_POST['submit_qa_comment'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Generate a unique ID
        $qa_idno = rand(1000000, 9999999);

        // Sanitize and validate input data
        $qa_engagement_id = isset($_POST['qa_engagement_id']) ? trim($_POST['qa_engagement_id']) : ""; 
        $qa_client_name = isset($_POST['qa_client_name']) ? trim($_POST['qa_client_name']) : "";
        $control_ref = isset($_POST['control_ref']) ? trim($_POST['control_ref']) : "";
        $cell_reference = isset($_POST['cell_reference']) ? trim($_POST['cell_reference']) : "";
        $comment_by = isset($_POST['comment_by']) ? trim($_POST['comment_by']) : "";
        $control = isset($_POST['control']) ? trim($_POST['control']) : "";
        $qa_comment = isset($_POST['qa_comment']) ? trim($_POST['qa_comment']) : "";

        // Prepare query
        $stmt = $conn->prepare(
            "INSERT INTO qa_comments (idno, engagement_id, client_name, control_ref, cell_reference, comment_by, control, qa_comment)
            VALUES (?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))"
        );

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "ssssssss",
            $qa_idno,
            $qa_engagement_id,
            $qa_client_name,
            $control_ref,
            $cell_reference,
            $comment_by,
            $control,
            $qa_comment
        );

        if ($stmt->execute()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
    }
// end Add qa comment



?>