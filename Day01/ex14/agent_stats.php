#!/usr/bin/php
<?php

if ($argv[1] == "moyenne") {
    $sum = 0; $n = 0;
    while ($line = fgets(STDIN)) {
        if ($line == "User;Note;Noteur;Feedback\n")
            continue;
        $array = explode(';', $line, PHP_INT_MAX);
        if ($array[1] != "" && $array[2] != "moulinette") {
            ++$n; $sum += intval($array[1]);
        }
    }
    if ($n)
        echo $sum / $n, "\n";
}
else if ($argv[1] == "moyenne_user" || $argv[1] == "ecart_moulinette") {
    $array = array();
    while ($line = fgets(STDIN)) {
        if ($line == "User;Note;Noteur;Feedback\n")
            continue;
        array_push($array, $line);
    }
    sort($array, SORT_STRING);
    $current_user = ""; $sum = 0; $n = 0; $moulinette = 0;
    foreach ($array as $value) {
        $data = explode(';', $value, PHP_INT_MAX);
        if ($current_user != $data[0]) {
            if ($n) {
                if ($argv[1] == "moyenne_user")
                    echo $current_user, ":", $sum / $n, "\n";
                else
                    echo $current_user, ":", $sum / $n - $moulinette, "\n";
            }
            $current_user = $data[0]; $sum = 0; $n = 0;
        }
        if ($data[1] != "" && $data[2] != "moulinette") {
            ++$n; $sum += intval($data[1]);
        }
        else if ($data[2] == "moulinette")
            $moulinette = $data[1];
    }
    if ($n && $argv[1] == "moyenne_user")
        echo $current_user, ":", $sum / $n, "\n";
    else if ($n)
        echo $current_user, ":", $sum / $n - $moulinette, "\n";
}

?>
