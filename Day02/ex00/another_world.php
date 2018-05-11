#!/usr/bin/php
<?php
    if ($argc > 1) {
	    $cleaned = preg_replace('/(^[\s+])|([\s]+)/', ' ', trim($argv[1]), PHP_INT_MAX);
	    printf("%s\n", $cleaned);
    }
?>