<?php

$files = glob(BASE_URL . "/app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}



// Get the current script directory name (e.g., "dashboard" or "user_profile")
$currentDirectory = basename(dirname($_SERVER['SCRIPT_FILENAME']));
// Replace underscores with spaces
$pageName = str_replace('_', ' ', $currentDirectory);
// Capitalize the first letter of each word for display purposes
$pageName = ucwords($pageName);
?>
<div class="page_header">
    <div class="left">
        <h5><?php echo $pageName; ?></h5>
    </div>
    <div class="right">
        <p style="padding-right: 15px;">Welcome, <?php echo $_SESSION['full_name']; ?></p>
        <div class="dropdown header-icon">
            <a class="dropdown-toggle custom-dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-gear-fill"></i>
            </a>

            <ul class="dropdown-menu">
                <li><h6 class="dropdown-header">Manage</h6></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#manage_clients">Manage Clients</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#manage_engagements">Manage Engagements</a></li>
                <hr>
                <li><h6 class="dropdown-header">Backups</h6></li>
                <li><a class="dropdown-item" href="<?php $BASE_URL; ?>/backups">Backup Configurations</a></li>
            </ul>
        </div>
        <div class="dropdown header-icon">
            <a class="dropdown-toggle custom-dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus-circle-fill"></i>
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_client">Add Client</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_engagement">Add Engagement</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_qa_comment">Add QA Comment</a></li>
            </ul>
        </div>

        <!-- <a class="header-icon" data-bs-toggle="modal" data-bs-target="#add_content" style="cursor: pointer;"><i class="bi bi-plus-circle-fill"></i></a> -->
        <a class="header-icon" href="?logout=1"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>


<!-- add-client -->
    <div class="modal fade" id="add_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label for="c_client_name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="c_client_name" name="c_client_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_primary_contact" class="form-label">Primary Contact</label>
                            <input type="text" class="form-control" id="c_primary_contact" name="c_primary_contact" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="c_contact_email" name="c_contact_email" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="has_logo" name="has_logo">
                                <label class="form-check-label" for="has_logo">
                                    Client has a logo
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="add_client" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<!-- end add-client -->

<!-- add-engagement --> 
    <div class="modal fade" id="add_engagement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Engagement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <form class="row g-3" method="POST">
                <div class="col-md-6">
                    <label for="e_client_name" class="form-label">Client Name</label>
                    <select id="e_client_name" name="e_client_name" class="form-select">
                        <option value="">Choose...</option>
                        <?php
                        // Secure the database query
                        $stmt = $conn->prepare("SELECT client_name FROM clients");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $e_client_name = htmlspecialchars($row['client_name']);
                                echo "<option value=\"$e_client_name\">$e_client_name</option>";
                            }
                        } else {
                            echo "<option value=\"\">No clients found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="e_engagement_type" class="form-label">Engagement Type</label>
                    <select id="e_engagement_type" name="e_engagement_type" class="form-select">
                        <option value="">Choose...</option>
                        <option value="SOC 1 Type 1">SOC 1 Type 1</option>
                        <option value="SOC 1 Type 2">SOC 1 Type 2</option>
                        <option value="SOC 2 Type 1">SOC 2 Type 1</option>
                        <option value="SOC 2 Type 2">SOC 2 Type 2</option>
                        <option value="HIPAA">HIPAA</option>
                        <option value="PCI">PCI</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" class="form-control" id="year" name="year" min="1900" max="2100">
                </div>
                <div class="col-md-6">
                    <label for="report_start" class="form-label">Report Start Date</label>
                    <input type="date" class="form-control" id="report_start" name="report_start">
                </div>
                <div class="col-md-6">
                    <label for="report_end" class="form-label">Report End Date</label>
                    <input type="date" class="form-control" id="report_end" name="report_end">
                </div>
                <div class="col-md-6">
                    <label for="report_as_of" class="form-label">Report As Of Date</label>
                    <input type="date" class="form-control" id="report_as_of" name="report_as_of">
                </div>
                <div class="col-md-6">
                    <label for="manager" class="form-label">Manager</label>
                    <input type="text" class="form-control" id="manager" name="manager">
                </div>
                <div class="col-md-6">
                    <label for="senior" class="form-label">Senior</label>
                    <input type="text" class="form-control" id="senior" name="senior">
                </div>
                <div class="col-md-6">
                    <label for="staff_1" class="form-label">Staff 1</label>
                    <input type="text" class="form-control" id="staff_1" name="staff_1">
                </div>
                <div class="col-md-6">
                    <label for="staff_2" class="form-label">Staff 2</label>
                    <input type="text" class="form-control" id="staff_2" name="staff_2">
                </div>
                <div class="col-md-6">
                    <label for="leadsheet_due" class="form-label">Leadsheet Due</label>
                    <input type="date" class="form-control" id="leadsheet_due" name="leadsheet_due">
                </div>
                <div class="col-md-6">
                    <label for="field_work_week" class="form-label">Fieldwork Week</label>
                    <input type="date" class="form-control" id="field_work_week" name="field_work_week">
                </div>
                <div class="col-md-6">
                    <label for="senior_dol" class="form-label">Senior DOL</label>
                    <input type="text" class="form-control" id="senior_dol" name="senior_dol">
                </div>
                <div class="col-md-6">
                    <label for="staff_1_dol" class="form-label">Staff 1 DOL</label>
                    <input type="text" class="form-control" id="staff_1_dol" name="staff_1_dol">
                </div>
                <div class="col-md-6">
                    <label for="staff_2_dol" class="form-label">Staff 2 DOL</label>
                    <input type="text" class="form-control" id="staff_2_dol" name="staff_2_dol">
                </div>
                <div class="col-12">
                    <button type="submit" name="add_engagement" class="btn btn-primary">Add Engagement</button>
                </div>
            </form>



            </div>
        </div>
      </div>
    </div>
<!-- end add-engagement -->

<!-- Add QA Comment Modal -->
    <div class="modal fade" id="add_qa_comment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add QA Comment</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="POST" action="">
              <div class="col-md-6">
                <label for="qa_engagement_id" class="form-label">Engagement</label>
                <select id="qa_engagement_id" name="qa_engagement_id" class="form-select" required>
                  <option value="">Choose...</option>
                  <?php
                  $qa_sql = "SELECT * FROM engagement";
                  $qa_result = mysqli_query($conn, $qa_sql);
                  if ($qa_result && mysqli_num_rows($qa_result) > 0) {
                      while ($qa_row = mysqli_fetch_assoc($qa_result)) { 
                          $qa_engagement_id = htmlspecialchars($qa_row['engagement_id']);
                          $qa_client_name = htmlspecialchars($qa_row['client_name']);
                          $qa_year = htmlspecialchars($qa_row['year']);
                          $qa_engagement_type = htmlspecialchars($qa_row['engagement_type']);
                  ?>
                  <option value="<?php echo $qa_engagement_id; ?>" data-client-name="<?php echo $qa_client_name; ?>">
                    <?php echo "$qa_client_name - $qa_year $qa_engagement_type"; ?>
                  </option>
                  <?php } } ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="qa_client_name" class="form-label">Client Name</label>
                <input type="text" id="qa_client_name" name="qa_client_name" class="form-control" readonly>
              </div>
              <div class="col-md-6">
                <label for="control_ref" class="form-label">Control Reference</label>
                <input type="text" class="form-control" id="control_ref" name="control_ref" required>
              </div>
              <div class="col-md-6">
                <label for="cell_reference" class="form-label">Cell Reference</label>
                <input type="text" class="form-control" id="cell_reference" name="cell_reference" required>
              </div>
              <div class="col-md-6">
                <label for="comment_by" class="form-label">Comment By</label>
                <input type="text" class="form-control" id="comment_by" name="comment_by" required>
              </div>
              <div class="mb-3">
                <label for="control" class="form-label">Control</label>
                <textarea class="form-control" id="control" name="control" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="qa_comment" class="form-label">QA Comment</label>
                <textarea class="form-control" id="qa_comment" name="qa_comment" rows="3" required></textarea>
              </div>
              <div class="col-12">
                <button type="submit" name="add_qa_comment" class="btn btn-primary">Add QA Comment</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<!-- end add qa comment -->


<!-- manage-client -->
    <div class="modal fade" id="manage_clients" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Clients</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <?php
                        // Query to get clients
                        $dc_sql = "SELECT * FROM clients ORDER BY client_created ASC";
                        $dc_result = mysqli_query($conn, $dc_sql);
                        if ($dc_result) {
                            $dc_num_rows = mysqli_num_rows($dc_result);
                            if ($dc_num_rows > 0) {
                                while ($dc_row = mysqli_fetch_assoc($dc_result)) {
                                    $dc_idno = $dc_row['idno'];
                                    $dc_id = $dc_row['client_id'];
                                    $dc_client_name = $dc_row['client_name'];
                        ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div><strong><?php echo $dc_idno; ?></strong></div>
                                    <div><?php echo $dc_client_name; ?></div>
                                </div>
                                <div>
                                    <!-- Pass the client_id as a data attribute for the edit modal -->
                                    <a href="#" data-bs-target="#edit_client" data-bs-toggle="modal" data-dc-id="<?php echo $dc_id; ?>">
                                        <i class="bi bi-pencil-square" style="color: #005382; cursor: pointer;"></i>
                                    </a> &nbsp;&nbsp;
                                    <a href="?action=delete&dc_id=<?php echo $dc_id; ?>" onclick="return confirm('Are you sure you want to delete this client?');">
                                        <i class="bi bi-trash" style="color: #941515; cursor: pointer;"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<!-- end manage-client -->

<!-- edit-client -->
    <div class="modal fade" id="edit_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Client</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClientForm" method="POST" class="row g-3">
                        <input type="hidden" name="edit_client_id" id="edit_client_id">
                        <div class="col-md-6">
                            <label for="c_client_name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="edit_client_name" name="edit_client_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_primary_contact" class="form-label">Primary Contact</label>
                            <input type="text" class="form-control" id="edit_primary_contact" name="edit_primary_contact" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="edit_contact_email" name="edit_contact_email" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="edit_has_logo" name="edit_has_logo">
                                <label class="form-check-label" for="has_logo">
                                    Client has a logo
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="edit_client" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- end edit-client -->

<!-- manage-engagements -->
    <div class="modal fade" id="manage_engagements" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Engagements</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <?php
                        // Query to get clients
                        $me_sql = "SELECT * FROM engagement ORDER BY engagement_created ASC";
                        $me_result = mysqli_query($conn, $me_sql);
                        if ($me_result) {
                            $me_num_rows = mysqli_num_rows($me_result);
                            if ($me_num_rows > 0) {
                                while ($me_row = mysqli_fetch_assoc($me_result)) {
                                    $me_id = $me_row['engagement_id'];
                                    $me_idno = $me_row['idno'];
                                    $me_client_name = $me_row['client_name'];
                                    $me_engagement_type = $me_row['engagement_type'];
                                    $me_year = $me_row['year'];
                        ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div><strong><?php echo $me_idno; ?></strong></div>
                                    <div><?php echo $me_client_name; ?> - <?php echo $me_year; ?> <?php echo $me_engagement_type; ?></div>
                                </div>
                                <div>
                                    <!-- Pass the client_id as a data attribute for the edit modal -->
                                    <a href="#" data-bs-target="#edit_engagement" data-bs-toggle="modal" data-me-id="<?php echo $me_id; ?>">
                                        <i class="bi bi-pencil-square" style="color: #005382; cursor: pointer;"></i>
                                    </a> &nbsp;&nbsp;
                                    <a href="?action=delete&me_id=<?php echo $me_id; ?>" onclick="return confirm('Are you sure you want to delete this engagement?');">
                                        <i class="bi bi-trash" style="color: #941515; cursor: pointer;"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<!-- end manage-engagements -->

<!-- edit-engagement -->
    <div class="modal fade" id="edit_engagement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Engagement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   

                <form class="row g-3" method="POST">
                    <input type="hidden" name="me_edit_engagement_id" id="me_edit_engagement_id">
                        <div class="col-md-6">
                            <label for="me_edit_client_name" class="form-label">Client Name</label>
                            <select id="me_edit_client_name" name="e_client_name" class="form-select">
                                <option value="">Choose...</option>
                                <?php
                                $stmt = $conn->prepare("SELECT client_name FROM clients");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $e_client_name = htmlspecialchars($row['client_name']);
                                        echo "<option value=\"$e_client_name\">$e_client_name</option>";
                                    }
                                } else {
                                    echo "<option value=\"\">No clients found</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_engagement_type" class="form-label">Engagement Type</label>
                            <select id="me_edit_engagement_type" name="e_engagement_type" class="form-select">
                                <option value="">Choose...</option>
                                <option value="SOC 1 Type 1">SOC 1 Type 1</option>
                                <option value="SOC 1 Type 2">SOC 1 Type 2</option>
                                <option value="SOC 2 Type 1">SOC 2 Type 1</option>
                                <option value="SOC 2 Type 2">SOC 2 Type 2</option>
                                <option value="HIPAA">HIPAA</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_year" class="form-label">Year</label>
                            <input type="number" class="form-control" id="me_edit_year" name="year" min="1900" max="2100">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_report_start" class="form-label">Report Start Date</label>
                            <input type="date" class="form-control" id="me_edit_report_start" name="report_start">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_report_end" class="form-label">Report End Date</label>
                            <input type="date" class="form-control" id="me_edit_report_end" name="report_end">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_report_as_of" class="form-label">Report As Of Date</label>
                            <input type="date" class="form-control" id="me_edit_report_as_of" name="report_as_of">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_manager" class="form-label">Manager</label>
                            <input type="text" class="form-control" id="me_edit_manager" name="manager">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_senior" class="form-label">Senior</label>
                            <input type="text" class="form-control" id="me_edit_senior" name="senior">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_staff" class="form-label">Staff</label>
                            <input type="text" class="form-control" id="me_edit_staff" name="staff">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_leadsheets_due" class="form-label">Leadsheet Due</label>
                            <input type="date" class="form-control" id="me_edit_leadsheets_due" name="leadsheet_due">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_field_work_week" class="form-label">Fieldwork Week</label>
                            <input type="date" class="form-control" id="me_edit_field_work_week" name="field_work_week">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_senior_dol" class="form-label">Senior DOL</label>
                            <input type="text" class="form-control" id="me_edit_senior_dol" name="senior_dol">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_staff_1_dol" class="form-label">Staff 1 DOL</label>
                            <input type="text" class="form-control" id="me_edit_staff_1_dol" name="staff_1_dol">
                        </div>
                        <div class="col-md-6">
                            <label for="me_edit_staff_2_dol" class="form-label">Staff 2 DOL</label>
                            <input type="text" class="form-control" id="me_edit_staff_2_dol" name="staff_2_dol">
                        </div>
                        <div class="col-12">
                            <button type="submit" name="edit_engagement" class="btn btn-primary">Update Engagement</button>
                        </div>
                    </form>

                

                </div>
            </div>
        </div>
    </div>
<!-- end edit-engagement -->

<script>
    document.getElementById('edit_engagement').addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        var button = event.relatedTarget;

        // Get the engagement ID from the data-me-id attribute
        var engagementId = button.getAttribute('data-me-id');
        console.log('Engagement ID passed to modal:', engagementId);

        // Make an AJAX request to fetch engagement data
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "<?php echo BASE_URL; ?>/app/fetch_engagement_data.php?engagement_id=" + encodeURIComponent(engagementId), true);

        console.log('Sending AJAX request to fetch engagement data for ID:', engagementId);

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('AJAX Response:', xhr.responseText);

                try {
                    // Parse the JSON response
                    var engagementData = JSON.parse(xhr.responseText);
                    console.log('Parsed engagement data:', engagementData);

                    // Populate the modal fields with the fetched data
                    if (engagementData) {
                        document.getElementById('me_edit_engagement_id').value = engagementData.engagement_id || '';
                        document.getElementById('me_edit_client_name').value = engagementData.client_name || '';
                        document.getElementById('me_edit_engagement_type').value = engagementData.engagement_type || '';
                        document.getElementById('me_edit_year').value = engagementData.year || '';
                        document.getElementById('me_edit_report_start').value = engagementData.report_start || '';
                        document.getElementById('me_edit_report_end').value = engagementData.report_end || '';
                        document.getElementById('me_edit_report_as_of').value = engagementData.report_as_of || '';
                        document.getElementById('me_edit_manager').value = engagementData.manager || '';
                        document.getElementById('me_edit_senior').value = engagementData.senior || '';
                        document.getElementById('me_edit_staff').value = engagementData.staff || '';
                        document.getElementById('me_edit_leadsheets_due').value = engagementData.leadsheets_due || '';
                        document.getElementById('me_edit_field_work_week').value = engagementData.field_work_week || '';
                        document.getElementById('me_edit_senior_dol').value = engagementData.senior_dol || '';
                        document.getElementById('me_edit_staff_1_dol').value = engagementData.staff_1_dol || '';
                        document.getElementById('me_edit_staff_2_dol').value = engagementData.staff_2_dol || '';
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            } else {
                console.error('Failed to fetch engagement data, status:', xhr.status);
            }
        };

        xhr.onerror = function () {
            console.error('AJAX request failed');
        };

        xhr.send();
    });
</script>

<script>
        document.getElementById('edit_client').addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        var button = event.relatedTarget;
        
        // Get the client ID from the data-dc-id attribute
        var clientId = button.getAttribute('data-dc-id');
        
        // Log the client ID for debugging
        console.log('Client ID passed to modal:', clientId);

        // Now, make an AJAX request to fetch client data
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "<?php echo BASE_URL;?>/app/fetch_client_data.php?client_id=" + clientId, true);
        
        // Log before sending the request
        console.log('Sending AJAX request to fetch client data for ID:', clientId);

        xhr.onload = function () {
            if (xhr.status == 200) {
                // Log the server response for debugging
                console.log('AJAX Response:', xhr.responseText);

                try {
                    // Parse the JSON response
                    var clientData = JSON.parse(xhr.responseText);

                    // Log the parsed data for debugging
                    console.log('Parsed client data:', clientData);

                    // Populate the modal fields with the fetched data
                    if(clientData) {
                        document.getElementById('edit_client_id').value = clientData.client_id || '';
                        document.getElementById('edit_client_name').value = clientData.client_name || '';
                        document.getElementById('edit_primary_contact').value = clientData.primary_contact || '';
                        document.getElementById('edit_contact_email').value = clientData.contact_email || '';
                        document.getElementById('edit_has_logo').checked = clientData.has_logo && clientData.has_logo !== '';
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            } else {
                console.error('Failed to fetch client data, status:', xhr.status);
            }
        };

        xhr.onerror = function () {
            console.error('AJAX request failed');
        };

        xhr.send();
    });



</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const engagementDropdown = document.getElementById('qa_engagement_id');
    const clientNameInput = document.getElementById('qa_client_name');
    if (engagementDropdown && clientNameInput) {
      engagementDropdown.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        clientNameInput.value = selectedOption.getAttribute('data-client-name') || '';
      });
    }
  });
</script>






