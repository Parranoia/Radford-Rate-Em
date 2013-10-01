<?php

require('/config.php');

// Redirect if user is already logged in
if (isset($_SESSION['user']) || !empty($_SESSION['user']))
{
    header("Location: http://" . $_SERVER['SERVER_NAME']);
    die();
}

// Stores any errors that occur
$errors = array();

// Check whether or not data has been submitted
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
            die();
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
            die(); 
        }
        
        $row = $stmt->fetch();
        
        if ($row)
        {
            $errors[5] = "This email is already in use";
        }
        
        if (empty($errors))
        {        
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
                die();   
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
    <link rel="stylesheet" href="/css/default.css" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile.structure-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
</head>
<body>
    <div data-role="page" data-theme="a">
        <div data-role="content">
            <?php 
                if (!empty($errors))
                    foreach ($errors as $error)
                        print("<code style='color:red'>$error</code><br>");
            ?>
            <form id="register" action="/register/index.php" method="post" data-ajax="false">
                <input type="email" maxlenth="50" name="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" />
                <input type="text" maxlength="28" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>"/>
                <input type="password" maxlength="28" name="password" placeholder="Password" />
                <input type="password" maxlength="28" name="pass_confirm" placeholder="Confirm Password" />
                <input type="submit" value="Register" />
            </form>
            <footer data-role="footer" data-position="fixed">
                <nav data-role="navbar">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="#">Link 2</a></li>
                        <li><a href="#">Link 3</a></li>
                    </ul>
                </nav>
            </footer>
        </div>
    </div>
</body>
</html>