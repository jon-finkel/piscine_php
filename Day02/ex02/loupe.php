#!/usr/bin/php
<?php

    if ($argc > 1) {
        $file = fopen($argv[1], 'r');
        while (($line = fgets($file, 4096)) !== false) {
          if (($pos = strpos($line, "<a href=")))
            printf("%s\n", substr($line, $pos));
        }
        fclose($file);
	}

?>
