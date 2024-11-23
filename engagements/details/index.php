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
    <title>Engagement Details - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- php code for getting asset details -->
    <?php
            $id = $_GET['id'];
            $off_sql = "SELECT * FROM engagement WHERE engagement_id = $id";
            $off_result = mysqli_query($conn, $off_sql);
            if($off_result) {
            $num_rows = mysqli_num_rows($off_result);
            if($num_rows > 0) {
                while ($off_row = mysqli_fetch_assoc($off_result)) {
                    $off_id                     = $off_row['engagement_id']; 
                    $off_client_name            = $off_row['client_name']; 
                    $off_engagement_type        = $off_row['engagement_type']; 
                    $off_year                   = $off_row['year']; 
                    $off_report_start           = $off_row['report_start']; 
                    $off_report_end             = $off_row['report_end']; 
                    $off_report_as_of           = $off_row['report_as_of']; 
                    $off_manager                = $off_row['manager']; 
                    $off_senior                 = $off_row['senior']; 
                    $off_staff                  = $off_row['staff']; 


                    // Split the name into parts and get initials
                    $name_parts = explode(" ", $off_manager);
                    $first_initial = isset($name_parts[0]) ? strtoupper($name_parts[0][0]) : '';
                    $last_initial = isset($name_parts[1]) ? strtoupper($name_parts[1][0]) : '';
                    $manager_initials = $first_initial . $last_initial;

                    $name_parts = explode(" ", $off_senior);
                    $first_initial = isset($name_parts[0]) ? strtoupper($name_parts[0][0]) : '';
                    $last_initial = isset($name_parts[1]) ? strtoupper($name_parts[1][0]) : '';
                    $senior_initials = $first_initial . $last_initial;

                    $name_parts = explode(" ", $off_staff);
                    $first_initial = isset($name_parts[0]) ? strtoupper($name_parts[0][0]) : '';
                    $last_initial = isset($name_parts[1]) ? strtoupper($name_parts[1][0]) : '';
                    $staff_initials = $first_initial . $last_initial;
                    

                    // $today = date('Y-m-d');
                    // $is_today = ($off_audit_schedule == $today) ? true : false;
                }
            // }}
            ?>
        <!-- end php code for getting asset details -->

    <!-- main-container -->
        <div class="container" style="background-color: #f2f2f2 !important;">
            <a class="text-decoration-none" href="<?php BASE_URL; ?>/engagements"><i class="bi bi-arrow-left"></i>&nbsp; Back to Engagements</a>
            <br>
            <div class="mt-5"></div>
            <div class="detail-section d-flex justify-content-between">
                <div class="engagement-client-details">
                    <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                </div>
                <div class="audit-period">
                    <strong>Audit Period: </strong><br><?php echo $off_report_start; ?> - <?php echo $off_report_end; ?>
                </div>
                <div class="complete-button">
                    <button type="button" class="btn btn-outline-primary"><i class="bi bi-check2-circle"></i> Complete Engagement</button>
                </div>
            </div>

            <div class="mt-5"></div>

            <div class="card-container">
                <div class="card" style="width: 20rem;">
                  <div class="card-body">
                    <h5 class="card-title">Engagements Resources</h5>
                    <p class="card-text">
                        
                    </p>
                  </div>
                </div>
                <div class="card" style="width: 20rem;">
                  <div class="card-body">
                    <h5 class="card-title">Auditors</h5>
                    <p class="card-text">
                        <div class="auditor-info">
                            <div class="circle"><?php echo htmlspecialchars($manager_initials); ?></div>
                            <div class="name-bg">
                                <span class="name"><?php echo htmlspecialchars($off_manager); ?></span>
                            </div>
                        </div>
                        <div class="mt-2"></div>
                        <div class="auditor-info">
                            <div class="circle"><?php echo htmlspecialchars($senior_initials); ?></div>
                            <div class="name-bg">
                                <span class="name"><?php echo htmlspecialchars($off_senior); ?></span>
                            </div>
                        </div>
                        <div class="mt-2"></div>
                        <div class="auditor-info">
                            <div class="circle"><?php echo htmlspecialchars($staff_initials); ?></div>
                            <div class="name-bg">
                                <span class="name"><?php echo htmlspecialchars($off_staff); ?></span>
                            </div>
                        </div>
                    </p>
                  </div>
                </div>
                <div class="card" style="width: 38rem;">
                  <div class="card-body">
                    <h5 class="card-title">Engagement Summary <span class="text-secondary" style="font-size: 12px;">(QA Comments)</span></h5>
                    <div class="mt-3"></div>
                    <p class="card-text">
                    <div class="summary-content d-flex justify-content-between align-items-center">
                        <div class="new-comments d-flex flex-column text-center">
                            <i class="bi bi-circle"></i>
                            <div class="pt-1"></div>
                            New
                            <br>
                            <div class="pt-1"></div>
                            <strong>
                                <?php
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='New'";
                                    $result=mysqli_query($conn,$sql);
                                    $rowtotal=mysqli_fetch_array($result); 
                                    if($rowtotal[0] < 10) {
                                        echo "0$rowtotal[0]";
                                    } else {
                                        echo "$rowtotal[0]";
                                    }
                                ?>
                            </strong>
                        </div>
                        <div class="followup-comments d-flex flex-column text-center">
                            <i class="bi bi-clock mx-auto"></i>
                            <div class="pt-1"></div>
                            Follow-Up
                            <br>
                            <div class="pt-1"></div>
                            <strong>
                                <?php
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='Follow-Up'";
                                    $result=mysqli_query($conn,$sql);
                                    $rowtotal=mysqli_fetch_array($result); 
                                    if($rowtotal[0] < 10) {
                                        echo "0$rowtotal[0]";
                                    } else {
                                        echo "$rowtotal[0]";
                                    }
                                ?>
                            </strong>
                        </div>
                        <div class="completed-comments d-flex flex-column text-center">
                            <i class="bi bi-check-lg mx-auto"></i>
                            <div class="pt-1"></div>
                            Completed
                            <br>
                            <div class="pt-1"></div>
                            <strong>
                                <?php
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='Completed'";
                                    $result=mysqli_query($conn,$sql);
                                    $rowtotal=mysqli_fetch_array($result); 
                                    if($rowtotal[0] < 10) {
                                        echo "0$rowtotal[0]";
                                    } else {
                                        echo "$rowtotal[0]";
                                    }
                                ?>
                            </strong>
                        </div>
                        <div class="completed-status d-flex flex-column text-center">
                            <div class="progress-circle">
                                <svg width="120" height="120" viewBox="0 0 120 120">
                                    <!-- Background Circle -->
                                    <circle cx="60" cy="60" r="54" stroke="#e6e6e6" stroke-width="12" fill="none"></circle>
                                    <!-- Progress Circle -->
                                    <circle class="progress-bar" cx="60" cy="60" r="54" stroke="#007bff" stroke-width="12" fill="none" stroke-dasharray="339.292" stroke-dashoffset="339.292"></circle>
                                </svg>
                                <div class="progress-text">
                                    0%
                                    <p class="text-secondary" style="font-size: 14px;">
                                        Completed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    </p>
                  </div>
                </div>
            </div>
            <div class="comment_content_table" style="border-radius: 15px; background-color: white;">

            <form style="padding: 20px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="margin-right: -10px;">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control border-start-0" 
                        id="search_bar" 
                        placeholder="Search QA Comment..."
                    >
                </div>
            </form>


            <table class="table">
            <thead class="table-secondary">
                <tr>
                    <th></th>
                    <th scope="col">ID</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Control</th>

                    <th scope="col">Comment By</th>
                    <th scope="col">Status</th>
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
                    
                    $sql = "SELECT * FROM qa_comments WHERE engagement_id = '$off_id' ORDER BY qa_updated DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $num_rows = mysqli_num_rows($result);
                        if($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id                     = $row['qa_id'];
                                $idno                   = $row['idno'];
                                $engagement_id          = $row['engagement_id'];
                                $control_ref            = $row['control_ref'];
                                $comment_by             = $row['comment_by'];
                                $control                = $row['control'];
                                $status                 = $row['status'];

                  
                             

                                // Format maintenance schedule if not null
                                // $f_maintenance_schedule = !empty($maintenance_schedule) ? date_format(date_create($maintenance_schedule), 'M d, Y') : '-';

                                // Format audit schedule if not null
                                // $f_audit_schedule = !empty($audit_schedule) ? date_format(date_create($audit_schedule), 'M d, Y') : '-';
                ?>
                <tr>
                    <td></td>
                    <th scope="row"><?php echo $idno; ?></th>
                    <td><?php echo $control_ref ? $control_ref : '-'; ?></td>
                    <td style="width: 400px;"><?php echo $control ? $control : '-'; ?></td>
                    <td><?php echo $comment_by ? $comment_by : '-'; ?></td>
                    <td><?php echo $status ? $status : '-'; ?></td>
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
            $sql = "SELECT COUNT(*) as total FROM qa_comments WHERE engagement_id = '$off_id'";
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

            



            <!-- Percentage Calculation -->
                <?php
                $engagement_id = $_GET['engagement_id']; // Get the engagement ID dynamically

                // Prepare the query
                $sql = "
                    SELECT 
                        COUNT(*) AS total_comments,
                        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_comments,
                        ROUND(
                            (SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2
                        ) AS percentage_completed
                    FROM 
                        qa_comments
                    WHERE 
                        engagement_id = ?
                ";

                // Execute the query
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $off_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $total_comments = $row['total_comments'];
                    $completed_comments = $row['completed_comments'];
                    $percentage_completed = $row['percentage_completed'];
                } 
                ?>
            <!-- end Percentage Calculation -->


        <?php }
        } ?>

        </div>
    <!-- END main-container -->



    


    <script>
function updateProgressCircle(percent) {
    const radius = 54;
    const circumference = 2 * Math.PI * radius;
    const offset = circumference - (percent / 100) * circumference;

    const circle = document.querySelector('.progress-bar');
    circle.style.strokeDashoffset = offset;

    // Update the progress percentage text
    document.querySelector('.progress-text').innerHTML = `${percent}%<p class="text-secondary" style="font-size: 14px;">Completed</p>`;
}

// Example usage:
updateProgressCircle(<?php echo $percentage_completed; ?>); // Update to 75% progress
</script>

</body>
</html>
