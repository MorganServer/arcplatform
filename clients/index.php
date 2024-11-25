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
                    <th scope="col">Number of Engagements</th>
                    <th scope="col">Open QA Comments</th>
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

                    // Query to get clients, the total number of engagements, and open QA comments for each client
                    $sql = "SELECT * FROM clients ORDER BY client_created DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $num_rows = mysqli_num_rows($result);
                        if ($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $idno = $row['idno'];
                                $id = $row['client_id'];
                                $client_name = $row['client_name'];
                                $total_engagements = $row['total_engagements'];
                                $total_open_qa_comments = $row['total_open_qa_comments'];  // Get total open QA comments
                                ?>

                                <tr>
                                    <th scope="row"><?php echo $idno; ?></th>
                                    <td><?php echo $client_name ? $client_name : '-'; ?></td>
                                    <td>
                                        <?php
                                            // Counting engagements for the client
                                            $sql_engagements = "SELECT COUNT(*) FROM engagement WHERE client_name='$client_name'";
                                            $result_engagements = mysqli_query($conn, $sql_engagements);
                                            if ($result_engagements) {
                                                $rowtotal = mysqli_fetch_array($result_engagements);
                                                echo ($rowtotal[0] < 10) ? "0$rowtotal[0]" : "$rowtotal[0]";
                                            } else {
                                                echo "Error in query: " . mysqli_error($conn);
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            // Counting open QA comments for the client
                                            $sql_qa = "SELECT COUNT(*) FROM qa_comments WHERE client_name='$client_name' AND status != 'Completed'";
                                            $result_qa = mysqli_query($conn, $sql_qa);
                                            if ($result_qa) {
                                                $rowtotal = mysqli_fetch_array($result_qa);
                                                echo ($rowtotal[0] < 10) ? "0$rowtotal[0]" : "$rowtotal[0]";
                                            } else {
                                                echo "Error in query: " . mysqli_error($conn);
                                            }
                                        ?>
                                    </td>

                                    <td style="width: 100px; text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>" class="view">
                                            <i class="bi bi-eye text-success"></i>
                                        </a> 
                                    </td>
                                    <td style="width: 100px; text-align: center;">
                                        <i class="bi bi-pencil-square" style="color:#005382;"></i>
                                    </td>
                                    <td style="width: 100px; text-align: center;">
                                        <i class="bi bi-trash" style="color:#941515;"></i>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error fetching data: " . mysqli_error($conn) . "</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
        <br>
        <?php
            // Pagination links
            $sql_total = "SELECT COUNT(*) as total FROM clients";
            $result_total = mysqli_query($conn, $sql_total);
            if ($result_total) {
                $row = mysqli_fetch_assoc($result_total);
                $total_pages = ceil($row["total"] / $limit);
            } else {
                $total_pages = 1;
            }

            echo '<ul class="pagination justify-content-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($page == $i) ? "active" : "";
                echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
            }
            echo '</ul>';
        ?>

        </div>
    <!-- END main-container -->

</body>
</html>
