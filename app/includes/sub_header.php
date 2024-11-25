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

            <form class="row g-3">
                <div class="col-md-6">
                    <label for="client_name" class="form-label">Client Name</label>
                    <input type="text" class="form-control" id="client_name">
                </div>
                <div class="col-12">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <form class="row g-3">
                <div class="col-md-4">
                    <label for="e_client_name" class="form-label">Client Name</label>
                    <select id="e_client_name" class="form-select">
                        <option>Choose...</option>
                        <?php
                        $sql = "SELECT client_name FROM clients";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { 
                                $client_name = $row['client_name'];
                        ?>
                            <option value="<?php echo $client_name; ?>"><?php echo $client_name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="client_name" class="form-label"></label>
                    <input type="text" class="form-control" id="client_name">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submite</button>
                </div>
            </form>

        </div>
    </div>
  </div>
</div>
<!-- end add-engagement -->