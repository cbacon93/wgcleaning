#!/usr/bin/php
<?php 
require("include.php");

$answ = readline("Are you sure? [Y/n]");
if ($answ=="y" || $answ=="Y") {

//reset program
$mysqli->query("UPDATE duties set dirt_index=0");
$mysqli->query("TRUNCATE table history");
$mysqli->query("TRUNCATE table roster");

}

?>
