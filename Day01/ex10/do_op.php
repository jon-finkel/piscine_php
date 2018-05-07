#!/usr/bin/php
<?php
	if ($argc != 4) {
	    echo("Incorrect Parameters\n");
		return 0;
	}
	$num1 = preg_replace('/\s+/', '', $argv[1]);
    $num2 = preg_replace('/\s+/', '', $argv[3]);
    
	if (trim($argv[2]) === "+") {
	    if ($num1 + $num2 > PHP_INT_MAX)
	        echo "Overflow!\n";
	    else
    	    printf("%d\n", $num1 + $num2);
	}
	else if (trim($argv[2]) === "-") {
		if ($num1 - $num2 > PHP_INT_MAX)
			echo "Overflow!\n";
		else
			printf("%d\n", $num1 - $num2);
	}
	else if (trim($argv[2]) === "*") {
	    if ($num1 * $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
        else
	        printf("%d\n", $num1 * $num2);
	}
	else if (trim($argv[2]) === "/") {
	    if ($num1 / $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
 	    else if ($num2 == 0)
	        echo "Don't divide by zero...\n";
	    else
    	    printf("%d\n", $num1 / $num2);
    }
    else if (trim($argv[2]) === "%") {
	    if ($num1 % $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
	    else if ($num2 == 0)
	        echo "Don't divide by zero...\n";
	    else
    	    printf("%d\n", $num1 % $num2);
    }
?>
