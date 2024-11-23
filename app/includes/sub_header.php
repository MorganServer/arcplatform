<?php
// Get the current script name
$currentPageName = basename($_SERVER['SCRIPT_NAME'], '.php');

// Handle specific cases for nicer display
if ($currentPageName == 'index') {
    $currentPageName = basename(__DIR__); // Use directory name if index.php
}

// Capitalize the first letter
$currentPageName = ucfirst($currentPageName);
?>
<div class="page_header">
    <div class="left">
        <h6><?php echo $currentPageName; ?></h6>

    </div>
    <div class="right">
        <p style="padding-right: 15px;">Welcome, <?php echo $_SESSION['full_name']; ?></p>
        <a class="header-icon" href=""><i class="bi bi-info-circle-fill"></i></a>
        <a class="header-icon" href=""><i class="bi bi-lock-fill"></i></a>
        <a class="header-icon" href="?logout=1"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>