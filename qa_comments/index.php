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
    <title>QA Comments - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- main-container -->
    <div class="container" style="background-color: #f2f2f2 !important;">
        <h2 class="mt-4">
            QA Comments
        </h2>
        <hr>

        <table class="table">
            <thead style="bg-dark text-white">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Engagement</th>
                    <th scope="col">Control Ref</th>
                    <th scope="col">Cell Ref</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Status</th>
                    <th style="width: 100px; text-align: center;">View</th>
                    <?php if ($_SESSION['account_type'] == 'Admin') { ?>
                        <th style="width: 100px; text-align: center;">Edit</th>
                        <th style="width: 100px; text-align: center;">Delete</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pagination variables
                $limit = 10;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Fetching engagement records
                $sql = "SELECT * FROM qa_comments ORDER BY qa_created ASC LIMIT $limit OFFSET $offset";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $num_rows = mysqli_num_rows($result);
                    if ($num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['qa_id'];
                            $idno = $row['idno'];
                            $engagement_id = $row['engagement_id'];
                            $control_ref = $row['control_ref'];
                            $cell_ref = $row['cell_reference'];
                            $comment_by = $row['comment_by'];
                            $status = $row['status'];
                            ?>
                            <tr>
                                <th scope="row"><?php echo $idno; ?></th>

                                <?php
                                $engage_sql = "SELECT * FROM engagement WHERE engagement_id='$engagement_id'";
                                $engage_result = mysqli_query($conn, $engage_sql);
                
                                if ($engage_result) {
                                    $engage_num_rows = mysqli_num_rows($engage_result);
                                    if ($engage_num_rows > 0) {
                                        while ($engage_row = mysqli_fetch_assoc($engage_result)) {
                                            $engage_id = $engage_row['engagement_id'];
                                            $engage_client_name = $engage_row['client_name'];
                                            $engage_year = $engage_row['year'];
                                            $engage_engagement_type = $engage_row['engagement_type'];

                                ?>

                                <td><?php echo $engage_client_name . " - " . $engage_year . " " . $engage_engagement_type ? $engage_client_name . " - " . $engage_year . " " . $engage_engagement_type : '-'; ?></td>
                                <?php }}} ?>
                                <td><?php echo $control_ref ? $control_ref : '-'; ?></td>
                                <td><?php echo $cell_ref ? $cell_ref : '-'; ?></td>
                                <td><?php echo $comment_by ? $comment_by : '-'; ?></td>
                                <td><?php echo $status ? $status : '-'; ?></td>
                                <td style="width: 100px; text-align: center;">
                                    <a href="<?php echo BASE_URL; ?>/engagements/details/?id=<?php echo $engage_id; ?>" class="view">
                                        <i class="bi bi-eye text-success"></i>
                                    </a>
                                </td>
                                <?php if ($_SESSION['account_type'] == 'Admin') { ?>
                                    <td style="width: 100px; text-align: center;">
                                        <i class="bi bi-pencil-square" style="color:#005382;"></i>
                                    </td>
                                    <td style="width: 100px; text-align: center;">
                                        <i class="bi bi-trash" style="color:#941515;"></i>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Error fetching data: " . mysqli_error($conn) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <?php
        // Pagination: Calculate the total number of pages
        $sql = "SELECT COUNT(*) as total FROM engagement WHERE status = 'Open'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total_records = $row["total"];
            $total_pages = ceil($total_records / $limit);

            echo "<!-- Debug: Total records = $total_records, Total pages = $total_pages -->"; 

            echo '<ul class="pagination justify-content-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($page == $i) ? "active" : "";
                echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
            }
            echo '</ul>';
        } else {
            echo "<p>Error calculating total records: " . mysqli_error($conn) . "</p>";
        }
        ?>
    </div>
    <!-- END main-container -->

</body>
</html>
