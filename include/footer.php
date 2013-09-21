<?php
$page = isset($_GET['p']) ? $_GET['p'] : "";
if ($page != "login")
{
?>
        <footer data-role="footer" data-position="fixed">
            <nav data-role="navbar">
                <ul>
                    <?php if (empty($_SESSION['user'])) { ?><li><a href="?p=login">Login</a></li><?php } ?>
                    <li><a href="#">Link 2</a></li>
                    <li><a href="#">Link 3</a></li>
                </ul>
            </nav>
        </footer>
<?php } ?>
    </div>
</body>
</html>