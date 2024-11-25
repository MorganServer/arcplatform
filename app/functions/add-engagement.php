<?php
// if (isset($_POST['add_engagement'])) {
//     $idno = rand(1000000, 9999999);

//     // Sanitize input data
//     $client_name = isset($_POST['e_client_name']) ? mysqli_real_escape_string($conn, $_POST['e_client_name']) : ""; 
//     $engagement_type = isset($_POST['e_engagement_type']) ? mysqli_real_escape_string($conn, $_POST['e_engagement_type']) : "";
//     $year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : "";
//     $report_start = isset($_POST['report_start']) ? mysqli_real_escape_string($conn, $_POST['report_start']) : "";
//     $report_end = isset($_POST['report_end']) ? mysqli_real_escape_string($conn, $_POST['report_end']) : "";
//     $report_as_of = isset($_POST['report_as_of']) ? mysqli_real_escape_string($conn, $_POST['report_as_of']) : "";
//     $manager = isset($_POST['manager']) ? mysqli_real_escape_string($conn, $_POST['manager']) : "";
//     $senior = isset($_POST['senior']) ? mysqli_real_escape_string($conn, $_POST['senior']) : "";
//     $staff = isset($_POST['staff']) ? mysqli_real_escape_string($conn, $_POST['staff']) : "";
//     $leadsheet_due = isset($_POST['leadsheet_due']) ? mysqli_real_escape_string($conn, $_POST['leadsheet_due']) : "";
//     $field_work_week = isset($_POST['field_work_week']) ? mysqli_real_escape_string($conn, $_POST['field_work_week']) : "";
//     $senior_dol = isset($_POST['senior_dol']) ? mysqli_real_escape_string($conn, $_POST['senior_dol']) : "";
//     $staff_1_dol = isset($_POST['staff_1_dol']) ? mysqli_real_escape_string($conn, $_POST['staff_1_dol']) : "";
//     $staff_2_dol = isset($_POST['staff_2_dol']) ? mysqli_real_escape_string($conn, $_POST['staff_2_dol']) : "";

//     // Check if client already exists
//     $engagement_select = "SELECT * FROM engagement WHERE idno = '$idno'";
//     $engagement_result = mysqli_query($conn, $engagement_select);
//     if (mysqli_num_rows($engagement_result) > 0) {
//         $error[] = 'Engagement already exists!';
//     } else {
//         // Insert the new engagement into the database
//         $engagement_insert = "INSERT INTO engagement (idno, client_name, engagement_type, year, report_start, report_end, report_as_of, manager, senior, staff, leadsheet_due, field_work_week, senior_dol, staff_1_dol, staff_2_dol)
//             VALUES ('$idno', NULLIF('$client_name', ''), NULLIF('$engagement_type', ''), NULLIF('$year', ''), NULLIF('$report_start', ''), NULLIF('$report_end', ''), NULLIF('$report_as_of', ''), NULLIF('$manager', ''), NULLIF('$senior', ''), NULLIF('$staff', ''), NULLIF('$leadsheet_due', ''), NULLIF('$field_work_week', ''), NULLIF('$senior_dol', ''), NULLIF('$staff_1_dol', ''), NULLIF('$staff_2_dol', ''))";

//         if (mysqli_query($conn, $engagement_insert)) {
//             header('location: ' . BASE_URL . '/');
//             exit;
//         } else {
//             $error[] = 'Error: ' . mysqli_error($conn);
//         }
//     }
// }
?>
