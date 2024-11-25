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
        <div class="dropdown">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown link
            </a>

            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </div>
        <a class="header-icon" data-bs-toggle="modal" data-bs-target="#add_content" style="cursor: pointer;"><i class="bi bi-plus-circle-fill"></i></a>
        <a class="header-icon" href="?logout=1"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>






<!-- modal -->

<div class="modal fade" id="add_content" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">ARC Actions</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <a class="btn btn-secondary" href="<?php BASE_URL; ?>/actions/add_qa_comment/" role="button">Add QA Comment</a>
            <br>
            <div class="mt-3"></div>
            <a class="btn btn-secondary" href="<?php BASE_URL; ?>/actions/add_client/" role="button">Add Client</a>
            <br>
            <div class="mt-3"></div>
            <a class="btn btn-secondary" href="<?php BASE_URL; ?>/actions/add_engagement/" role="button">Add Engagement</a>

        </div>
    </div>
  </div>
</div>

<!-- end modal -->