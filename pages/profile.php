<?php
    if (!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }
?>

            <ul data-role="listview" data-theme="b" data-divider-theme="a">
                <li data-role="list-divider">Info</li>
                <li><h3><?php echo $_SESSION['user']['username'] ?></h3></li>
                <li><h3><?php echo $_SESSION['user']['email'] ?></h3></li>
                <li data-role="list-divider">Current Courses</li>
                <li>
                    <h3>ITEC 110</h3>
                    <p>Muang M. Htay</p>
                </li>
                <li><h3>Course 2</h3></li>
                <li><h3>Course 3</h3></li>
                <li><h3>Course 4</h3></li>
            </ul>
