<?php
include "auth.php";

session_start();

echo '<!DOCTYPE html><html><head></head><body>';
if (isset($_POST['login']) && isset($_POST['passwd']) && auth($_POST['login'], $_POST['passwd'])) {
    $_SESSION['loggued_on_user'] = $_POST['login'];
    echo '<iframe name="chat" src="chat.php" width="100%" height="550px"></iframe>';
    echo '<iframe name="speak" src="speak.php" width="100%" height="50px"></iframe>';
}
else
{
    echo 'Invalid credentials!<br />';
    echo '<a href="index.html">Back to login page</a>';
    $_SESSION['loggued_on_user'] = "";
}
echo '<a href="logout.php">Logout</a></body></html>';

?>
