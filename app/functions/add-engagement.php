<?php
if (isset($_POST['add_engagement'])) {
    // Generate a unique ID for the engagement
    $idno = rand(1000000, 9999999);

    // Sanitize and validate input data
    $client_name = isset($_POST['e_client_name']) ? trim(mysqli_real_escape_string($conn, $_POST['e_client_name'])) : ""; 
    $engagement_type = isset($_POST['e_engagement_type']) ? trim(mysqli_real_escape_string($conn, $_POST['e_engagement_type'])) : "";
    $year = isset($_POST['year']) ? trim(mysqli_real_escape_string($conn, $_POST['year'])) : "";
    $report_start = isset($_POST['report_start']) ? trim(mysqli_real_escape_string($conn, $_POST['report_start'])) : "";
    $report_end = isset($_POST['report_end']) ? trim(mysqli_real_escape_string($conn, $_POST['report_end'])) : "";
    $report_as_of = isset($_POST['report_as_of']) ? trim(mysqli_real_escape_string($conn, $_POST['report_as_of'])) : "";
    $manager = isset($_POST['manager']) ? trim(mysqli_real_escape_string($conn, $_POST['manager'])) : "";
    $senior = isset($_POST['senior']) ? trim(mysqli_real_escape_string($conn, $_POST['senior'])) : "";
    $staff = isset($_POST['staff']) ? trim(mysqli_real_escape_string($conn, $_POST['staff'])) : "";
    $leadsheet_due = isset($_POST['leadsheet_due']) ? trim(mysqli_real_escape_string($conn, $_POST['leadsheet_due'])) : "";
    $field_work_week = isset($_POST['field_work_week']) ? trim(mysqli_real_escape_string($conn, $_POST['field_work_week'])) : "";
    $senior_dol = isset($_POST['senior_dol']) ? trim(mysqli_real_escape_string($conn, $_POST['senior_dol'])) : "";
    $staff_1_dol = isset($_POST['staff_1_dol']) ? trim(mysqli_real_escape_string($conn, $_POST['staff_1_dol'])) : "";
    $staff_2_dol = isset($_POST['staff_2_dol']) ? trim(mysqli_real_escape_string($conn, $_POST['staff_2_dol'])) : "";

    // Check if engagement already exists
    $stmt = $conn->prepare("SELECT * FROM engagement WHERE idno = ?");
    $stmt->bind_param("s", $idno);
    $stmt->execute();
    $engagement_result = $stmt->get_result();

    if ($engagement_result->num_rows > 0) {
        $error[] = 'Engagement already exists!';
    } else {
        // Insert the new engagement into the database
        $stmt = $conn->prepare(
            "INSERT INTO engagement (idno, client_name, engagement_type, year, report_start, report_end, report_as_of, manager, senior, staff, leadsheet_due, field_work_week, senior_dol, staff_1_dol, staff_2_dol)
            VALUES (?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))"
        );
        
        $stmt->bind_param(
            "sssssssssssssss",
            $idno,
            $client_name,
            $engagement_type,
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
            $error[] = 'Error: ' . $conn->error;
        }
    }
    $stmt->close();
}

?>
