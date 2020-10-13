#!/usr/bin/php
<?php
require("include.php");

$duties = getTableArray($mysqli, "SELECT * FROM duties");
$roster = getTableArray($mysqli, "SELECT * FROM roster");

print_r($duties);
print_r($roster);

?>
