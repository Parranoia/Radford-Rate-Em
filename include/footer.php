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
        print "\t\t\t<li><a data-icon='plus' href='#addAssignment' data-rel='popup' data-position-to='window'>Add Assignment</a></li>\n";
    if (isset($_GET['assignment']))
        print "\t\t\t<li><a data-icon='plus' href='#addComment' data-rel='popup' data-position-to='window'>Add Comment</a></li>\n";
    
    if (!isset($_GET['p']))
        print "\t\t\t<li><a data-icon='info' href='?p=profile'>View Profile</a></li>\n";
    else
    {
        if ($_GET['p'] == 'profile')
        {
            print "\t\t\t<li><a data-icon='gear' href='?p=editProfile'>Edit Profile</a></li>\n";
            print "\t\t\t<li><a data-icon='plus' href='#addClass' data-rel='popup' data-position-to='window'>Add Class</a></li>\n";
        }
        else if ($_GET['p'] == 'editProfile')
        {
            //print "\t\t\t<li><a data-icon='check' href='?p=editProfile'>Save Changes</a></li>\n";
            print "\t\t\t<li><a data-icon='info' href='?p=profile'>View Profile</a></li>\n";
        }
    }
    print "\t\t\t<li><a data-icon='delete' href='/login/logout.php'>Log Out</a></li>\n"; 
}
?>
                </ul>
            </nav>
        </footer>
    </div>
</body>
</html>