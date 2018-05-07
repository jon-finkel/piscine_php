#!/usr/bin/php
<?php
	if ($argc != 2) {
	    echo("Incorrect Parameters\n");
		return;
	}
	
	function get_op($string)
    {
        $k = -1;
        while ($string[++$k] != NULL)
            if ($string[$k] == '+' || $string[$k] == '-' || $string[$k] == '*' || $string[$k] == '/' || $string[$k] == '%')
                break;
        return $string[$k];
    }
	
    if (!($op = get_op($argv[1]))) {
	    echo "Syntax Error\n";
	    return;
    }
    
	if (count($array = explode($op, $argv[1], PHP_INT_MAX), COUNT_NORMAL) != 2) {
	    echo "Syntax Error\n";
        return;
    }
   
    if (!is_numeric($num1 = preg_replace('/\s+/', '', $array[0]))) {
 	    echo "Syntax Error\n";
        return;
    }
    
    if (!is_numeric($num2 = preg_replace('/\s+/', '', $array[1]))) {
 	    echo "Syntax Error\n";
        return;
    }
 
	if ($op === "+") {
	    if ($num1 + $num2 > PHP_INT_MAX)
	        echo "Overflow!\n";
	    else
    	    printf("%d\n", $num1 + $num2);
	}
	else if ($op === "-") {
		if ($num1 - $num2 > PHP_INT_MAX)
			echo "Overflow!\n";
		else
			printf("%d\n", $num1 - $num2);
	}
	else if ($op === "*") {
	    if ($num1 * $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
        else
	        printf("%d\n", $num1 * $num2);
	}
	else if ($op === "/") {
	    if ($num1 / $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
 	    else if ($num2 == 0)
	        echo "Don't divide by zero...\n";
	    else
    	    printf("%d\n", $num1 / $num2);
    }
    else if ($op === "%") {
	    if ($num1 % $num2 > PHP_INT_MAX)
		    echo "Overflow!\n";
	    else if ($num2 == 0)
	        echo "Don't divide by zero...\n";
	    else
    	    printf("%d\n", $num1 % $num2);
    }
?>
