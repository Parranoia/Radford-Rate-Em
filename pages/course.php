<?php
    if (!isset($_GET['course']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }

    //$query = "SELECT id, course, professor FROM classes WHERE course = (SELECT (CONCAT(college, ' ', course_number)) FROM courses WHERE id = :course)";
    $query = "SELECT classes.id, classes.course, classes.professor, professors.grade " .
                "FROM classes " . 
                "INNER JOIN professors " .
                 "ON professors.name = classes.professor " . 
                 "AND classes.course = (SELECT (CONCAT(college, ' ', course_number)) " . 
                 "FROM courses WHERE id = :course)";
    
    $query_params = array(':course' => $_GET['course']);

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
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }
    
    print "\t\t<ul data-role='listview' data-divider-theme='a'>\n";
    print "\t\t\t<li data-role='list-divider'>" . $row['course'] . "</li>\n";

    do
    {        
        print "\t\t\t<li><a href='?class=" . $row['id'] . "'>" . 
           "\n\t\t\t\t<h1>" . $row['professor'] . "</h1>" . 
           "\n\t\t\t\t<p>Grade: " . intToGrade($row['grade']) . "</p></a>" .
           "\n\t\t\t</li>\n";
    } while($row = $stmt->fetch());

    print "\t\t</ul>";
?>