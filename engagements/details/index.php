<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php"; // Ensure this is correct
require_once "../../path.php";
require_once "../../app/functions/logout.php";
require_once "../../app/functions/session_helpers.php";
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Trigger the logout function
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    logoutUser($conn);
}

redirectIfNotLoggedIn();

// Ensure the script runs only when needed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followup_owner'])) {
    // Connect to your database (ensure $conn is initialized)
    // require_once 'db_connection.php';

    $qaId = intval($_POST['qa_id']);
    $comment = trim($_POST['followup_comment']);
    $owner = trim($_POST['followup_owner']);
    $engagement_id = intval($_POST['engagement_id']);

    // Input validation
    if (!$qaId || !$engagement_id || empty($comment) || empty($owner)) {
        die("Invalid input. Please provide all required data.");
    }

    // Generate a unique random idno
    $idno = null;
    do {
        $idno = rand(100000, 999999);
        $checkQuery = "SELECT idno FROM followup_qa_comments WHERE idno = ?";
        $stmt = $conn->prepare($checkQuery);
        if (!$stmt) {
            die("SQL prepare failed (check idno): (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("i", $idno);
        $stmt->execute();
        $stmt->store_result();
        $isDuplicate = $stmt->num_rows > 0;
        $stmt->close();
    } while ($isDuplicate);

    // Prepare SQL to insert the follow-up comment
    $insertQuery = "INSERT INTO followup_qa_comments (idno, qa_id, engagement_id, followup_comment, followup_owner) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if (!$stmt) {
        die("SQL prepare failed (insert comment): (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("iiiss", $idno, $qaId, $engagement_id, $comment, $owner);

    // Execute the insert query
    if (!$stmt->execute()) {
        die("Execution failed (insert comment): (" . $stmt->errno . ") " . $stmt->error);
    }
    $stmt->close();

    // Fetch the newly inserted follow-up comment
    $fetchQuery = "SELECT * FROM followup_qa_comments WHERE idno = ?";
    $stmt = $conn->prepare($fetchQuery);
    if (!$stmt) {
        die("SQL prepare failed (fetch comment): (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        die("Failed to fetch the newly inserted comment.");
    }

    // Escape the comment for display
    $commentText = htmlspecialchars($row['followup_comment']);
    $createdAt = date("F j, Y, g:i a", strtotime($row['followup_created']));
    $followupOwner = htmlspecialchars($row['followup_owner']);

    // Return the new comment HTML
    echo "<div class='comment'>
        <div class='comment-header'>
            <span class='comment-time'>$createdAt</span>
            <span class='comment-author'>$followupOwner</span>
        </div>
        <div class='comment-body mt-2'>
            <p>$commentText</p>
        </div>
    </div>";

    exit;
}
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

    <style>
        .qa-comment-row:hover {
            background-color: #f8f9fa !important; 
        }
    </style>
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
                    $off_status                 = $off_row['status']; 


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
                    

                    $formatted_start = date("m/d/Y", strtotime($off_report_start));
                    $formatted_end = date("m/d/Y", strtotime($off_report_end));
                    $formatted_as_of = date("m/d/Y", strtotime($off_report_as_of));
                }
            // }}
            ?>
        <!-- end php code for getting asset details -->

    <!-- main-container -->
        <div class="container" style="background-color: #f2f2f2 !important;">
                <a class="text-decoration-none" href="<?php BASE_URL; ?>/client_list"><i class="bi bi-arrow-left"></i>&nbsp; Back to Client List</a>
            
            <br>
            <div class="mt-5"></div>
            <div class="detail-section d-flex justify-content-between">
                <div class="engagement-client-details">
                    <?php if (strpos($off_engagement_type, 'SOC 2') !== false) { ?>
                        <img src="<?php ROOT_PATH; ?>/assets/images/soc-2-icon.png" width="35" alt=""> &nbsp; <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                    <?php } else if (strpos($off_engagement_type, 'SOC 1') !== false) { ?>
                        <img src="<?php ROOT_PATH; ?>/assets/images/soc-1-icon.png" width="35" alt=""> &nbsp; <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                    <?php } else if (strpos($off_engagement_type, 'HIPAA') !== false) { ?>
                        <img src="<?php ROOT_PATH; ?>/assets/images/hipaa-icon.png" width="35" alt=""> &nbsp; <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                    <?php } else if (strpos($off_engagement_type, 'PCI') !== false) { ?>
                        <img src="<?php ROOT_PATH; ?>/assets/images/pci-icon.png" width="35" alt=""> &nbsp; <?php echo $off_client_name; ?> - <?php echo $off_year; ?> <?php echo $off_engagement_type; ?>
                    <?php } ?>
                </div>
                <div class="audit-period">
                    <?php if(!isset($off_report_as_of)) { ?>
                        <strong>Audit Period: </strong><br><?php echo $formatted_start; ?> - <?php echo $formatted_end; ?>
                    <?php } else { ?>
                        <strong>Audit Period: </strong><br>As of <?php echo $formatted_as_of; ?>
                    <?php } ?>
                        
                </div>
                <?php if($off_status !== "Completed") { ?>
                <div class="complete-button">
                    <form method="POST">
                        <input type="hidden" name="engagement_id" value="<?php echo htmlspecialchars($off_id); ?>">
                        <input type="hidden" name="status" value="Completed">
                        <button type="submit" name="complete_engagement" class="btn btn-outline-primary">
                            <i class="bi bi-check2-circle"></i> Complete Engagement
                        </button>
                    </form>

                    <!-- Complete Engagement PHP -->
                        <?php

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_engagement'])) {
                            $engagement_id = intval($_POST['engagement_id']);
                            $status = $_POST['status'];
                        
                            // Debugging: Check variables
                            echo "Engagement ID: $engagement_id<br>";
                            echo "Status: $status<br>";
                        
                            // Debugging: Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                        
                            // Prepare the SQL query
                            $sql = "UPDATE engagement SET status = ? WHERE engagement_id = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("si", $status, $engagement_id);
                            
                                // Execute the statement
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Engagement status updated to Completed successfully.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Error executing statement: " . $stmt->error . "</div>";
                                }
                            
                                $stmt->close();
                            } else {
                                echo "<div class='alert alert-danger'>Error preparing the SQL statement: " . $conn->error . "</div>";
                            }
                        }
                        ?>
                    <!-- end Complete Engagement PHP -->
                    
                </div>
                <?php } else { ?>
                    <div class="alert alert-success" role="alert">
                        Completed Engagement
                    </div>
                <?php } ?>
            </div>

            <div class="mt-5"></div>

            <div class="card-container">
                <div class="card details_card" style="width: 20rem;">
                  <div class="card-body">
                    <h5 class="card-title">Engagements Resources</h5>
                    <p class="card-text">
                        <h6>
                            QA Comment Report
                        </h6>
                        <div class="btn-group mt-2">
                          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#download_modal"><i class="bi bi-download"></i> Comment Report</button>
                        </div>

                        <!-- MODAL -->

                            <div class="modal fade" id="download_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Download QA Comment Report</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">

                                    <form action="<?php BASE_URL; ?>/app/comment_report.php" method="POST" class="row g-3 p-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="New" id="newComments" name="options[]">
                                            <label class="form-check-label" for="newComments">
                                                New Comments
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Follow-Up" id="followUpComments" name="options[]">
                                            <label class="form-check-label" for="followUpComments">
                                                Follow-Up Comments
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Completed" id="completedComments" name="options[]">
                                            <label class="form-check-label" for="completedComments">
                                                Completed Comments
                                            </label>
                                        </div>
                                        <input type="hidden" name="e_id" value="<?php echo $off_id; ?>"> <!-- Replace with dynamic ID -->
                                        <div class="col-12 pt-3">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-download"></i>&nbsp;&nbsp;Comment Report</button>
                                        </div>
                                    </form>

                                    

                                  </div>
                                </div>
                              </div>
                            </div>


                        <!-- end MODAL -->
                        
                    </p>
                  </div>
                </div>
                <div class="card details_card" style="width: 20rem;">
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
                <div class="card details_card" style="width: 38rem;">
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
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='New' && engagement_id = '$id'";
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
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='Follow-Up' && engagement_id = '$id'";
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
                                    $sql="SELECT count('1') FROM qa_comments WHERE status='Completed' && engagement_id = '$id'";
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


            <table class="table table-hover">
            <thead class="table-secondary">
                <tr>
                    <th></th>
                    <th scope="col">ID</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Control</th>

                    <th scope="col">Comment By</th>
                    <th scope="col">Status</th>
                    <th style="width: 100px; text-align: center;"></th>
                </tr>
            </thead>

            <tbody>
                <?php
                    // Pagination variables
                    $limit = 10; 
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;
                    
                    $sql = "SELECT * FROM qa_comments WHERE engagement_id = '$off_id' && status!='Completed' ORDER BY qa_updated DESC LIMIT $limit OFFSET $offset";
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

                  
                ?>
                
                
                <tr class="qa-comment-row" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $id; ?>">
                    <td></td>
                    <th scope="row"><?php echo $idno; ?></th>
                    <td><?php echo $control_ref ? $control_ref : '-'; ?></td>
                    <td style="width: 400px;"><?php echo $control ? $control : '-'; ?></td>
                    <td><?php echo $comment_by ? $comment_by : '-'; ?></td>
                    <td>
                        <?php if($status == 'New') { ?>
                            <span class="badge" style="background-color: #ecf4f9; color: #2d60a3;"><?php echo $status ? $status : '-'; ?></span>
                        <?php } else if($status == 'Follow-Up') { ?>
                            <span class="badge" style="background-color: #fdf1e0; color: #785524;"><?php echo $status ? $status : '-'; ?></span>
                        <?php } else if($status == 'Completed') { ?>
                            <span class="badge" style="background-color: #e9f8e3; color: #497a37;"><?php echo $status ? $status : '-'; ?></span>
                        <?php } ?>
                        
                    </td>
                    <td>
                        <i class="bi bi-chevron-right"></i>
                    </td>
                </tr>



                <!-- Bootstrap Modal -->
                    <div class="modal fade" id="exampleModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-lg">

                        <?php
                        $modalsql = "SELECT * FROM qa_comments WHERE qa_id = '$id'";
                        $modalresult = mysqli_query($conn, $modalsql);
                        if($modalresult) {
                            $mnum_rows = mysqli_num_rows($modalresult);
                            if($mnum_rows > 0) {
                                while ($mrow = mysqli_fetch_assoc($modalresult)) {
                                    $mid                     = $mrow['qa_id'];
                                    $midno                   = $mrow['idno'];
                                    $mengagement_id          = $mrow['engagement_id'];
                                    $mcontrol_ref            = $mrow['control_ref'];
                                    $mcomment_by             = $mrow['comment_by'];
                                    $mcontrol                = $mrow['control'];
                                    $mstatus                 = $mrow['status'];
                                    $mqa_comment             = $mrow['qa_comment'];
                                
                                ?>

                                
                            <div class="modal-content" style="background-color: #f2f2f2;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <?php if (strpos($off_engagement_type, 'SOC 2') !== false) { ?>
                                            <?php echo $control_ref; ?> &nbsp; <p class="badge soc-2-badge">SOC 2</p>
                                        <?php } else if (strpos($off_engagement_type, 'SOC 1') !== false) { ?>
                                            <?php echo $control_ref; ?> &nbsp; <p class="badge soc-1-badge">SOC 1</p>
                                        <?php } else if (strpos($off_engagement_type, 'HIPAA') !== false) { ?>
                                            <?php echo $control_ref; ?> &nbsp; <p class="badge hipaa-badge">HIPAA</p>
                                        <?php } ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="qa-comment-details">
                                        <h4 class="details-header">Details</h4>
                                        <div class="details-content">
                                            <div class="detail-item">
                                                <span class="detail-label">Reference:</span>
                                                <span class="detail-value"><?php echo $mcontrol_ref ?: '-'; ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Owner:</span>
                                                <span class="detail-value"><?php echo $mcomment_by ?: '-'; ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Control:</span>
                                                <span class="detail-value"><?php echo $mcontrol ?: '-'; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="additional-comments-section">
                                        <h4 class="details-header">QA Comment Details</h4>
                                        <div class="original-comment-details">
                                            <div class="detail-item">
                                                <span class="detail-label">Original Comment:</span>
                                                <span class="detail-value"><?php echo $mqa_comment ?: '-'; ?></span>
                                            </div>
                                        </div>

                                        <div class="mt-4"></div>
                                        <hr>
                                        <div class="mt-4"></div>

                                        <h6 class="details-header" style="font-size: 15px;">Follow-Up Comments</h6>

                                        <!-- Comments Container for each qa_id -->
                                            <div id="followup-comments-container-<?php echo $id; ?>">
                                                <!-- Existing comments for qa_id -->
                                                <?php
                                                // Fetch and display current follow-up comments
                                                $followupSql = "SELECT * FROM followup_qa_comments WHERE qa_id = '$id' ORDER BY followup_created DESC";
                                                $followupResult = mysqli_query($conn, $followupSql);
                                                                                    
                                                if ($followupResult && mysqli_num_rows($followupResult) > 0) {
                                                    while ($followupRow = mysqli_fetch_assoc($followupResult)) {
                                                        $comment = htmlspecialchars($followupRow['followup_comment']);
                                                        $createdAt = date("F j, Y, g:i a", strtotime($followupRow['followup_created']));
                                                        $followupOwner = htmlspecialchars($followupRow['followup_owner']); // Fetch the owner
                                                    
                                                        echo "
                                                            <div class='comment'>
                                                                <div class='comment-header'>
                                                                    <span class='comment-author'>$followupOwner</span> <!-- Author on the left -->
                                                                    <span class='comment-time'>$createdAt</span> <!-- Time on the right -->
                                                                </div>
                                                                <div class='comment-body mt-2'>
                                                                    <p>$comment</p>
                                                                </div>
                                                            </div>";
                                                    }
                                                } 
                                                ?>
                                            </div>


                                        <!-- Follow-Up Comment Form -->
                                        <div class="mt-4"></div>
                                        <hr>
                                        <div class="mt-4"></div>
                                        <h6 class="details-header" style="font-size: 15px;">Add New Comments</h6>
                                            <form id="followup-comment-form-<?php echo $id; ?>" class="followup-comment-form">
                                                <div class="form-group">
                                                    <textarea name="followup_comment" id="followup_comment-<?php echo $id; ?>" rows="4" class="form-control" placeholder="Enter your follow-up comment..." required></textarea>
                                                </div>
                                                <div class="mt-3"></div>
                                                <div class="form-group">
                                                    <input name="followup_owner" id="followup_owner-<?php echo $id; ?>" class="form-control" placeholder="Follow-Up Owner..." required>
                                                </div>
                                                <input type="hidden" name="qa_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="engagement_id" value="<?php echo $mengagement_id; ?>">
                                                <button type="submit" name="followupcomment" class="btn btn-primary mt-3">Submit Follow-Up Comment</button>
                                            </form>

                                            

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        <?php }}} ?>
                    </div>
                <!-- end Bootstrap modal -->

            
               
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
// Prepare the query
$sql = "
    SELECT 
        COUNT(*) AS total_comments,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_comments
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

$total_comments = 0;
$completed_comments = 0;
$percentage_completed = 0;

if ($row = $result->fetch_assoc()) {
    $total_comments = $row['total_comments'];
    $completed_comments = $row['completed_comments'];

    // Calculate percentage only if there are comments
    if ($total_comments > 0) {
        $percentage_completed = round(($completed_comments / $total_comments) * 100, 2);
    }
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

// Update progress circle with PHP-calculated value
updateProgressCircle(<?php echo $percentage_completed; ?>);
</script>


<script>

document.querySelectorAll('.followup-comment-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        const formData = new FormData(this);
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.ok ? response.text() : Promise.reject(response.statusText))
        .then(data => {
            const qaId = formData.get('qa_id');
            const container = document.getElementById('followup-comments-container-' + qaId);
            container.insertAdjacentHTML('afterbegin', data);
            this.reset(); // Clear form fields
        })
        .catch(error => console.error('Error:', error));
    });
});


</script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
