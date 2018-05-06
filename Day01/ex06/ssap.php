#!/usr/bin/php
<?php
    $array = NULL;
    for ($k = 1; $k < $argc; ++$k) {
        if ($k == 1)
            $array = explode(" ", $argv[$k], PHP_INT_MAX);
        else {
            $array_new = explode(" ", $argv[$k], PHP_INT_MAX);
            $size = count($array_new);
            for ($p = 0; $p < $size; ++$p)
                array_push($array, $array_new[$p]);
        }
    }
    if ($array) {
        sort($array, COUNT_NORMAL);
        $size = count($array);
        for ($k = 0; $k < $size; ++$k)
            printf("%s\n", $array[$k]);
    }
?>
