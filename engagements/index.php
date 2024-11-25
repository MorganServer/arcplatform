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
    <title>Engagements - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- main-container -->
    <div class="container" style="background-color: #f2f2f2 !important;">
            <h2 class="mt-4">
                Engagements
            </h2>
            <hr>

            <table class="table">
    <thead style="bg-dark text-white">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Client</th>
            <th scope="col">Year</th>
            <th scope="col">Type</th>
            <th scope="col">Open QA Comments</th>
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
        $sql = "SELECT * FROM engagement WHERE status = 'Open' ORDER BY engagement_created DESC LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $num_rows = mysqli_num_rows($result);
            echo "<!-- Debug: Number of records fetched: $num_rows -->"; // Debugging output
            if ($num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['engagement_id'];
                    $idno = $row['idno'];
                    $status = $row['status'];
                    $client_name = $row['client_name'];
                    $year = $row['year'];
                    $engagement_type = $row['engagement_type'];

                    echo "<!-- Debug: SQL = $sql -->";
                    ?>
                    <tr>
                        <th scope="row"><?php echo $idno; ?></th>
                        <td><?php echo $client_name ? $client_name : '-'; ?></td>
                        <td><?php echo $year ? $year : '-'; ?></td>
                        <td><?php echo $engagement_type ? $engagement_type : '-'; ?></td>
                        <td>
                            <?php
                            $sql = "SELECT COUNT(1) FROM qa_comments WHERE client_name='$client_name' AND status != 'Completed'";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                $rowtotal = mysqli_fetch_array($result);
                                echo ($rowtotal[0] < 10) ? "0$rowtotal[0]" : "$rowtotal[0]";
                            } else {
                                echo "Error in query execution: " . mysqli_error($conn);
                            }
                            ?>
                        </td>
                        <td style="width: 100px; text-align: center;">
                            <a href="<?php echo BASE_URL; ?>/engagements/details/?id=<?php echo $id; ?>" class="view">
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
                echo "<p>No records found.</p>";
            }
        } else {
            echo "<p>Error fetching data: " . mysqli_error($conn) . "</p>";
        }
        ?>
    </tbody>
</table>
<br>

<!-- Pagination Links -->
<?php
// Get the total number of records
$sql = "SELECT COUNT(*) as total FROM engagement WHERE status = 'Open'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_records = $row["total"];
$total_pages = ceil($total_records / $limit);

echo "<!-- Debug: Total records = $total_records, Total pages = $total_pages -->"; // Debugging output


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
