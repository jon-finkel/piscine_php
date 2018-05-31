<?php

if (isset($_POST['login']) && isset($_POST['oldpw']) && isset($_POST['newpw']) && isset($_POST['submit'])) {
    if ($_POST['login'] == "" || $_POST['oldpw'] == "" || $_POST['newpw'] == "" || $_POST['submit'] !== "OK") {
        echo "ERROR\n";
        return;
    }
    $path = "/Users/nfinkel/42/MAMP/apache2/htdocs/private/passwd";
    if (!file_exists($path)) {
        echo "ERROR\n";
        return;
    }
    $file = unserialize(file_get_contents($path));
    foreach($file as $key => $value) {
        if ($value['login'] === $_POST['login']) {
            if ($value['passwd'] !== hash(whirlpool, $_POST['oldpw']))
                break;
            $file[$key]['passwd'] = hash(whirlpool, $_POST['newpw']);
            file_put_contents($path, serialize($file));
            echo "OK\n";
            return;
        }
    }
    echo "ERROR\n";
}

?>
