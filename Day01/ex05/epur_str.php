#!/usr/bin/php
<?php
    $array = explode(" ", $argv[1], PHP_INT_MAX);
    $max = count($array, COUNT_NORMAL);
    $check = false;
    for ($k = 0; $k < $max; ++$k)
        if ($array[$k] != NULL) {
            if ($check)
                printf(" ");
            printf("%s", $array[$k]);
            $check = true;
        }
    if ($check)
        printf("\n");
?>
