<?php

include_once('../config.php');

if (!empty($_POST))
{
    echo "Post is not empty";
    $query = "SELECT id, username, password, salt, email FROM users WHERE username = :username";
    
    $query_params = array(':username' => $_POST['username']);
    
    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $e)
    {
        die("Failed to run query: " . $e->getMessage());   
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
        if (isset($_SESSION['failed']))
            unset($_SESSION['failed']);
        
        $_SESSION['user'] = $row;
        
        header("Location: " . $_SERVER['SERVER_ADDR']);
        die();
    }
    else
    {
        $_SESSION['failed'] = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
        header("Location: http://" . $_SERVER['SERVER_NAME'] . "/?p=login");
        die();
    }
}
?>