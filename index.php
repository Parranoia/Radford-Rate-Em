<?php

include_once('/config.php');
include_once('/include/header.php');

if (isset($_GET['course']))
{
    include_once('/pages/course.php');
}
else if (isset($_GET['class']))
{
    include_once('/pages/class.php');
}
else
{
    include_once('/pages/home.php');   
}

include_once('/include/footer.php');
?>