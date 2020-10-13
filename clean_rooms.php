#!/usr/bin/php
<?php 
require("include.php");

if (!array_key_exists(1, $argv))
{
  exit("Please specify the week\nCurrent week nr. is: " . date("W") . "\n");
}

$weeknum = $argv[1];

$week = getTableArray($mysqli, "SELECT * FROM roster WHERE week=$weeknum");
$user = getTableArray($mysqli, "SELECT * FROM user");
$duties = getTableArray($mysqli, "SELECT * FROM duties");

for ($i=0; $i < sizeof($week); $i++)
{
  $uid = $week[$i]['user_id'];
  $did = $week[$i]['duty_id'];
  $u = getElementById($user, $uid);
  $d = getElementById($duties, $did);
  
  $answ = readline ("Did $u[name] do $d[name]? [Y/n] ");
  
  if ($answ == "y" || $answ == "Y")
  {
    $mysqli->query("UPDATE duties SET dirt_index=0 WHERE id=$did");
    $mysqli->query("INSERT INTO history SET user_id=$uid, duty_id=$did, `time`=" . time());
  }
}

?>
