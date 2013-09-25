    <div data-theme="a" data-role="page" id="home">
        <header data-role="header">
            <h1>Radford Rate 'Em</h1>
        </header>
        <article data-role="content">
<?php
                    
    $query = "SELECT college, abbr FROM colleges ORDER BY college ASC";
    
    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
    }
    catch(PDOException $e)
    {
        die();   
    }

    // Loop through all of the colleges
    while ($row = $stmt->fetch())
    {
        $query = "SELECT college, course_number, name FROM courses WHERE college = :abbr";
        
        $query_params = array(':abbr' => $row['abbr']);
        
        try
        {
            $stmt2 = $db->prepare($query);
            $result2 = $stmt2->execute($query_params);
        }
        catch(PDOException $e)
        {
            die();   
        }
        
        $row2 = $stmt2->fetch();
        // If nothing was found for that abbreviation, skip over to the next
        if (!$row2)
            continue;
        
        // Create the div and list
        print "\t\t<div data-role='collapsible' data-theme='a'>\n";
        print "\t\t\t<h2>" . $row['college'] . "</h2>\n";
        print "\t\t\t<ul data-role='listview' data-divider-theme='a'>\n";
        
        // Loop through each result and create a list item for each one
        do
        {
            // Adding tabs makes things a little hard to read, but the output is much nicer
            $course = $row2['college'] . " " . $row2['course_number'];
            print "\t\t\t\t<li><a href='?course=" . $course . "'>" . 
                  "\n\t\t\t\t\t<h1>" . $row2['college'] . " " . $row2['course_number'] . "</h1>" . 
                  "\n\t\t\t\t\t<p>" . $row2['name'] . "</p></a>" .
                  "\n\t\t\t\t</li>\n";  
        } while ($row2 = $stmt2->fetch());
        
        print "\t\t\t</ul>\n";
        print "\t\t</div>\n";
    }
?>
    <!--
    <h2>Information Technology</h2>
    <ul data-role="listview" data-divider-theme="a">
        <li><a href="#">
            <h1>ITEC 120</h1></a>
        </li>
        <li><a href="#">
            <h1>Ian Barland</h1>
            <img src="http://www.radford.edu/ibarland/Pictures/ibarland-RU-portrait.jpg" alt="Ian Barland" />
            <p>ITEC 380</p></a>
        </li>
        <li><a href="#">
            <h1>Tracy Lewis</h1>
            <img src="http://www.radford.edu/~tlewis32/images/Lewis.Tracy.01.jpg" alt="Tracy Lewis" />
            <p>ITEC 471</p></a>
        </li>
    </ul>
    -->
        </article>
        