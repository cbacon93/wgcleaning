#!/usr/bin/php
<?php 
require("include.php");

$mysqli->query("UPDATE duties SET dirt_index=dirt_index+1");

?>
