<?php
if (!isset($_GET['assignment']))
{
    header("Location: http://" . $_SERVER['SERVER_NAME']);
    die();
}   

// Handle post data
if (!empty($_POST))
{
    ChromePhp::log($_POST['comment']);
    ChromePhp::log(intval($_POST['grade']));
    if (!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        header("Location: http://" . $_SERVER['SERVER_NAME']);
        die();
    }
    
    $post_query = "SELECT id FROM comments WHERE user = :user";
    $post_params = array(':user' => $_SESSION['user']['id']);
    
    try
    {
        $post_stmt = $db->prepare($post_query);
        $post_result = $post_stmt->execute($post_params);
    }
    catch (PDOException $e)
    {
        die();   
    }
    $row = $post_stmt->fetch();
    
    if (!empty($row))
        die();
    else
    {
        $post_query = "INSERT INTO comments (assignment, user, text) VALUES (:assignment, :user, :text); " .
                        "INSERT INTO ratings (user, assignment, rating) VALUES (:user, :assignment, :rating)";
        $post_params = array(':assignment' => $_GET['assignment'],
                            ':user' => $_SESSION['user']['id'],
                            ':text' => $_POST['comment'],
                            ':rating' => $_POST['grade']);
        try
        {
            $post_stmt = $db->prepare($post_query);
            $post_result = $post_stmt->execute($post_params);
        }
        catch(PDOException $e)
        {
            die();
        }
    }
    
    // Redirect after submitting data
    header("Location: http://" . $_SERVER['SERVER_NAME'] . "?assignment=" . $_GET['assignment']);
    die();
}

$query = "SELECT comments.id, comments.assignment, text, comments.user, asgn_name, asgn_desc, score, num_scores, users.username, ratings.rating " .
          "FROM comments " .
          "INNER JOIN assignments "  .
          "ON assignments.id = :assignment " .
          "INNER JOIN users " .
          "ON users.id = comments.user " .
          "INNER JOIN ratings " .
          "ON ratings.assignment = :assignment AND ratings.user = comments.user";
$query_params = array(':assignment' => $_GET['assignment']);

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

// This occurs when no comments are found
if (empty($row))
{
    $query2 = "SELECT asgn_name, asgn_desc, score, num_scores FROM assignments WHERE id = :assignment";
        
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
    
    // Calculate grade to display
    $grade = intToGrade($row2['score'] / $row2['num_scores']);
    
    print "\t\t<ul data-role='listview' data-theme='a' data-divider-theme='c'>\n";
    print "\t\t\t<li data-role='list-divider'>" . 
        "\n\t\t\t\t<h1>" . $row2['asgn_name'] . "</h1>" . 
        "\n\t\t\t\t<p><strong>" . $row2['asgn_desc'] . "</strong></p>" .
        "\n\t\t\t\t<h3 class='ui-li-aside'><strong>" . $grade . "</strong></h3>" .
        "\n\t\t\t</li>\n";  
}
else
{
    $grade = intToGrade($row['score'] / $row['num_scores']);
    
    print "\t\t<ul data-role='listview' data-theme='a' data-divider-theme='a'>\n";
    print "\t\t\t<li data-role='list-divider'>" . 
        "\n\t\t\t\t<h1>" . $row['asgn_name'] . "</h1>" . 
        "\n\t\t\t\t<p><strong>" . $row['asgn_desc'] . "</strong></p>" .
        "\n\t\t\t\t<h3 class='ui-li-aside'><strong>" . $grade . "</strong></h3>" .
        "\n\t\t\t</li>\n";  
    
    do
    {
        $grade = intToGrade($row['rating']);
        
        print "\t\t\t<li>" .
            "\n\t\t\t\t<h2>" . $row['text'] . "</h2>" .
            "\n\t\t\t\t<p>Posted by <strong>" . $row['username'] . "</strong></p>" .
            "\n\t\t\t\t<h3 class='ui-li-aside'><strong>" . $grade . "</strong></h3>" .
            "\n\t\t\t</li>\n";
    } while ($row = $stmt->fetch());
}

print "\t\t</ul>";

?>

<div data-role="popup" data-theme="a" data-overlay-theme="a" data-corners="false" id="addComment">
    <a href="#"  data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
    <form style="width: 100%" action="?assignment=<?php echo $_GET['assignment'] ?>" method="POST" data-ajax="false">    
        <div data-role="header" data-theme="a"><h6>Comment</h6></div>
        <p class="ui-bar">
            <textarea name="comment" placeholder="Comment here"></textarea>
        </p>
        <p class="ui-bar">
            <label for="select-grade">Rate Assignment</label>
            <select data-mini="true" name="grade" id="select-grade" data-native-menu="true">
                <option value="12">A+</option>
                <option value="11">A</option>
                <option value="10">A-</option>
                <option value="9">B+</option>
                <option value="8">B</option>
                <option value="7">B-</option>
                <option value="6">C+</option>
                <option value="5">C</option>
                <option value="4">C-</option>
                <option value="3">D+</option>
                <option value="2">D</option>
                <option value="1">D-</option>
                <option value="0">F</option>
            </select>
        </p>
        <p class="ui-bar">
            <button type="submit" data-mini="true">Submit</button>
        </p>
    </form>
</div>
