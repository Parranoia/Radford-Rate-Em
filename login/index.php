<?php

require('../config.php');

include_once("../include/chromephp.php");

// If already logged in, redirect to the homepage
if (isset($_SESSION['user']) || !empty($_SESSION['user']))
{
    header("Location: http://" . $_SERVER['SERVER_NAME']);
    die();
}

$user = "";

// Check if data has been submitted or not
if (!empty($_POST))
{
    $query = "SELECT id, username, password, salt, email FROM users WHERE username = :username";
    
    $query_params = array(':username' => $_POST['username']);
    
    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $e)
    {
        die();   
    }
    
    $success = false;
    
    $row = $stmt->fetch();
    if ($row)
    {
        // Hash the user-given password and compare with what's in the db
        $pass_hash = hash('sha256', $_POST['password'] . $row['salt']);
        
        if ($pass_hash === $row['password'])
            $success = true;
    }
    
    if ($success)
    {
        // Remove sensitive information from being stored later
        unset($row['salt']);
        unset($row['password']);
        
        $_SESSION['user'] = $row;
        
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }
    else
    {
        $user = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
        header("Location: http://" . $_SERVER['SERVER_NAME'] . "/login");
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Radford Rate 'Em</title>
    <link rel="stylesheet" href="/css/default.css" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile.structure-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
</head>
<body>
    <div data-role="page" data-theme="b">
        <div data-role="content">
            <?php
                if ($user != "")
                    print("<code style='color:red'>Username or password incorrect</code><br />");
            ?>
            <form id="login" action="/login/index.php" method="post" data-ajax="false">
                <input type="text"  maxlength="28"name="username" placeholder="Username" value="<?php echo $user ?>" />
                <input type="password" maxlength="28" name="password" placeholder="Password" />
                <input type="submit" value="Login" />
            </form>
            <a href="/register" data-role="button">Register</a>
        </div>
    </div>
</body>
</html>