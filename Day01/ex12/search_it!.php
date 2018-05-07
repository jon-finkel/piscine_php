#!/usr/bin/php
<?php
    for ($k = $argc; $k > 1; --$k)
        if (($pos = strpos($argv[$k], $argv[1])) !== false && $pos === 0) {
            $found = true;
            break;
        }
    if ($found) {
        $array = explode(':', $argv[$k], PHP_INT_MAX);
        printf("%s\n", $array[1]);
    }
?>
