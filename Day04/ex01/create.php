<?php

if (isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['submit'])) {
    if ($_POST['login'] == "" || $_POST['passwd'] == "" || $_POST['submit'] !== "OK") {
        echo "ERROR\n";
        return;
    }
    $path = "/Users/nfinkel/42/MAMP/apache2/htdocs/private";
    if (!file_exists($path))
        mkdir($path, 0700);
    $path .= "/passwd";
    if (file_exists($path)) {
        $file = unserialize(file_get_contents($path));
        foreach($file as $value) {
            if ($value['login'] === $_POST['login']) {
                echo "ERROR\n";
                return;
            }
        }
    }
    $array['login'] = $_POST['login'];
    $array['passwd'] = hash(whirlpool, $_POST['passwd']);
    $file[] = $array;
    file_put_contents($path, serialize($file));
    echo "OK\n";
}

?>
