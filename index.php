<?php

include_once('config.php');

$page = isset($_GET['p']) ? $_GET['p'] : "home";

include_once('/include/header.php');

switch($page)
{
    case "home":
        include_once('/pages/home.php');
        break;
    case "login":
        include_once('/pages/login.php');
        break;
    case "register":
        include_once('/pages/register.php');
        break;
    default:
        include_once('/pages/notfound.php');
        break;
}
include_once('/include/footer.php');
?>