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
                            <a data-bs-toggle="modal" data-bs-target="#add_backup_config"><i class="bi bi-plus-circle-fill"></i></a>
                        </div>
                    </h5>
                    <p class="card-text">
                        <!-- backup config ul list -->
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
                                                    <a data-bs-toggle="modal" data-bs-target="#edit_backup_config-<?php echo $bu_id; ?>" style="color: #156194 !important; cursor: pointer; text-decoration: none;" class="me-2">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <!-- edit-configuration -->
                                                        <div class="modal fade" id="edit_backup_config-<?php echo $bu_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Backup Configuration</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <?php
                                                                        $one_bu_sql = "SELECT * FROM backup_configs WHERE backup_config_id = '$bu_id'";
                                                                        $one_bu_result = mysqli_query($conn, $one_bu_sql);

                                                                        if (!$one_bu_result) {
                                                                            die('Error executing query: ' . mysqli_error($conn));
                                                                        }
                                                                    
                                                                        $one_bu_num_rows = mysqli_num_rows($one_bu_result);
                                                                        if ($one_bu_num_rows > 0) {
                                                                            while ($one_bu_row = mysqli_fetch_assoc($one_bu_result)) {
                                                                                $one_bu_id = $one_bu_row['backup_config_id']; 
                                                                                $one_bu_config_name = $one_bu_row['config_name'];
                                                                                $one_bu_value = $one_bu_row['value'];
                                                                            
                                                                                $formatted_one_bu_config_name = ucwords(str_replace('_', ' ', $one_bu_config_name));
                                                                        ?>
                                                                        <form method="POST" class="row g-3">
                                                                            <input type="hidden" name="bu_id" value="<?php echo htmlspecialchars($one_bu_id); ?>">
                                                                            <div class="col-md-6">
                                                                                <label for="config_name" class="form-label">Configuration Name</label>
                                                                                <input type="text" class="form-control" id="config_name" name="config_name" value="<?php echo htmlspecialchars($one_bu_config_name); ?>">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="value" class="form-label">Primary Contact</label>
                                                                                <input type="text" class="form-control" id="value" name="value" value="<?php echo htmlspecialchars($one_bu_value); ?>">
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <button type="submit" name="edit_backup_config" class="btn btn-primary">Update Configuration</button>
                                                                            </div>
                                                                        </form>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <!-- end edit-configuration -->
                                                    <a style="color: #941515 !important; cursor: pointer; text-decoration: none;" href="?action=delete_backup_config&bu_id=<?php echo $bu_id; ?>" onclick="return confirm('Are you sure you want to delete this Backup Configuration?');" class="me-2">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </li>


                                <?php
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        <!-- end backup config ul list -->

                        <!-- add-configuration -->
                            <div class="modal fade" id="add_backup_config" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Backup Configuration</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <form method="POST" class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="config_name" class="form-label">Configuration Name</label>
                                                    <input type="text" class="form-control" id="config_name" name="config_name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="value" class="form-label">Primary Contact</label>
                                                    <input type="text" class="form-control" id="value" name="value" >
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="add_backup_config" class="btn btn-primary">Add Configuration</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- end add-configuration -->

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
                        <!-- backup config ul list -->
                        <ul class="list-group list-group-flush">
    <?php
    // Get all the notification users
    $bun_sql = "SELECT bn.backup_notification_id, bn.user_id, bn.notification_type, u.first_name, u.last_name
                FROM backup_notifications bn
                LEFT JOIN users u ON bn.user_id = u.user_id";
                
    $bun_result = mysqli_query($conn, $bun_sql);
    
    if ($bun_result) {
        $bun_rows = [];
        
        // Group users by notification type
        while ($bun_row = mysqli_fetch_assoc($bun_result)) {
            $bun_notification_type = $bun_row['notification_type'];
            $user_full_name = $bun_row['first_name'] . " " . $bun_row['last_name'];
            
            // Create an array for each notification type
            $bun_rows[$bun_notification_type][] = $user_full_name;
        }

        // Loop through the grouped users and display the Success and Failure types
        foreach (['Success', 'Failure'] as $type) {
            if (isset($bun_rows[$type]) && count($bun_rows[$type]) > 0) {
                echo '<li class="list-group-item">';
                echo '<div class="float-start"><strong>' . $type . ':</strong>&nbsp;';

                // Display the first user in the group
                echo $bun_rows[$type][0];

                // Check if there are more than 1 user in this group
                if (count($bun_rows[$type]) > 1) {
                    // Display the +# for additional users
                    $extra_count = count($bun_rows[$type]) - 1;
                    $extra_users = implode(', ', array_slice($bun_rows[$type], 1)); // Get the rest of the users

                    // Create the circle with the number of extra users
                    echo ' <span class="badge bg-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($extra_users) . '">+ ' . $extra_count . '</span>';
                }

                echo '</div>';

                echo '<div class="float-end">';
                // Edit and Delete buttons (These are placeholders for your modal and delete actions)
                echo '<a data-bs-toggle="modal" data-bs-target="#edit_backup_config-<?php echo $bu_id; ?>" style="color: #156194 !important; cursor: pointer; text-decoration: none;" class="me-2">
                        <i class="bi bi-pencil-square"></i>
                    </a>';
                echo '<a style="color: #941515 !important; cursor: pointer; text-decoration: none;" href="?action=delete_backup_config&bu_id=<?php echo $bu_id; ?>" onclick="return confirm('Are you sure you want to delete this Backup Configuration?');" class="me-2">
                        <i class="bi bi-trash"></i>
                    </a>';
                echo '</div>';
                echo '</li>';
            }
        }
    }
    ?>
</ul>
                        <!-- end backup config ul list -->

                        <!-- add-notification -->
                            <div class="modal fade" id="add_backup_config" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Backup Configuration</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <form method="POST" class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="config_name" class="form-label">Configuration Name</label>
                                                    <input type="text" class="form-control" id="config_name" name="config_name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="value" class="form-label">Primary Contact</label>
                                                    <input type="text" class="form-control" id="value" name="value" >
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="add_backup_config" class="btn btn-primary">Add Configuration</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- end add-notification -->

                        <!-- edit-configuration -->
                            <div class="modal fade" id="edit_backup_config-<?php echo $bu_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Backup Configuration</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $one_bu_sql = "SELECT * FROM backup_configs WHERE backup_config_id = '$bu_id'";
                                            $one_bu_result = mysqli_query($conn, $one_bu_sql);
                                            if (!$one_bu_result) {
                                                die('Error executing query: ' . mysqli_error($conn));
                                            }
                                        
                                            $one_bu_num_rows = mysqli_num_rows($one_bu_result);
                                            if ($one_bu_num_rows > 0) {
                                                while ($one_bu_row = mysqli_fetch_assoc($one_bu_result)) {
                                                    $one_bu_id = $one_bu_row['backup_config_id']; 
                                                    $one_bu_config_name = $one_bu_row['config_name'];
                                                    $one_bu_value = $one_bu_row['value'];
                                                
                                                    $formatted_one_bu_config_name = ucwords(str_replace('_', ' ', $one_bu_config_name));
                                            ?>
                                            <form method="POST" class="row g-3">
                                                <input type="hidden" name="bu_id" value="<?php echo htmlspecialchars($one_bu_id); ?>">
                                                <div class="col-md-6">
                                                    <label for="config_name" class="form-label">Configuration Name</label>
                                                    <input type="text" class="form-control" id="config_name" name="config_name" value="<?php echo htmlspecialchars($one_bu_config_name); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="value" class="form-label">Primary Contact</label>
                                                    <input type="text" class="form-control" id="value" name="value" value="<?php echo htmlspecialchars($one_bu_value); ?>">
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="edit_backup_config" class="btn btn-primary">Update Configuration</button>
                                                </div>
                                            </form>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- end edit-configuration -->
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
