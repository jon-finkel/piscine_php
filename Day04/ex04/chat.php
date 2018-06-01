<?php

$path = "/Users/nfinkel/42/MAMP/apache2/htdocs/private/chat";
if (file_exists($path)) {
    $file = unserialize(file_get_contents($path));
    date_default_timezone_set('Europe/Paris');
    echo '<!DOCTYPE html><html><body>';
    foreach($file as $value) {
        $line = "[" . date('H:i', $value['time']) . "] <b>" . $value['login'] . "</b>: " . $value['msg'] . "<br />";
        echo $line;
    }
    echo '</body></html>';
}

?>
