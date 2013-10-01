<?php

// Check if the user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user']))
{
    header("Location: http://" . $_SERVER['SERVER_NAME']);
    die();
}

if (isset($_GET['delClass']))
{
    $query = "DELETE FROM enrolled WHERE user = :user AND class = :class";
    $query_params = array(':user' => $_SESSION['user']['id'],
                          ':class' => $_GET['delClass']);
    
    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $e)
    {
        die();   
    }
}

$errors = array();

// Handle post data
if (!empty($_POST))
{
    // No changes were made, don't query the db
    if ($_POST['email'] == $_SESSION['user']['email'] && $_POST['username'] == $_SESSION['user']['username'])
    {
        unset($_POST);
        header("Location: http://" . $_SERVER['SERVER_NAME'] . "?p=profile");
        die();
    }
    
    if ($_POST['username'] != $_SESSION['user']['username'])
    {
        $query = "SELECT 1 FROM users WHERE username = :username";
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
        
        $row = $stmt->fetch();
        
        if (!empty($row))
        {
            $errors[0] = "That username already exists";   
        }
    }
    
    if ($_POST['email'] != $_SESSION['user']['email'])
    {
        $query = "SELECT 1 FROM users WHERE email = :email";
        $query_params = array(':email' => $_POST['email']);
        
        try
        {
            $stmt = $db->prepare($query);
            ChromePhp::log($stmt);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $e)
        {
            die();   
        }
        
        $row = $stmt->fetch();
        
        if (!empty($row))
        {
            $errors[1] = "That email already exists";   
        }
    }
    
    if (empty($errors))
    {
        $query = "UPDATE users SET email = :email, username = :username WHERE id = :id";
        $query_params = array(':email' => $_POST['email'],
                              ':username' => $_POST['username'],
                              ':id' => $_SESSION['user']['id']);
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $e)
        {
            die();   
        }
        
        // Update the session variables
        $_SESSION['user']['email'] = $_POST['email'];
        $_SESSION['user']['username'] = $_POST['username'];
    }
}

?>

            <ul data-role="listview" data-theme="b" data-divider-theme="a">
                <li data-role="list-divider">Info</li>
                <li>
                    <?php 
                        if (!empty($errors))
                            foreach ($errors as $error)
                                print("<code style='color:red'>$error</code><br>");
                    ?>
                    <form action="?p=editProfile" method="POST">
                        <input type="text" name="username" value="<?php echo $_SESSION['user']['username'] ?>" onchange="enableSave()" />
                        <input type="email" name="email" value="<?php echo $_SESSION['user']['email'] ?>" />
                        <button type="submit" id="submitSave" data-icon="check" data-mini="true" data-inline="true">Save Changes</button>
                    </form>
                </li>
                <li data-role="list-divider">Delete Courses</li>
<?php 
$query = "SELECT classes.id, class, course, professor " .
        "FROM enrolled " .
        "INNER JOIN classes " .
        "ON class = classes.id AND user = :user";
$query_params = array(':user' => $_SESSION['user']['id']);

try
{
    $stmt = $db->prepare($query);
    $result = $stmt->execute($query_params);
}
catch(PDOException $e)
{
    die();   
}

$row = $stmt->fetch();

if (empty($row))
{
    print "\t\t<li>You are not enrolled in any classes</li>\n";
}
else
{
    do
    {
        print "\t\t<li><a href='?p=editProfile&delClass=" . $row['id'] . "'>" .
              "\n\t\t\t<h3>" . $row['course'] . "</h3>" .
              "\n\t\t\t<p>" . $row['professor'] . "</p>" .
              "\n\t\t</a></li>\n";        
    } while ($row = $stmt->fetch());
}

?>
            </ul>