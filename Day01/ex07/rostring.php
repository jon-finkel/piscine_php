#!/usr/bin/php
<?php
    $array = explode(" ", $argv[1], PHP_INT_MAX);
    $max = count($array);
    $check = false;
    for ($k = 1; $k < $max; ++$k) {
        if ($array[$k] == null)
            continue;
        if ($check == true)
            printf(" ");
        printf("%s", $array[$k]);
        $check = true;
    }
    if ($check == true)
        printf(" %s\n", $array[0]);
    else if ($argc > 1)
        printf("%s\n", $array[0]);
?>
