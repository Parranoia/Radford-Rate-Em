<?php

require('../config.php');

include_once("../include/chromephp.php");

$errors = array();

if (!empty($_POST))
{
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        $errors[0] = "Invalid email address";
    if (empty($_POST['username']))
        $errors[1] = "Please enter a username";
    if (empty($_POST['password']))
        $errors[2] = "Please enter a password";
    if ($_POST['password'] !== $_POST['pass_confirm'])
        $errors[3] = "Passwords do not match";
    
    if (empty($errors))
    {                
        $query = "SELECT 1 FROM users 
            WHERE username = :username";
        
        $query_params = array(':username' => $_POST['username']); 
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $e)
        {
            die("Error: " . $e->getMessage());
        }
        
        $row = $stmt->fetch();
        
        if ($row)
        {
            $errors[4] = "This username already exists";
        }
        
        $query = "SELECT 1 FROM users WHERE email = :email";
        
        $query_params = array(':email' => $_POST['email']);
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $e)
        {
            die("Error: " . $e->getMessage()); 
        }
        
        $row = $stmt->fetch();
        
        if ($row)
        {
            $errors[5] = "This email is already in use";
        }
        
        if (empty($errors))
        {        
            
            ChromePhp::log("6");
            $query = "INSERT INTO users (username, password, salt, email) VALUES
                        (:username, :password, :salt, :email)";
            
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            
            $password = hash('sha256', $_POST['password'] . $salt);
            
            $query_params = array(':username' => $_POST['username'],
                                  ':password' => $password,
                                  ':salt'     => $salt,
                                  ':email'    => $_POST['email']);
            
            try
            {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $e)
            {
                die("Error: " . $e->getMessage());   
            }
            
            header("Location: http://" . $_SERVER['SERVER_NAME'] . "/?p=login");
            die("Redirecting");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Radford Rate 'Em</title>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
</head>
<body>
    <div data-role="content">
        <?php 
            if (!empty($errors))
                foreach ($errors as $error)
                    print("<code style='color:red'>$error</code><br>");
        ?>
        <form id="register" action="index.php" method="post">
            <input type="email" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>" />
            <input type="text" name="username" placeholder="Username" value="<?php echo $_POST['username'] ?>"/>
            <input type="password" name="password" placeholder="Password" />
            <input type="password" name="pass_confirm" placeholder="Confirm Password" />
            <input type="submit" value="Register" />
        </form>
    </div>
</body>
</html>