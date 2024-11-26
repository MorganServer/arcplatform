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
            
            <div class="mt-2"></div>
            <div class="card-container">
                <div class="card details_card" style="width: 100%;">
                  <div class="card-body">
                    <h5 class="card-title">Engagements Resources</h5>
                    <p class="card-text">
                        <h6>
                            QA Comment Report
                        </h6>
                        <div class="btn-group mt-2">
                          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#download_modal"><i class="bi bi-download"></i> Comment Report</button>
                        </div>

                        
                        
                    </p>
                  </div>
                </div>
            </div>
                

        </div>
    <!-- END main-container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
