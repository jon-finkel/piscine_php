<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <?php

        session_start();
        if (isset($_GET['login']) && $_GET['login'] != null
            && isset($_GET['passwd']) && $_GET['passwd'] != null
            && isset($_GET['submit']) && $_GET['submit'] == "OK") {
            $_SESSION['login'] = $_GET['login'];
            $_SESSION['passwd'] = $_GET['passwd'];
        }

        ?>
        <form action="index.php" method="get">
            <fieldset>
                <legend>Personal information</legend>
                Identifiant: <input type="text" name="login" value="<?php echo $_SESSION['login'];?>"><br />
                Mot de passe: <input type="text" name="passwd" value="<?php echo $_SESSION['passwd'];?>"><br />
                <input type="submit" value="OK">
            </fieldset>
        </form>
    </body>
</html>
