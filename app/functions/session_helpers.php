<?php
function redirectIfNotLoggedIn()
{
    session_start();
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/");
        exit;
    }
}

function redirectIfLoggedIn()
{
    session_start();
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/dashboard");
        exit;
    }
}
?>
