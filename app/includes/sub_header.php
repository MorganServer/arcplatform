<?php
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
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#manage_engagement">Manage Engagement</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#manage_qa_comment">Manage QA Comment</a></li>
                <hr>
                <li><h6 class="dropdown-header">Backup Actions</h6></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#backup_scehdule">Backup Schedule</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#backup_notifications">Backup Notifications</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#manage_backups">Manage Backups</a></li>
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
                    <label for="staff" class="form-label">Staff</label>
                    <input type="text" class="form-control" id="staff" name="staff">
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

<!-- add-qa-comment -->
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
                    <select id="qa_engagement_id" name="qa_engagement_id" class="form-select">
                        <option value="">Choose...</option>
                        <?php
                        $qa_sql = "SELECT * FROM engagement";
                        $qa_result = mysqli_query($conn, $qa_sql);
                        if (mysqli_num_rows($qa_result) > 0) {
                            while ($qa_row = mysqli_fetch_assoc($qa_result)) { 
                                $qa_id = $qa_row['engagement_id'];
                                $qa_client_name = htmlspecialchars($qa_row['client_name']);
                                $qa_year = htmlspecialchars($qa_row['year']);
                                $qa_engagement_type = htmlspecialchars($qa_row['engagement_type']);
                        ?>
                            <option value="<?php echo $qa_id; ?>" 
                                    data-client-name="<?php echo $qa_client_name; ?>">
                                <?php echo $qa_client_name; ?> - <?php echo $qa_year; ?> <?php echo $qa_engagement_type; ?>
                            </option>
                        <?php 
                            }
                        }
                        ?>
                    </select>
                </div>
                    
                <div class="col-md-6 mt-3">
                    <label for="qa_client_name" class="form-label">Client Name</label>
                    <input type="text" id="qa_client_name" name="qa_client_name" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label for="control_ref" class="form-label">Control Reference</label>
                    <input type="text" class="form-control" id="control_ref" name="control_ref">
                </div>
                <div class="col-md-6">
                    <label for="cell_reference" class="form-label">Cell Reference</label>
                    <input type="text" class="form-control" id="cell_reference" name="cell_reference">
                </div>
                <div class="col-md-6">
                    <label for="comment_by" class="form-label">Comment By</label>
                    <input type="text" class="form-control" id="comment_by" name="comment_by">
                </div>
                <div class="mb-3">
                    <label for="control" class="form-label">Control</label>
                    <textarea class="form-control" id="control" name="control" rows="3" ></textarea>
                </div>
                <div class="mb-3">
                    <label for="qa_comment" class="form-label">QA Comment</label>
                    <textarea class="form-control" id="qa_comment" name="qa_comment" rows="3" ></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" name="submit_qa_comment" class="btn btn-primary">Submit</button>
                </div>
            </form>


            </div>
        </div>
      </div>
    </div>
<!-- end add-qa-comment -->

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
                        // Query to get clients, the total number of engagements, and open QA comments for each client
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
                                    <a href="" data-bs-target="#edit_client" data-bs-toggle="modal" data-dc-id="<?php echo $dc_id; ?>">
                                        <i class="bi bi-pencil-square" style="color: #005382; cursor: pointer;"></i>
                                    </a> &nbsp;&nbsp;
                                    <a href="?action=delete&dc_id=<?php echo $dc_id; ?>" onclick="return confirm('Are you sure you want to delete this client?');">
                                        <i class="bi bi-trash" style="color: #941515; cursor: pointer;"></i>
                                    </a>
                                </div>
                            </div>
                        </li>


  
                        <?php }}} ?>
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
                <!-- Hidden Input for Client ID -->
                <input type="hidden" id="edit_dc_id" name="edit_dc_id" value="<?php echo $dc_id; ?>">

                <?php
                if (isset($_GET['dc_id'])) {
                    // Fetch client ID from the URL or hidden field
                    $ec_id = intval($_GET['dc_id']); // Sanitize input
                    
                    // Query to fetch client details
                    $ec_sql = "SELECT * FROM clients WHERE client_id = ?";
                    if ($stmt = $conn->prepare($ec_sql)) {
                        $stmt->bind_param("i", $ec_id); // Bind the client ID
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $ec_idno = $row['idno'];
                            $ec_client_name = $row['client_name'];
                            $ec_primary_contact = $row['primary_contact'];
                            $ec_contact_email = $row['contact_email'];
                            $ec_has_logo = $row['has_logo'];
                        }
                    }
                }
                ?>

                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="c_client_name" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="c_client_name" name="c_client_name" value="<?php echo isset($ec_client_name) ? $ec_client_name : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="c_primary_contact" class="form-label">Primary Contact</label>
                        <input type="text" class="form-control" id="c_primary_contact" name="c_primary_contact" value="<?php echo isset($ec_primary_contact) ? $ec_primary_contact : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="c_contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="c_contact_email" name="c_contact_email" value="<?php echo isset($ec_contact_email) ? $ec_contact_email : ''; ?>" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="has_logo" name="has_logo" <?php echo (isset($ec_has_logo) && $ec_has_logo == 1) ? 'checked' : ''; ?>>
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle dropdown selection and update client name
        const engagementDropdown = document.getElementById('qa_engagement_id');
        const clientNameInput = document.getElementById('qa_client_name');

        if (engagementDropdown && clientNameInput) {
            engagementDropdown.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const clientName = selectedOption.getAttribute('data-client-name');

                // Set the client name input field in real-time
                clientNameInput.value = clientName || '';  // Set client name or empty if not selected
            });
        }
    });
</script>

<script>
    // When the modal is about to be shown
    var editClientModal = document.getElementById('edit_client');
    editClientModal.addEventListener('show.bs.modal', function (event) {
        // Get the clicked button (triggering the modal)
        var button = event.relatedTarget;
        var dc_id = button.getAttribute('data-dc-id'); // Get client ID from the clicked link
        
        // Set the value of the hidden input field
        document.getElementById('edit_dc_id').value = dc_id;
    });
</script>

