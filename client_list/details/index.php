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
                <div class="card details_card" style="width: 100%; height: 220px;">
                  <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">
                        <div style="padding-top: 15px; padding-left: 25px;">
                            <h6 class="mb-4" style="font-weight: bold;">
                                Primary Contacts (1)
                            </h6>

                            <div class="auditor-info">
                                <div class="circle"><?php echo htmlspecialchars($primary_contact_initials); ?></div>
                                <div class="name-bg">
                                    <span class="name"><?php echo htmlspecialchars($client_primary_contact); ?></span>
                                </div>
                            </div>
                        </div>
                    </p>
                  </div>
                </div>
            </div>



            


            <div class="accordion accordion-flush" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>
                                Active Engagements   
                            </h4>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <?php
                                $off_sql = "SELECT * FROM engagement WHERE client_name = '$client_client_name' && status = 'Active'";
                                $off_result = mysqli_query($conn, $off_sql);

                                if ($off_result) {
                                    $num_rows = mysqli_num_rows($off_result);
                                
                                    if ($num_rows > 0) {
                                        while ($off_row = mysqli_fetch_assoc($off_result)) {
                                            $off_id = $off_row['engagement_id'];
                                            $off_client_name = $off_row['client_name'];
                                            $off_engagement_type = $off_row['engagement_type'];
                                            $off_year = $off_row['year'];
                                            $off_report_start = $off_row['report_start'];
                                            $off_report_end = $off_row['report_end'];
                                            $off_report_as_of = $off_row['report_as_of'];
                                            $off_fieldwork_week = $off_row['field_work_week'];
                                            $off_leadsheets_due = $off_row['leadsheets_due'];
                                        
                                            // Format the dates
                                            $formatted_start = date("m/d/Y", strtotime($off_report_start));
                                            $formatted_end = date("m/d/Y", strtotime($off_report_end));
                                            $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
                                            $formatted_fw_week = date("m/d/Y", strtotime($off_fieldwork_week));
                                            $formatted_leadsheets_due = date("m/d/Y", strtotime($off_leadsheets_due));
                                ?>
                                            <div class="col-md-4 mb-4"> <!-- Make the cards responsive and inline -->
                                                <div class="card" style="width: 30rem;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <!-- Left section for the image -->
                                                            <div class="left">
                                                                <?php if (strpos($off_engagement_type, 'SOC 2') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/soc-2-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'SOC 1') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/soc-1-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'HIPAA') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/hipaa-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'PCI') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/pci-icon.png" width="90" alt="">
                                                                <?php } ?>
                                                            </div>
                                                                
                                                            
                                                            <div class="right ms-4 d-flex flex-column align-items-start">
                                                                <h5 class="card-title text-start" style="margin-bottom: -15px;"><?php echo $off_engagement_type; ?></h5>
                                                                <p class="card-text text-start" style="font-size: 14px !important;">
                                                                    <?php if(!isset($off_report_as_of)) { ?>
                                                                        <span class="text-secondary"><strong>Audit Period:&nbsp;&nbsp;</strong></span><?php echo $formatted_start; ?> - <?php echo $formatted_end; ?>
                                                                    <?php } else { ?>
                                                                        <span class="text-secondary"><strong>Audit Period:&nbsp;&nbsp;</strong></span>As of <?php echo $formatted_as_of; ?>
                                                                    <?php } ?> 
                                                                    <br>
                                                                    <span class="d-block mt-2" style="font-size: 14px !important;">
                                                                        <span class="text-secondary"><strong>Fieldwork Week:&nbsp;&nbsp;</strong></span><?php echo $formatted_fw_week; ?>
                                                                    </span>
                                                                    <br>
                                                                    <!-- Adjusted spacing here with mt-1 for smaller margin -->
                                                                    <span class="d-block" style="font-size: 14px !important; margin-top: -12px;">
                                                                        <span class="text-secondary"><strong>Leadsheets Due:&nbsp;&nbsp;</strong></span><?php echo $formatted_leadsheets_due; ?>
                                                                    </span>
                                                                </p>


                                                                    
                                                            </div>

                                                        </div>

                                                        
                                                        
                                                        
                                                    </div>
                                                    <div class="card-footer" style="background-color: transparent; padding: 15px 15px;">
                                                        <a data-bs-toggle="modal" data-bs-target="#download_modal" class="card-link text-decoration-none" style="cursor: pointer;"><i class="bi bi-download"></i>  Summary Report</a>
                                                        <a href="<?php echo BASE_URL; ?>/engagements/details/?id=<?php echo $off_id; ?>" class="card-link float-end text-decoration-none" style="cursor: pointer;">Open Engagement  <i class="bi bi-chevron-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- MODAL -->
                                                <div class="modal fade" id="download_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Download QA Comment Report</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <form action="<?php BASE_URL; ?>/app/comment_report.php" method="POST" class="row g-3 p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="New" id="newComments" name="options[]">
                                                                <label class="form-check-label" for="newComments">
                                                                    New Comments
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="Follow-Up" id="followUpComments" name="options[]">
                                                                <label class="form-check-label" for="followUpComments">
                                                                    Follow-Up Comments
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="Completed" id="completedComments" name="options[]">
                                                                <label class="form-check-label" for="completedComments">
                                                                    Completed Comments
                                                                </label>
                                                            </div>
                                                            <input type="hidden" name="e_id" value="<?php echo $off_id; ?>"> <!-- Replace with dynamic ID -->
                                                            <div class="col-12 pt-3">
                                                                <button type="submit" class="btn btn-primary"><i class="bi bi-download"></i>&nbsp;&nbsp;Comment Report</button>
                                                            </div>
                                                        </form>

                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            <!-- end MODAL -->
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4>
                        Completed Engagements
                    </h4>
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                  <div class="row">
                                <?php
                                $off_sql = "SELECT * FROM engagement WHERE client_name = '$client_client_name' && status = 'Completed'";
                                $off_result = mysqli_query($conn, $off_sql);

                                if ($off_result) {
                                    $num_rows = mysqli_num_rows($off_result);
                                
                                    if ($num_rows > 0) {
                                        while ($off_row = mysqli_fetch_assoc($off_result)) {
                                            $off_id = $off_row['engagement_id'];
                                            $off_client_name = $off_row['client_name'];
                                            $off_engagement_type = $off_row['engagement_type'];
                                            $off_year = $off_row['year'];
                                            $off_report_start = $off_row['report_start'];
                                            $off_report_end = $off_row['report_end'];
                                            $off_report_as_of = $off_row['report_as_of'];
                                            $off_fieldwork_week = $off_row['field_work_week'];
                                            $off_leadsheets_due = $off_row['leadsheets_due'];
                                        
                                            // Format the dates
                                            $formatted_start = date("m/d/Y", strtotime($off_report_start));
                                            $formatted_end = date("m/d/Y", strtotime($off_report_end));
                                            $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
                                            $formatted_fw_week = date("m/d/Y", strtotime($off_fieldwork_week));
                                            $formatted_leadsheets_due = date("m/d/Y", strtotime($off_leadsheets_due));
                                ?>
                                            <div class="col-md-4 mb-4"> <!-- Make the cards responsive and inline -->
                                                <div class="card" style="width: 30rem;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <!-- Left section for the image -->
                                                            <div class="left">
                                                                <?php if (strpos($off_engagement_type, 'SOC 2') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/soc-2-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'SOC 1') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/soc-1-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'HIPAA') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/hipaa-icon.png" width="90" alt="">
                                                                <?php } else if (strpos($off_engagement_type, 'PCI') !== false) { ?>
                                                                    <img src="<?php echo BASE_URL; ?>/assets/images/pci-icon.png" width="90" alt="">
                                                                <?php } ?>
                                                            </div>
                                                                
                                                            
                                                            <div class="right ms-4 d-flex flex-column align-items-start">
                                                                <h5 class="card-title text-start" style="margin-bottom: -15px;"><?php echo $off_engagement_type; ?></h5>
                                                                <p class="card-text text-start" style="font-size: 14px !important;">
                                                                    <?php if(!isset($off_report_as_of)) { ?>
                                                                        <span class="text-secondary"><strong>Audit Period:&nbsp;&nbsp;</strong></span><?php echo $formatted_start; ?> - <?php echo $formatted_end; ?>
                                                                    <?php } else { ?>
                                                                        <span class="text-secondary"><strong>Audit Period:&nbsp;&nbsp;</strong></span>As of <?php echo $formatted_as_of; ?>
                                                                    <?php } ?> 
                                                                    <br>
                                                                    <span class="d-block mt-2" style="font-size: 14px !important;">
                                                                        <span class="text-secondary"><strong>Fieldwork Week:&nbsp;&nbsp;</strong></span><?php echo $formatted_fw_week; ?>
                                                                    </span>
                                                                    <br>
                                                                    <!-- Adjusted spacing here with mt-1 for smaller margin -->
                                                                    <span class="d-block" style="font-size: 14px !important; margin-top: -12px;">
                                                                        <span class="text-secondary"><strong>Leadsheets Due:&nbsp;&nbsp;</strong></span><?php echo $formatted_leadsheets_due; ?>
                                                                    </span>
                                                                </p>


                                                                    
                                                            </div>

                                                        </div>

                                                        
                                                        
                                                        
                                                    </div>
                                                    <div class="card-footer" style="background-color: transparent; padding: 15px 15px;">
                                                        <a href="<?php echo BASE_URL; ?>/engagements/details/?id=<?php echo $off_id; ?>" class="card-link float-end text-decoration-none" style="cursor: pointer;">Open Engagement  <i class="bi bi-chevron-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                    
                  

                  </div>
                </div>
              </div>
            </div>




            
                

        </div>
    <!-- END main-container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
