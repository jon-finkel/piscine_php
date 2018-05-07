#!/usr/bin/php
<?php
    if ($argc > 1)
	    $array = explode(" ", $argv[1], PHP_INT_MAX);
    for ($k = 2; $k < $argc; ++$k) {
	    $array_new = explode(" ", $argv[$k], PHP_INT_MAX);
	    $size = count($array_new);
	    for ($p = 0; $p < $size; ++$p)
	    	if ($array_new[$p])
		    	array_push($array, $array_new[$p]);
    }

    if ($argc > 1) {
        sort($array, COUNT_NORMAL);
        $size = count($array);
        for ($k = 0; $k < $size; ++$k)
            if ($array[$k])
                printf("%s\n", $array[$k]);
    }
?>
