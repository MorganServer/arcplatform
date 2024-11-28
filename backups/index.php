<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        <div class="container"  style="background-color: #f2f2f2 !important; padding: 0 20px 0 20px;">
            <h2 class="mt-4">
                Backup Configurations
            </h2>
            
            <hr>

            <div class="card-container">
                <div class="card details_card" style="width: 38rem;">
                  <div class="card-body">
                    <h5 class="card-title">
                        Backup Configurations
                        <div class="float-end">
                            <a href=""><i class="bi bi-plus-circle-fill"></i></a>
                        </div>
                    </h5>
                    <p class="card-text">
                        <ul class="list-group list-group-flush">
                            <?php
                            $bu_sql = "SELECT * FROM backup_configs";
                            $bu_result = mysqli_query($conn, $bu_sql);
                            if ($bu_result) {
                                $bu_num_rows = mysqli_num_rows($bu_result);
                                if ($bu_num_rows > 0) {
                                    while ($bu_row = mysqli_fetch_assoc($bu_result)) {
                                        $bu_id = $bu_row['backup_config_id']; 
                                        $bu_config_name = $bu_row['config_name'];
                                        $bu_value = $bu_row['value'];
                                    
                                        $formatted_bu_config_name = ucwords(str_replace('_', ' ', $bu_config_name));
                            ?>
                                        <li class="list-group-item">
                                            <div class="float-start">
                                                <strong>
                                                    <?php echo $formatted_bu_config_name; ?>:&nbsp;
                                                </strong>
                                                <?php echo $bu_value; ?>
                                            </div>
                                            <div class="float-end">
                                    
                                            </div>
                                        </li>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </ul>

                    </p>
                  </div>
                </div>
                <div class="card details_card" style="width: 38rem;">
                  <div class="card-body">
                    <h5 class="card-title">
                        Backup Notifications
                        <div class="float-end">
                            <a href=""><i class="bi bi-plus-circle-fill"></i></a>
                        </div>
                    </h5>
                    <p class="card-text">
                        
                    </p>
                  </div>
                </div>
            </div>

            <div class="mt-3"></div>

            <div class="card details_card" style="width: 100%;">
              <div class="card-body">
                <h5 class="card-title">Backup Logs</h5>
                <div class="mt-3"></div>
                <p class="card-text">
                    <div class="">
                    
                    </div>
                </p>
              </div>
            </div>
            


        </div>
    <!-- END main-container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
