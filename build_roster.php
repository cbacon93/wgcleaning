#!/usr/bin/php
<?php 
require("include.php");



// GET USER AND DUTY TABLE
$user = getTableArray($mysqli, "SELECT * FROM user");
$duties = getTableArray($mysqli, "SELECT * FROM duties ORDER BY (dirt_index / max_dirt_index) DESC");
$uidlist = array();

//user history
for ($u=0; $u < sizeof($user); $u++)
{
  $id = $user[$u]['id'];
  $uidlist[] = $id;
  $user[$u]['history'] = getTableArray($mysqli, "SELECT * FROM history WHERE user_id=$id ORDER BY `time` DESC");
}




$weeknum = date("W")+1;

for($i=$weeknum; $i <= $weeknum+2; $i++)
{
  $mysqli->query("DELETE FROM roster WHERE week=$i");
  
  //start week
  echo "Building Roster Week $i\n";
  
  //sort duties
  sortDutyTable($duties);
  
  //to do duties
  $todo = array();
  for ($j=0; $j < sizeof($user); $j++)
  {
    $todo[] = $duties[$j]['id'];
  }
  
  
  //get combinations
  $combinations = getDutyCombination($uidlist, $todo);
  $dcpoints = array();
  for ($x=0; $x < sizeof($combinations); $x++)
  {
    $sum = 0;
    for ($y=0; $y < sizeof($combinations[$x]); $y++)
    {
      $s = explode("=>", $combinations[$x][$y]);
      $u = getElementById($user, $s[0]);
      $sum += getHistoryPoints($u['history'], $s[1]);
    }
    $dcpoints[$x] = $sum;
  }
  
  //get best combi
  $best = 0;
  $bestp = INF;
  for($x=0; $x < sizeof($dcpoints); $x++)
  {
    if ($dcpoints[$x] < $bestp)
    {
      $best = $x;
      $bestp = $dcpoints[$x];
    }
  }
  
  
  //do duties
  $assignment = $combinations[$best];

  for ($x=0; $x < sizeof($assignment); $x++)
  {
    $s = explode("=>", $assignment[$x]);
    $uid = $s[0];
    $did = $s[1];
    $u = getElementById($user, $uid);
    $d = getElementById($duties, $did);
    
    echo $u['name'] . " => " . $d['name'] . "\n";
    
    $mysqli->query("INSERT INTO roster SET user_id=$uid, duty_id=$did, week=$i");
  }
  
  echo "\n";
  
  
  //for next iteration pretend every user has finished job
  //clean rooms
  for ($x=0; $x < sizeof($assignment); $x++)
  {
    $s = explode("=>", $assignment[$x]);
    $did = $s[1];
    $uid = $s[0];
    $k = getElementKeyById($duties, $did);
    $duties[$k]['dirt_index'] = 0;
    
    //insert history
    $k = getElementKeyById($user, $uid);
    $history = array('user_id'=>$uid, 'duty_id'=>$did, 'time'=>time());
    array_unshift($user[$k]['history'], $history);
  }
  
  //dirty rooms
  for ($j=0; $j < sizeof($duties); $j++)
  {
    $duties[$j]['dirt_index'] += 7;
  }
  
  
}
?>