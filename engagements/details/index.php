<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php";
require_once "../../path.php";
session_start();

$files = glob("../../app/functions/*.php");
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
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Engagement Details - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- php code for getting asset details -->
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
                    

                    // $today = date('Y-m-d');
                    // $is_today = ($off_audit_schedule == $today) ? true : false;
                }
            // }}
            ?>
        <!-- end php code for getting asset details -->

    <!-- main-container -->
        <div class="container" style="">
            <a class="text-decoration-none" href="<?php BASE_URL; ?>/engagements"><i class="bi bi-arrow-left"></i>&nbsp; Back to Engagements</a>
            <br>
            <div class="mt-5"></div>
            <div class="detail-section d-flex justify-content-between">
                <div class="engagement-client-details">
                    <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                </div>
                <div class="audit-period">
                    <strong>Audit Period: </strong><br><?php echo $off_report_start; ?> - <?php echo $off_report_end; ?>
                </div>
                <div class="complete-button">
                    <button type="button" class="btn btn-outline-primary"><i class="bi bi-check2-circle"></i> Complete Engagement</button>
                </div>
            </div>

            <div class="card-container">
                <div class="card" style="width: 18rem;">
                  <img src="..." class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>
                <div class="card" style="width: 18rem;">
                  <img src="..." class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>
                <div class="card" style="width: 18rem;">
                  <img src="..." class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>
            </div>


        <?php }
        } ?>

        </div>
    <!-- END main-container -->



</body>
</html>
