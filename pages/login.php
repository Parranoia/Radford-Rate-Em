    <div data-role="content" id="splash">
        <?php
        if (isset($_SESSION['failed']))
        {
            print('<code style="color:red">Invalid username or password</code>');
        } 
        ?>
        <form id="login" action="../../login/do_login.php" method="post">
            <input type="text" name="username" placeholder="Username" value="<?php if (isset($_SESSION['failed'])) echo $_SESSION['failed']; ?>" />
            <input type="password" name="password" placeholder="Password" />
            <input type="submit" value="Login" />
        </form>
        <a data-role="button" href="/register">Register</a>
    