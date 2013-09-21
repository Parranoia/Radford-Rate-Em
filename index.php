<?php

include_once('/config.php');

$page = isset($_GET['p']) ? $_GET['p'] : "home";

include_once('/include/header.php');

switch($page)
{
    case "home":
        include_once('/pages/home.php');
        break;
    default:
        include_once('/pages/notfound.php');
        break;
}
include_once('/include/footer.php');
?>