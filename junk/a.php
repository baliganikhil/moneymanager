<?php


session_name("kirit");
session_start();

echo session_id() . "<br>";

$_SESSION['name'] = "Nikhil";
echo $_SESSION['name'];


?>
