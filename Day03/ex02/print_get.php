<?php
	if (isset($_GET)) {
	  foreach($_GET as $key => $value)
	  	echo $key, ": ", $value, "\n";
	}
?>
