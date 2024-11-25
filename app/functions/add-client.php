<?php
// add Client
    if (isset($_POST['add_client'])) {
        $idno = rand(1000000, 9999999);

        // Sanitize input data
        $client_name = isset($_POST['c_client_name']) ? mysqli_real_escape_string($conn, $_POST['c_client_name']) : ""; 

        // Check if asset already exists
        $select = "SELECT * FROM clients WHERE idno = '$idno'";
        $result = mysqli_query($conn, $select);
        if (mysqli_num_rows($result) > 0) {
            $error[] = 'Client already exists!';
        } else {
            // Insert the new asset into the database
            $insert = "INSERT INTO clients (idno, client_name) 
                VALUES ('$idno', NULLIF('$client_name', ''))";

            if (mysqli_query($conn, $insert)) {
                header('location:' . BASE_URL . '/');
                exit; // Ensure script stops execution after redirecting
            } else {
                $error[] = 'Error: ' . mysqli_error($conn);
            }
        }
    }
// end Add Client

// add Engagement
    if (isset($_POST['add_engagement'])) {
        $idno = rand(1000000, 9999999);

        // Sanitize input data
        $client_name = isset($_POST['e_client_name']) ? mysqli_real_escape_string($conn, $_POST['e_client_name']) : ""; 
        $engagement_type = isset($_POST['e_engagement_type']) ? mysqli_real_escape_string($conn, $_POST['e_engagement_type']) : "";
        $year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : "";
        $report_start = isset($_POST['report_start']) ? mysqli_real_escape_string($conn, $_POST['report_start']) : "";
        $report_end = isset($_POST['report_end']) ? mysqli_real_escape_string($conn, $_POST['report_end']) : "";
        $report_as_of = isset($_POST['report_as_of']) ? mysqli_real_escape_string($conn, $_POST['report_as_of']) : "";
        $manager = isset($_POST['manager']) ? mysqli_real_escape_string($conn, $_POST['manager']) : "";
        $senior = isset($_POST['senior']) ? mysqli_real_escape_string($conn, $_POST['senior']) : "";
        $staff = isset($_POST['staff']) ? mysqli_real_escape_string($conn, $_POST['staff']) : "";
        $leadsheet_due = isset($_POST['leadsheet_due']) ? mysqli_real_escape_string($conn, $_POST['leadsheet_due']) : "";
        $field_work_week = isset($_POST['field_work_week']) ? mysqli_real_escape_string($conn, $_POST['field_work_week']) : "";
        $senior_dol = isset($_POST['senior_dol']) ? mysqli_real_escape_string($conn, $_POST['senior_dol']) : "";
        $staff_1_dol = isset($_POST['staff_1_dol']) ? mysqli_real_escape_string($conn, $_POST['staff_1_dol']) : "";
        $staff_2_dol = isset($_POST['staff_2_dol']) ? mysqli_real_escape_string($conn, $_POST['staff_2_dol']) : "";

        // Check if client already exists
        $select = "SELECT * FROM engagement WHERE idno = '$idno'";
        $result = mysqli_query($conn, $select);
        if (mysqli_num_rows($result) > 0) {
            $error[] = 'Engagement already exists!';
        } else {
            // Insert the new engagement into the database
            $insert = "INSERT INTO engagement (idno, client_name, engagement_type, year, report_start, report_end, report_as_of, manager, senior, staff, leadsheet_due, field_work_week, senior_dol, staff_1_dol, staff_2_dol)
                VALUES ('$idno', NULLIF('$client_name' ''), NULLIF('$engagement_type', ''), NULLIF('$year', ''), NULLIF('$report_start', ''), NULLIF('$report_end' ''), NULLIF('$report_as_of', ''), NULLIF('$manager', ''), NULLIF('$senior', ''), NULLIF('$staff', ''), NULLIF('$leadsheet_due', ''), NULLIF('$field_work_week', ''), NULLIF('$senior_dol', ''), NULLIF('$staff_1_dol', ''), NULLIF('$staff_2_dol', ''))";

            if (mysqli_query($conn, $insert)) {
                header('location: ' . BASE_URL . '/');
                exit; // Ensure script stops execution after redirecting
            } else {
                $error[] = 'Error: ' . mysqli_error($conn);
            }
        }
    }
// end Add Engagement

// add QA Comment
    if (isset($_POST['submit_qa_comment'])) {
        $idno = rand(1000000, 9999999);
        // Sanitize input data
        $qa_engagement_id = isset($_POST['qa_engagement_id']) ? mysqli_real_escape_string($conn, $_POST['qa_engagement_id']) : "";
        $qa_client_name = isset($_POST['qa_client_name']) ? mysqli_real_escape_string($conn, $_POST['qa_client_name']) : "";
        $control_ref = isset($_POST['control_ref']) ? mysqli_real_escape_string($conn, $_POST['control_ref']) : "";
        $cell_reference = isset($_POST['cell_reference']) ? mysqli_real_escape_string($conn, $_POST['cell_reference']) : "";
        $comment_by = isset($_POST['comment_by']) ? mysqli_real_escape_string($conn, $_POST['comment_by']) : "";
        $control = isset($_POST['control']) ? mysqli_real_escape_string($conn, $_POST['control']) : "";
        $qa_comment = isset($_POST['qa_comment']) ? mysqli_real_escape_string($conn, $_POST['qa_comment']) : "";

        // Check if client already exists
        $select = "SELECT * FROM qa_comments WHERE idno = '$idno'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $error[] = 'QA Comment already exists!';
        } else {
            // Insert the QA comment into the database
            $insert_qa_comment = "INSERT INTO qa_comments (idno, engagement_id, client_name, control_ref, cell_reference, comment_by, control, qa_comment)
                                  VALUES ('$idno', NULLIF('$qa_engagement_id', ''), NULLIF('$qa_client_name', ''), NULLIF('$control_ref', ''), NULLIF('$cell_reference', ''), NULLIF('$comment_by', ''), NULLIF('$control', ''), NULLIF('$qa_comment', ''))";

            if (mysqli_query($conn, $insert_qa_comment)) {
                header('location: ' . BASE_URL . '/');
                exit; // Ensure script stops execution after redirecting
            } else {
                $error[] = 'Error: ' . mysqli_error($conn);
            }
        }
    }   
// end Add QA Comment



?>