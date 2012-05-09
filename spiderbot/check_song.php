<?php

include ("../connection.php");

if (isset($_POST['ytcode']))
{
  $ytcode = $_POST['ytcode'];

  
  $qryStr = "SELECT title FROM songs WHERE youtubecode = '$ytcode'";
  $qry = mysql_query($qryStr);

  if (!$qry)
    die("FAIL: " . mysql_error());

  
  if (mysql_num_rows($qry) > 0)
    echo $ytcode; //song is in db
  else
    echo "false"; //new song
}

?>
