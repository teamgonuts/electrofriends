<?php
include('../connection.php');
include ("../classes/Song.php");

if($_POST)
{	
	$where = str_replace('\\','',$_POST['where']);
	$commentsShown = $_POST['commentsShown'];
	$upperLimit = $_POST['upperLimit'];
	
	//updating limits
	$i = $upperLimit + 1;
	$lowerLimit = $upperLimit;
	$upperLimit = $upperLimit + $commentsShown;
	
	

	$qry = mysql_query("SELECT * FROM  `comments` 
                        WHERE $where
						ORDER BY upload_date DESC
						LIMIT $lowerLimit , $upperLimit
						");				
	if (!$qry)
		die("FAIL: " . mysql_error());

	
	while($row = mysql_fetch_array($qry) AND $i <= $upperLimit)
	{
		$com_user=$row['com_user'];
		$com_user = str_replace('\\','',$com_user);
		
		$comment_dis=$row['com_dis'];
		$comment_dis = str_replace('\\', '', $comment_dis);
		$date_t = $row['upload_date'];
		$date = new DateTime($date_t);
		echo '<li style="display: list-item;" class="box"><span class="com_name"> '.$com_user.'</span>:
		<span class="com_text"> ' . $comment_dis . '</span>
		<span class="com_date"> ' . $date->format('M. j, Y G:i:s') . '</span></li>';
		$i ++;
	}
}
else { die("FAIL: POST not set in maxSongAjax.php"); }
?>