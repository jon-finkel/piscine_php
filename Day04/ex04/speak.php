<?php

session_start();
echo '<html><head><script langage="javascript">top.frames[\'chat\'].location = \'chat.php\';</script></head><body>';
if ($_SESSION['loggued_on_user'] !== "") {
    echo '<form action="speak.php" method="post">Message:<input name="msg" type="text"><input name="send" type="submit" value="send"></form>';
}
if (isset($_POST['msg']) && $_POST['msg'] !== "" && isset($_POST['send']) && $_POST['send'] === "send") {
    $path = "/Users/nfinkel/42/MAMP/apache2/htdocs/private";
    if (!file_exists($path))
        mkdir($path);
    $path .= "/chat";
    $fp = fopen($path, 'r+');
    if (flock($fp, LOCK_EX)) {
        $file = unserialize(file_get_contents($path));
        $msg['login'] = $_SESSION['loggued_on_user'];
        $msg['time'] = time();
        $msg['msg'] = $_POST['msg'];
        $file[] = $msg;
        file_put_contents($path, serialize($file));
        fclose($fp);
    }
}
echo '</bddy></html>';

?>
