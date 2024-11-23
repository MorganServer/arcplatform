<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}


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

    <?php include(BASE_URL . "/app/includes/header.php"); ?>
    <?php include(BASE_URL . "/app/includes/sub_header.php"); ?>



<div class="content pt-5 d-flex">
    <div class="row mx-auto">
        <div class="card me-5" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">25</p>
            <h5 class="card-title text-secondary">Open Engagements</h5>
          </div>
        </div>

        <div class="card me-5" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">25</p>
            <h5 class="card-title text-secondary">Open QA Comments</h5>
          </div>
        </div>

        <div class="card" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">25</p>
            <h5 class="card-title text-secondary">Tasks</h5>
          </div>
        </div>
    </div>
</div>

<?php echo $_SESSION['email']; ?>
</body>
</html>
