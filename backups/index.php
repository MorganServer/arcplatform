<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}

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
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Backup Configurations - ARC Platform</title>
</head>
<body>


    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- main-container -->
        <div class="" style="padding: 0 20px 0 20px;">
            <h2 class="mt-4">
                Backup Configurations
            </h2>
            
            <hr>

            <div class="card-container">
                <div class="card details_card me-5" style="width: 20rem;">
                  <div class="card-body">
                    <h5 class="card-title">Backup Configurations</h5>
                    <p class="card-text">
                                                
                    </p>
                  </div>
                </div>
                
                <div class="card details_card me-5" style="width: 38rem;">
                  <div class="card-body">
                    <h5 class="card-title">Backup Logs</h5>
                    <div class="mt-3"></div>
                    <p class="card-text">
                        <div class="">
                        

                        </div>
                    </p>
                  </div>
                </div>
                <div class="card details_card" style="width: 20rem;">
                  <div class="card-body">
                    <h5 class="card-title">Backup Notifications</h5>
                    <p class="card-text">
                        
                    </p>
                  </div>
                </div>
            </div>
            


        </div>
    <!-- END main-container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
