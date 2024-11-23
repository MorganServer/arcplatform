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
    <title>Clients - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- main-container -->
    <div class="container" style="padding: 0 5px 0 5px;">
            <h2 class="mt-4">
                Clients
            </h2>
            <hr>

            <table class="table">
            <thead style="bg-dark text-white">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Total Number of Engagements</th>
                    <th style="width: 100px; text-align: center;">View</th>
                    <th style="width: 100px; text-align: center;">Edit</th>
                    <th style="width: 100px; text-align: center;">Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Pagination variables
                $limit = 10; 
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Query to get clients and the total number of engagements for each client
                $sql = "
                    SELECT c.client_id, c.client_name, COUNT(e.engagement_id) AS total_engagements
                    FROM clients c
                    LEFT JOIN engagement e ON c.client_id = e.client_id
                    GROUP BY c.client_id
                    ORDER BY c.client_created DESC
                    LIMIT $limit OFFSET $offset
                ";
                $result = mysqli_query($conn, $sql);

                if($result) {
                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id               = $row['client_id'];
                            $client_name      = $row['client_name'];
                            $total_engagements = $row['total_engagements'];
                ?>
                <tr>
                    <th scope="row"><?php echo $idno; ?></th>
                    <td><?php echo $client_name ? $client_name : '-'; ?></td>
                    <td><?php echo $total_engagements ? $total_engagements : '-'; ?></td>
                    <td style="width: 100px; text-align: center;">
                        <a href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>" class="view">
                            <i class="bi bi-eye text-success"></i>
                        </a> 
                    </td>
                    <td style="width: 100px; text-align: center;">
                        <!-- <a href="<?php //echo BASE_URL; ?>/asset/update/?id=<?php //echo $id; ?>"> -->
                            <i class="bi bi-pencil-square" style="color:#005382;"></i>
                        </a> 
                    </td>
                    <td style="width: 100px; text-align: center;">
                        <!-- <a href="<?php //echo BASE_URL; ?>/asset/delete/?id=<?php //echo $id; ?>" class="delete"> -->
                            <i class="bi bi-trash" style="color:#941515;"></i>
                        </a>
                    </td>
                </tr>
                <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <br>
        <?php
            // Pagination links
            $sql = "SELECT COUNT(*) as total FROM clients";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_pages = ceil($row["total"] / $limit);

                echo '<ul class="pagination justify-content-center">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($page == $i) ? "active" : "";
                    echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
                }
                echo '</ul>';
        ?>


        </div>
    <!-- END main-container -->



    <!-- <div class="content pt-5 d-flex">
    </div> -->

</body>
</html>
