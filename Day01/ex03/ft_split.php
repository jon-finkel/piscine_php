<?php
    function ft_split($string)
    {
        $array = explode(" ", $string, PHP_INT_MAX);
        $max = count($array);
        for ($k = 0; $k < $max; ++$k)
            if ($array[$k] == NULL)
                unset($array[$k]);
        sort($array, SORT_STRING);
        return $array;
    }
?>
