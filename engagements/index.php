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

// Handle insert follow-up comment logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $qa_id = $_POST['qa_id'] ?? null;
    $engagement_id = $_POST['engagement_id'] ?? null;
    $followup_comment = $_POST['followup_comment'] ?? null;
    $followup_owner = $_POST['followup_owner'] ?? null;

    // Check for missing data
    if ($qa_id && $engagement_id && $followup_comment && $followup_owner) {
        // Generate a unique ID for the comment
        do {
            $idno = rand(100000, 999999);
            $checkQuery = "SELECT idno FROM followup_qa_comments WHERE idno = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("i", $idno);
            $stmt->execute();
            $stmt->store_result();
        } while ($stmt->num_rows > 0);

        // Insert the follow-up comment
        $insertQuery = "INSERT INTO followup_qa_comments (idno, qa_id, engagement_id, followup_comment, followup_owner) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iiiss", $idno, $qa_id, $engagement_id, $followup_comment, $followup_owner);
        $stmt->execute();

        // Fetch the newly inserted follow-up comment
        $followupSql = "SELECT * FROM followup_qa_comments WHERE idno = ?";
        $stmt = $conn->prepare($followupSql);
        $stmt->bind_param("i", $idno);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // Format and return the new comment
            $comment = htmlspecialchars($row['followup_comment']);
            $createdAt = date("F j, Y, g:i a", strtotime($row['followup_created']));
            $owner = htmlspecialchars($row['followup_owner']);

            echo "
            <div class='comment'>
                <div class='comment-header'>
                    <span class='comment-time'>$createdAt</span>
                    <span class='comment-author'>$owner</span>
                </div>
                <div class='comment-body mt-2'>
                    <p>$comment</p>
                </div>
            </div>";
        } else {
            echo "<p>Error: Unable to retrieve the comment after insertion.</p>";
        }
    } else {
        echo "<p>Error: Missing required fields.</p>";
    }
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
                    
                    $sql = "SELECT * FROM engagement WHERE status = 'Open' ORDER BY engagement_created DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $num_rows = mysqli_num_rows($result);
                        if($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id                     = $row['engagement_id'];
                                $idno                   = $row['idno'];
                                $status                 = $row['status'];
                                $client_name            = $row['client_name'];
                                $year                   = $row['year'];
                                $engagement_type        = $row['engagement_type'];
                             

                                // Format maintenance schedule if not null
                                // $f_maintenance_schedule = !empty($maintenance_schedule) ? date_format(date_create($maintenance_schedule), 'M d, Y') : '-';

                                // Format audit schedule if not null
                                // $f_audit_schedule = !empty($audit_schedule) ? date_format(date_create($audit_schedule), 'M d, Y') : '-';
                ?>
                <tr>
                    <th scope="row"><?php echo $idno; ?></th>
                    <td><?php echo $client_name ? $client_name : '-'; ?></td>
                    <td><?php echo $year ? $year : '-'; ?></td>
                    <td><?php echo $engagement_type ? $engagement_type : '-'; ?></td>
                    <!-- <td><?php //echo $status ? $status : '-'; ?></td> -->
                    <td style="width: 100px; text-align: center;">
                        <a href="<?php echo BASE_URL; ?>/engagements/details/?id=<?php echo $id; ?>" class="view">
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
            $sql = "SELECT COUNT(*) as total FROM engagement WHERE status = 'Open'";
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
