<?php
// Get the current script directory name (e.g., "dashboard")
$currentDirectory = basename(dirname($_SERVER['SCRIPT_FILENAME']));

// Capitalize the first letter for display purposes
$pageName = ucfirst($currentDirectory);
?>

<div class="page_header">
    <div class="left">
        <h5><?php echo $pageName; ?></h5>
    </div>
    <div class="right">
        <p style="padding-right: 15px;">Welcome, <?php echo $_SESSION['full_name']; ?></p>
        <a class="header-icon" href=""><i class="bi bi-info-circle-fill"></i></a>
        <a class="header-icon" href=""><i class="bi bi-lock-fill"></i></a>
        <a class="header-icon" href="?logout=1"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>
