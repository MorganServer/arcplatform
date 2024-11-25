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
    <title>Add Client - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>



    <div class="container" style="background-color: #f2f2f2 !important;">

            <div class="mt-4"></div>

            <form class="row g-3">
                  <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail4">
                  </div>
                  <div class="col-md-6">
                    <label for="inputPassword4" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword4">
                  </div>
                  <div class="col-12">
                    <label for="inputAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                  </div>
                  <div class="col-12">
                    <label for="inputAddress2" class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                  </div>
                  <div class="col-md-6">
                    <label for="inputCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="inputCity">
                  </div>
                  <div class="col-md-4">
                    <label for="inputState" class="form-label">State</label>
                    <select id="inputState" class="form-select">
                      <option selected>Choose...</option>
                      <option>...</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="inputZip">
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gridCheck">
                      <label class="form-check-label" for="gridCheck">
                        Check me out
                      </label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                  </div>
                </form>
                                        
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
