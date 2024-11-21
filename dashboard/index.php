<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashbaord - ARC Platform</title>
</head>
<body>

<div class="header" style="overflow: auto; margin-bottom: 5px;">
    <div style="float: left; padding-left: 20px;">
        <img src="../assets/images/logo.png" width="250" alt="">
    </div>
    <div style="float: right;">
        <div class="nav">
            <ul class="nav-list">
                <li class="nav-list-item"><a class="nav-list-item-link" href="">Dashboard</a></li>
                <li class="nav-list-item"><a class="nav-list-item-link" href="">Engagements</a></li>
                <li class="nav-list-item"><a class="nav-list-item-link" href="">Clients</a></li>
                <li class="nav-list-item"><a class="nav-list-item-link" href="">Settings</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="page_header">
    <div class="left">
        <h6>Dashboard</h6>
    </div>
    <div class="right">
        <p style="padding-right: 15px;">Welcome, Garrett Morgan</p>
        <a class="header-icon" href=""><i class="bi bi-info-circle-fill"></i></a>
        <a class="header-icon" href=""><i class="bi bi-lock-fill"></i></a>
        <a class="header-icon" href=""><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>


<div class="content pt-5 d-flex">

    <div class="row mx-auto">
        <div class="card me-3" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">
                25
            </p>
            <h5 class="card-title text-secondary">Open Engagements</h5>
          </div>
        </div>

        <div class="card me-3" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">
                25
            </p>
            <h5 class="card-title text-secondary">Open QA Comments</h5>
          </div>
        </div>

        <div class="card" style="width: 18rem;">
          <div class="card-body text-center">
            <p style="font-size: 35px;">
                25
            </p>
            <h5 class="card-title text-secondary">Tasks</h5>
          </div>
        </div>
    
    </div>

</div>




    
</body>
</html>