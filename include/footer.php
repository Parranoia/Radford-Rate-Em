        </article>
        <footer data-role="footer" data-position="fixed">
            <nav data-role="navbar" data-iconpos='left'>
                <ul>
<?php 
if (empty($_SESSION['user'])) 
    print("<li><a href='/login'>Login</a></li>\n");
else 
{
    if (isset($_GET['class']))
        print "\t\t\t<li><a data-icon='plus' href='/pages/add_assignment.php'>Add Assignment</a></li>\n";
    print "\t\t\t<li><a data-icon='info' href='/pages/profile.php'>View Profile</a></li>\n";
    print "\t\t\t<li><a data-icon='delete' href='/login/logout.php'>Log Out</a></li>\n"; 
}
?>
                </ul>
            </nav>
        </footer>
    </div>
</body>
</html>