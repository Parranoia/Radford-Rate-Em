        <footer data-role="footer" data-position="fixed">
            <nav data-role="navbar">
                <ul>
                    <?php 
                    if (empty($_SESSION['user'])) 
                        print('<li><a href="/login">Login</a></li>') ?>
                    <li><a href="#">Link 2</a></li>
                    <?php 
                    if (!empty($_SESSION['user'])) 
                        print('<li><a href="/login/logout.php">Log Out</a></li>') ?>
                </ul>
            </nav>
        </footer>
    </div>
</body>
</html>