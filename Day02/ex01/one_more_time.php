#!/usr/bin/php
<?php

    function get_month($string)
    {
        $s = strtolower($string);
        if ($s == "janvier")
            return "January";
        else if ($s == "fevrier")
            return "February";
        else if ($s == "mars")
            return "March";
        else if ($s == "avril")
            return "April";
        else if ($s == "mai")
            return "May";
        else if ($s == "juin")
            return "June";
        else if ($s == "juillet")
            return "July";
        else if ($s == "aout")
            return "August";
        else if ($s == "septembre")
            return "September";
        else if ($s == "octobre")
            return "October";
        else if ($s == "novembre")
            return "November";
        else
            return "December";
    }
    
    if ($argc > 1) {
        if (preg_match('/([Ll]undi|[Mm]ardi|[Mm]ercredi|[Jj]eudi|[Vv]endredi|[Ss]amedi|[Dd]imanche]) ([0-9]{1,2}) ([Jj]anvier|[Ff]evrier|[Mm]ars|[Aa]vril|[Mm]ai|[Jj]uin|[Jj]uillet|[Aa]out|[Ss]eptembre|[Oo]ctobre|[Nn]ovembre|[Dd]ecembre) ([0-9]{4}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/', $argv[1]) === 0) {
			echo "Wrong Format\n";
    		return;
	    }
		$array = explode(' ', $argv[1], PHP_INT_MAX);
		date_default_timezone_set("Europe/Paris");
        $string = sprintf("%d %s %d %s", intval($array[1]), get_month($array[2]), $array[3], $array[4]);
        if ($string !== date("j F Y H:i:s", ($epoch = strtotime($string)))) {
            echo "Date is invalid!\n";
            return;
        }
        echo $epoch, "\n";
    }
?>