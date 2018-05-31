<?php
include "auth.php";

session_start();

if (isset($_GET['login']) && isset($_GET['passwd']) && auth($_GET['login'], $_GET['passwd']))
    $_SESSION['loggued_on_user'] = $_GET['login'];
else
    $_SESSION['loggued_on_user'] = "";
echo ($_SESSION['loggued_on_user'] === "" ? "ERROR\n" : "OK\n");

?>