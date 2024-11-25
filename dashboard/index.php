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
    <title>Dashboard - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>



    <div class="container" style="background-color: #f2f2f2 !important;">

            <div class="mt-4"></div>

            <!-- Top Card Container -->
                <div class="card-container">
                    <div class="card" style="border-bottom: 3px solid brown; border-radius: 5px !important;">
                        <a class="text-decoration-none text-black stretched-link" href="<?php echo BASE_URL; ?>/engagements/">
                        <div class="card-body text-center">
                            <p class="card-text">
                                <div class="card_text_left float-start" style="font-size: 45px;">
                                    <i class="bi bi-collection-fill ps-4"></i>
                                </div>
                                <div class="card_text_right float-end pe-3">
                                    <h2 class="text-end">
                                        <?php
                                            $sql="SELECT count('1') FROM engagement WHERE status='Open'";
                                            $result=mysqli_query($conn,$sql);
                                            $rowtotal=mysqli_fetch_array($result); 
                                            if($rowtotal[0] < 10) {
                                                echo "0$rowtotal[0]";
                                            } else {
                                                echo "$rowtotal[0]";
                                            }
                                        ?>
                                    </h2>
                                    <p class="text-muted text-end">Open Engagements</p>
                                </div>
                            </p>
                        </div>
                        </a>
                    </div>
                    <div class="card" style="border-bottom: 3px solid gray; border-radius: 5px !important;">
                        <a class="text-decoration-none text-black stretched-link" href="<?php echo BASE_URL; ?>/clients/">
                        <div class="card-body text-center">
                            <p class="card-text">
                                <div class="card_text_left float-start" style="font-size: 45px;">
                                    <i class="bi bi-people-fill ps-4"></i>
                                </div>
                                <div class="card_text_right float-end pe-3">
                                    <h2 class="text-end">
                                        <?php
                                            $sql="SELECT count('1') FROM clients";
                                            $result=mysqli_query($conn,$sql);
                                            $rowtotal=mysqli_fetch_array($result); 
                                            if($rowtotal[0] < 10) {
                                                echo "0$rowtotal[0]";
                                            } else {
                                                echo "$rowtotal[0]";
                                            }
                                        ?>
                                    </h2>
                                    <p class="text-muted text-end">Clients</p>
                                </div>
                            </p>
                        </div>
                        </a>
                    </div>
                    <div class="card" style="border-bottom: 3px solid purple; border-radius: 5px !important;">
                        <a class="text-decoration-none text-black stretched-link" href="<?php //echo BASE_URL; ?>">
                        <div class="card-body text-center">
                            <p class="card-text">
                                <div class="card_text_left float-start" style="font-size: 45px;">
                                    <i class="bi bi-chat-square-text-fill ps-4"></i>
                                </div>
                                <div class="card_text_right float-end pe-3">
                                    <h2 class="text-end">
                                        <?php
                                            $sql="SELECT count('1') FROM qa_comments WHERE status!='Completed'";
                                            $result=mysqli_query($conn,$sql);
                                            $rowtotal=mysqli_fetch_array($result); 
                                            if($rowtotal[0] < 10) {
                                                echo "0$rowtotal[0]";
                                            } else {
                                                echo "$rowtotal[0]";
                                            }
                                        ?>
                                    </h2>
                                    <p class="text-muted text-end">QA Comments Open</p>
                                </div>
                            </p>
                        </div>
                        </a>
                    </div>
                    <div class="card" style="border-bottom: 3px solid orange; border-radius: 5px !important;">
                        <a class="text-decoration-none text-black stretched-link" href="<?php echo BASE_URL; ?>/engagements/">
                        <div class="card-body text-center">
                            <p class="card-text">
                                <div class="card_text_left float-start" style="font-size: 45px;">
                                    <i class="bi bi bi-clipboard2-check-fill ps-4"></i>
                                </div>
                                <div class="card_text_right float-end pe-3">
                                    <h2 class="text-end">
                                        <?php
                                            $sql="SELECT count('1') FROM engagement WHERE status='Completed'";
                                            $result=mysqli_query($conn,$sql);
                                            $rowtotal=mysqli_fetch_array($result); 
                                            if($rowtotal[0] < 10) {
                                                echo "0$rowtotal[0]";
                                            } else {
                                                echo "$rowtotal[0]";
                                            }
                                        ?>
                                    </h2>
                                    <p class="text-muted text-end">Completed Enagements</p>
                                </div>
                            </p>
                        </div>
                        </a>
                    </div>
                </div>
            <!-- end Top Card Container -->
                                        
        </div>

    </div>

    <script>
            // JavaScript to handle dropdown selection and update client name
document.getElementById('e_engagement_id').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const clientName = selectedOption.getAttribute('data-client-name');

    // Set the client name input field
    document.getElementById('client_name').value = clientName || '';
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
