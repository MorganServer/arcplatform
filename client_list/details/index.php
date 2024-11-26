<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php"; // Ensure this is correct
require_once "../../path.php";
require_once "../../app/functions/logout.php";
require_once "../../app/functions/session_helpers.php";
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Trigger the logout function
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    logoutUser($conn);
}

redirectIfNotLoggedIn();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Client Details - ARC Platform</title>


</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>


    <!-- main-container -->
        <div class="" style="padding: 0 20px 0 20px;">
            <div class="mt-4"></div>
            <a class="text-decoration-none" href="<?php BASE_URL; ?>/client_list"><i class="bi bi-arrow-left"></i>&nbsp; Back to Client List</a>


            <?php
            $id = $_GET['id'];
            $client_sql = "SELECT * FROM clients WHERE client_id = $id";
            $client_result = mysqli_query($conn, $client_sql);
            if($client_result) {
            $client_num_rows = mysqli_num_rows($client_result);
            if($client_num_rows > 0) {
                while ($client_row = mysqli_fetch_assoc($client_result)) {
                    $client_id                     = $client_row['client_id']; 
                    $client_client_name            = $client_row['client_name']; 
                    $client_primary_contact        = $client_row['primary_contact']; 
                    $client_contact_email          = $client_row['contact_email']; 


                    // Split the name into parts and get initials
                    $name_parts = explode(" ", $client_primary_contact);
                    $first_initial = isset($name_parts[0]) ? strtoupper($name_parts[0][0]) : '';
                    $last_initial = isset($name_parts[1]) ? strtoupper($name_parts[1][0]) : '';
                    $primary_contact_initials = $first_initial . $last_initial;                    

                    // $formatted_start = date("m/d/Y", strtotime($off_report_start));
                    // $formatted_end = date("m/d/Y", strtotime($off_report_end));
                    // $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
                }}}
            // }}
            ?>
            
            <div class="mt-2"></div>
            <div class="card-container">
                <div class="card details_card" style="width: 100%;">
                  <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text" style="padding-top: 10px; padding-left: 5px;">
                        <h6>
                            Primary Contact
                        </h6>

                        <div class="auditor-info">
                            <div class="circle"><?php echo htmlspecialchars($primary_contact_initials); ?></div>
                            <div class="name-bg">
                                <span class="name"><?php echo htmlspecialchars($client_primary_contact); ?></span>
                            </div>
                        </div>

                        
                        
                    </p>
                  </div>
                </div>
            </div>


            <?php
            $id = $_GET['id'];
            $off_sql = "SELECT * FROM engagement WHERE engagement_id = $id";
            $off_result = mysqli_query($conn, $off_sql);
            if($off_result) {
            $num_rows = mysqli_num_rows($off_result);
            if($num_rows > 0) {
                while ($off_row = mysqli_fetch_assoc($off_result)) {
                    $off_id                     = $off_row['engagement_id']; 
                    $off_client_name            = $off_row['client_name']; 
                    $off_engagement_type        = $off_row['engagement_type']; 
                    $off_year                   = $off_row['year']; 
                    $off_report_start           = $off_row['report_start']; 
                    $off_report_end             = $off_row['report_end']; 
                    $off_report_as_of           = $off_row['report_as_of']; 
                    $off_manager                = $off_row['manager']; 
                    $off_senior                 = $off_row['senior']; 
                    $off_staff                  = $off_row['staff']; 
                    $off_status                 = $off_row['status']; 


                    // Split the name into parts and get initials

                    

                    $formatted_start = date("m/d/Y", strtotime($off_report_start));
                    $formatted_end = date("m/d/Y", strtotime($off_report_end));
                    $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
                }}}
            // }}
            ?>
                

        </div>
    <!-- END main-container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
