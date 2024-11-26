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
                                $off_sql = "SELECT * FROM engagement WHERE client_name = '$client_client_name'";
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
                                        
                                            // Format the dates
                                            $formatted_start = date("m/d/Y", strtotime($off_report_start));
                                            $formatted_end = date("m/d/Y", strtotime($off_report_end));
                                            $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
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
                                                                
                                                            
                                                            <div class="right d-flex flex-column align-items-start">
                                                                <h5 class="card-title text-start"><?php echo $off_engagement_type; ?></h5>
                                                                <p class="card-text text-start">
                                                                    <?php if(!isset($off_report_as_of)) { ?>
                                                                        <strong>Audit Period: </strong><br><?php echo $formatted_start; ?> - <?php echo $formatted_end; ?>
                                                                    <?php } else { ?>
                                                                        <strong>Audit Period: </strong><br>As of <?php echo $formatted_as_of; ?>
                                                                    <?php } ?>
                                                                </p>
                                                            </div>

                                                        </div>

                                                        
                                                        
                                                        
                                                    </div>
                                                    <div class="card-footer" style="background-color: transparent; padding: 15px 15px;">
                                                            <a href="#" class="card-link text-decoration-none">View Details</a>
                                                            <a href="#" class="card-link float-end text-decoration-none">Another Action</a>
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

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Accordion Item #2
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Accordion Item #3
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                  </div>
                </div>
              </div>
            </div>


            
                

        </div>
    <!-- END main-container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
