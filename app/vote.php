<?php
include('../connection.php');

if(isset($_GET['vote']))
{
  $vote = $_GET['vote']; //-1 or 1
  $ytcode = $_GET['ytcode'];
  $score = $_GET['score']; 
  $uniqueID = $_GET['uid']; //unique id for iphone using mac address 
  $user = $_GET['user'];
  
  $validVote = true; 
    
  //check if the user has voted on this song before
  $qry = mysql_query("SELECT vote FROM  `app_votes` 
                      WHERE unique_id = '$uniqueID', ytcode = '$ytcode'");
  if (!$qry)
      die("FAIL: " . mysql_error());

  if (mysql_num_rows($qry) > 0)//if user has voted
  {
    $row = mysql_fetch_array($qry);
    if($row['vote'] == $vote)//vote is the same
    {
      //dont count the vote 
      $validVote = false;
    }
    else //vote is different
    {
      //update users vote
      $qry = mysql_query("UPDATE songs 
                          SET vote='$vote'
                          WHERE unique_id = '$uniqueID', ytcode = '$ytcode'");
      if (!$qry)
          die("FAIL: " . mysql_error());
    }
  }
  else //user has not voted on this song before 
  {
    
  }
?>

