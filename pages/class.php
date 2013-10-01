<?php
    if (!isset($_GET['class']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }   
    $query = "SELECT assignments.id, class, asgn_name, asgn_desc, score, num_scores, professors.grade, course, professor " .
                "FROM assignments " .
                "INNER JOIN classes " . 
                "ON classes.id = :class AND assignments.class = classes.id " .
                "INNER JOIN professors " .
                "ON professors.name = classes.professor";
    $query_params = array(':class' => $_GET['class']);
    
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
        $query2 = "SELECT professor, course FROM classes WHERE id = :class";
        
        try
        {
            $stmt2 = $db->prepare($query2);
            $result2 = $stmt2->execute($query_params);
        }
        catch (PDOException $e)
        {
            die();   
        }
        
        $row2 = $stmt2->fetch();
        
        print "<h2>No assignments found for " . $row2['course'] . " | " . $row2['professor'] . "</h2>\n";

    }
    else
    {
        print "\t\t<ul data-role='listview' data-theme='a'>\n";
        print "\t\t\t<li data-role='list-divider'>" . $row['course'] . " | " . $row['professor'] . "</li>\n";
    
        do
        {
            print "\t\t\t<li><a href='?assignment=" . $row['id'] . "'>" . 
                "\n\t\t\t\t<h1>" . $row['asgn_name'] . " | " . intToGrade($row['score']/$row['num_scores']) . "</h1>" . 
                "\n\t\t\t\t<p>" . $row['asgn_desc'] . "</p></a>" .
                
                "\n\t\t\t</li>\n";
        } while ($row = $stmt->fetch());
    
        print "\t\t</ul>";
    }
?>