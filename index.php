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
else if (isset($_GET['assignment']))
{
    include_once('/pages/assignment.php');   
}
else if (isset($_GET['p']))
{
    if ($_GET['p'] == 'profile')
    {
        include_once('/pages/profile.php');   
    }
    else if ($_GET['p'] == 'editProfile')
    {
        include_once('/pages/edit_profile.php');   
    }
}
else
{
    include_once('/pages/home.php');   
}

include_once('/include/footer.php');
?>