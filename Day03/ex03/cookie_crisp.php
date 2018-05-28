<?php

if ($_GET && $_GET['action']) {
  if ($_GET['action'] === "set" && $_GET['name'])
    setcookie($_GET['name'], $_GET['value'], time() + 3600);
  else if ($_GET['action'] === "get" && $_GET['name'] && $_COOKIE[$_GET['name']])
	  echo $_COOKIE[$_GET['name']], "\n";
  else if ($_GET['action'] === "del" && $_GET['name'])
    setcookie($_GET['name'], null, -1);
}

?>
