<?php
//edit this line
$mysqli = new mysqli("localhost", "wg_cleaning", "", "");


function getTableArray($mysqli, $query)
{
  $res = $mysqli->query($query);
  $array = array();
  while($ass = $res->fetch_assoc())
  {
    $array[] = $ass;
  }
  return $array;
}


function getHistoryPoints($history, $duty)
{
  $points = 0;
  if (sizeof($history) == 0) return 0;
  
  //last event
  if ($history[0]['duty_id'] == $duty)
  {
    $points += 5;
  }
  
  foreach($history as $h)
  {
    if ($h['duty_id'] == $duty)
    {
      $points += 1;
    }
  }
  
  return $points;
}


function getElementById($elements, $id)
{
  foreach($elements as $e)
  {
    if ($e['id'] == $id)
      return $e;
  }
}

function getElementKeyById($elements, $id)
{
  foreach($elements as $k=>$e)
  {
    if ($e['id'] == $id)
      return $k;
  }
}


function getDutyCombination($user, $duties)
{
  if (sizeof($user) == 0 || sizeof($duties) == 0)
    return array();
  
  $pickuser = $user[0];
  array_splice($user, 0, 1);
  
  $array = array();
  for($d=0; $d < sizeof($duties); $d++) {

    $tmp = array($pickuser . "=>" . $duties[$d]);
    $duties_c = $duties;
    array_splice($duties_c, $d, 1);
    
    $tmp2 = getDutyCombination($user, $duties_c);
    
    if (sizeof($tmp2) == 0)
    {
      $array[] = $tmp;
    }
    else if (sizeof($tmp2) == 1)
    {
      $tmp2 = $tmp2[0];
      $array[] = array_merge($tmp, $tmp2);
    } else 
    {
      foreach($tmp2 as $t)
      {
        $array[] = array_merge($tmp, $t);
      }
    }
    
    
  }
  
  return $array;
}

//bubble sort duties
function sortDutyTable(&$duties)
{
  for ($j=0; $j < sizeof($duties); $j++)
  {
    for ($i=0; $i < sizeof($duties)-1; $i++)
    {
      $v1 = $duties[$i]['dirt_index'] / $duties[$i]['max_dirt_index'];
      $v2 = $duties[$i+1]['dirt_index'] / $duties[$i+1]['max_dirt_index'];
      
      if ($v2 > $v1)
      {
        $tmp = $duties[$i];
        $duties[$i] = $duties[$i+1];
        $duties[$i+1] = $tmp;
      }
    }
  }
}


 ?>
