<?php
    if (!isset($_GET['class']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }   

    // Handle form submission
    if (!empty($_POST))
    {
        // Double check user is loggeed in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header("Location: http://" . $_SERVER['SERVER_NAME']);
            die();
        }
        
        ChromePhp::log("1");
        
        $post_query = "INSERT INTO assignments (class, asgn_name, asgn_desc) VALUES (:class, :name, :desc)";
        $post_params = array(':class' => $_GET['class'],
                            ':name' => $_POST['assignment'],
                            ':desc' => $_POST['description']);
        try
        {
            $post_stmt = $db->prepare($post_query);
            $post_result = $post_stmt->execute($post_params);
        }
        catch(PDOException $e)
        {
            ChromePhp::log($e);
            die();
        }
        
        // Redirect after submitting data
        header("Location: http://" . $_SERVER['SERVER_NAME'] . "?class=" . $_GET['class']);
        die();
    }

    $query = "SELECT assignments.id, class, asgn_name, asgn_desc, professors.grade, course, professor " .
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
                "\n\t\t\t\t<h1>" . $row['asgn_name'] . " | " . intToGrade($row['grade']) . "</h1>" . 
                "\n\t\t\t\t<p>" . $row['asgn_desc'] . "</p></a>" .
                
                "\n\t\t\t</li>\n";
        } while ($row = $stmt->fetch());
    
        print "\t\t</ul>";
    }
?>

        <div data-role="popup" data-theme="a" data-overlay-theme="a" data-corners="false" id="addAssignment">
            <form style="width: 100%" action="?class=<?php echo $_GET['class'] ?>" method="POST" data-ajax="false">    
                <div data-role="header" data-theme="a"><h6>Assignment</h6></div>
                <p class="ui-bar">
                    <input type="text" name="assignment" placeholder="Assignment" />
                    <textarea name="description" placeholder="Description"></textarea>
                </p>
                <p class="ui-bar">
                    <button type="submit" data-mini="true">Submit</button>
                </p>
            </form>
        </div>
