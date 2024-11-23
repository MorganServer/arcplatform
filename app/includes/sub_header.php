<?php
$currentPageName = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<div class="page_header">
    <div class="left">
        <h6><?php echo ucfirst($currentPageName); ?></h6>

    </div>
    <div class="right">
        <p style="padding-right: 15px;">Welcome, <?php echo $_SESSION['full_name']; ?></p>
        <a class="header-icon" href=""><i class="bi bi-info-circle-fill"></i></a>
        <a class="header-icon" href=""><i class="bi bi-lock-fill"></i></a>
        <a class="header-icon" href="?logout=1"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>