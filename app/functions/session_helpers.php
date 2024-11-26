<?php


function redirectIfNotLoggedIn()
{
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/");
        exit;
    }
}

function redirectIfLoggedIn()
{
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        header("Location: " . BASE_URL . "/client_list");
        exit;
    }
}
?>
