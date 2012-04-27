
<?php
include('../connection.php');

if(isset($_GET['vote']))
{
  $vote = (int)$_GET['vote']; //-1 or 1
  $ytcode = $_GET['ytcode'];
  $uniqueID = $_GET['uid']; //unique id for iphone using mac address 
  $user = $_GET['user'];
  
  $validVote = true; 
  $changedVote = false; 
    
  //check if the user has voted on this song before
  $qry = mysql_query("SELECT vote FROM  `app_votes` 
                      WHERE unique_id = '$uniqueID' and ytcode = '$ytcode'");
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
      $qry = mysql_query("UPDATE `app_votes` 
                          SET vote=$vote
                          WHERE unique_id = '$uniqueID' and ytcode = '$ytcode'");
      if (!$qry)
          die("FAIL: " . mysql_error());

      $changedVote = true;
    }
  }
  else //user has not voted on this song before 
  {
    
    //create vote record
    $qry = mysql_query("INSERT INTO `app_votes` (ytcode, unique_id, vote)
                        VALUES ('$ytcode', '$uniqueID', $vote)");
    if (!$qry)
      die("FAIL: " . mysql_error());
  }


  if($validVote)
  {
    //updating song's/users score
    if($changedVote)
    {
      if ($vote == 1) //upVote
      {
        $qry = "UPDATE songs SET score=score+2 , ups=ups+1, downs=downs-1
                WHERE youtubecode='$ytcode'";
        $userqry = "UPDATE users SET points=points+2 WHERE user='$user'";
      }
      else //downVote
      {
        $qry = "UPDATE songs SET score=score-2 , downs=downs+1, ups=ups-1
                WHERE youtubecode='$ytcode'";
        $userqry = "UPDATE users SET points=points-2 WHERE user='$user'";
      }
    }
    else //user didn't change vote
    {
      if ($vote == 1) //upVote
      {
        $qry = "UPDATE songs SET score=score+1 , ups=ups+1
                WHERE youtubecode='$ytcode'";
        $userqry = "UPDATE users SET points=points+1 WHERE user='$user'";
      }
      else //downVote
      {
        $qry = "UPDATE songs SET score=score-1 , downs=downs+1
                WHERE youtubecode='$ytcode'";
        $userqry = "UPDATE users SET points=points-1 WHERE user='$user'";
      }
    }
    $qry = mysql_query($qry);
    if (!$qry)
      die("FAIL: " . mysql_error());

    $userqry = mysql_query($userqry);
    if (!$userqry)
        die("FAIL: " . mysql_error());
  }
 
  //returning the result
  //true = should update the score in SongCell/ExpandedCell else don't update
  if ($validVote)
    echo "true";
  else
    echo "false";
}


?>
