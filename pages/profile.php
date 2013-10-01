<?php
    if (!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }

    if (isset($_GET['addclass']))
    {
        // Make sure they aren't already enrolled in this class
        $query = "SELECT id FROM enrolled WHERE user = :user AND class = :class";
        $query_params = array(':user' => $_SESSION['user']['id'],
                              ':class' => $_GET['addclass']);
        
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
        
        // If we have a result, refresh the page without inserting duplicate into the db
        if (!empty($row))
        {
            header("Location: http://" . $_SERVER['SERVER_NAME'] . "?p=profile");
            die();  
        }
        
        $query = "INSERT INTO enrolled (user, class) VALUES (:user, :class)";
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $e)
        {
            ChromePhp::log($e);
            die();   
        }
    }
?>

            <ul data-role="listview" data-theme="b" data-divider-theme="a">
                <li data-role="list-divider">Info</li>
                <li><h3><?php echo $_SESSION['user']['username'] ?></h3></li>
                <li><h3><?php echo $_SESSION['user']['email'] ?></h3></li>
                <li data-role="list-divider">Current Courses</li>
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
    ChromePhp::log($e);
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
        print "\t\t<li><a href='?class=" . $row['id'] . "'>" .
              "\n\t\t\t<h3>" . $row['course'] . "</h3>" .
              "\n\t\t\t<p>" . $row['professor'] . "</p>" .
              "\n\t\t</a></li>\n";        
    } while ($row = $stmt->fetch());
}

?>
            </ul>
            <div data-role="popup" data-theme="a" data-overlay-theme="a" data-corners="false" id="addClass">
<?php
$query = "SELECT colleges.college, classes.id, classes.course, classes.professor " .
        "FROM colleges " .
        "INNER JOIN courses " .
        "ON colleges.abbr = courses.college " .
        "INNER JOIN classes " .
        "ON CONCAT(courses.college, ' ', courses.course_number) = classes.course " .
        "ORDER BY college ASC, course ASC, professor ASC";

try
{
    $stmt = $db->prepare($query);
    $result = $stmt->execute();
}
catch(PDOException $e)
{
    ChromePhp::log($e);
    die();
}

$row = $stmt->fetch();

// Used to tell when to start a new collapsible view
$prev_college = $row['college'];

// Used to tell when to start a new listview
$prev_course = $row['course'];

print "\t\t<div class='ui-bar-p' data-role='collapsible' data-theme='a'>" .
      "\n\t\t\t<h2>" . $row['college'] . "</h2>" . 
      "\n\t\t\t<div data-role='collapsible' data-theme='a'>" .
      "\n\t\t\t\t<h2>" . $row['course'] . "</h2>" . 
      "\n\t\t\t\t<ul data-role='listview' data-divider-theme='a'>\n";

do
{
    
    if ($row['course'] != $prev_course && $row['college'] == $prev_college)
    {
        print "\t\t\t\t</ul>\n";
        print "\t\t\t</div>\n";
        print "\t\t\t<div data-role='collapsible' data-theme='a'>" .
              "\n\t\t\t\t<h2>" . $row['course'] . "</h2>" . 
              "\n\t\t\t\t<ul data-role='listview' data-divider-theme='a'>\n";
        $prev_course = $row['course'];
    }
    
    if ($row['college'] != $prev_college)
    {
        print "\t\t\t\t</ul>\n";
        print "\t\t\t</div>\n";
        print "\t\t</div>\n";
        print "\t\t<div class='ui-bar-p' data-role='collapsible' data-theme='a'>" .
              "\n\t\t\t<h2>" . $row['college'] . "</h2>\n";
        print "\t\t\t<div data-role='collapsible' data-theme='a'>" .
              "\n\t\t\t\t<h2>" . $row['course'] . "</h2>" . 
              "\n\t\t\t\t<ul data-role='listview' data-divider-theme='a'>\n";
        
        // Need to reset both of these for next pass
        $prev_college = $row['college'];
        $prev_course = $row['course'];
    }
    
    print "\t\t\t\t\t<li><a href='?p=profile&addclass=" . $row['id'] . "'><h1>" . $row['professor'] . "</h1></a></li>\n";
    
} while ($row = $stmt->fetch());

print "\t\t</div>\n";

?>
            </div>