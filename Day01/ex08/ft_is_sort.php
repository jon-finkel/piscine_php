<?php
	function ft_is_sort($array)
	{
		$array_sort = $array;
		sort($array_sort, COUNT_NORMAL);
		$size = count($array);
		for ($k = 0; $k < $size; ++$k)
			if ($array[$k] !== $array_sort[$k])
				return false;
		return true;
	}
?>
