<?php
include('../connection.php');
include ("../classes/Song.php");

if($_POST)
{	
	$where = $_POST['where'];
	$songsPerPage = $_POST['songsPerPage'];
	$upperLimit = $_POST['upperLimit'];
	
	//updating limits
	$i = $upperLimit + 1;
	$lowerLimit = $upperLimit;
	$upperLimit = $upperLimit + $songsPerPage;
	
	
	if(strpos($where, 'New') != false) //newest was selected
	{
		$qry = mysql_query("SELECT * FROM  `songs` 
						WHERE $where
						ORDER BY uploaded_on DESC
						LIMIT $lowerLimit , $upperLimit
						");
	}
	else
	{
		$qry = mysql_query("SELECT * FROM  `songs` 
						WHERE $where
						ORDER BY score DESC
						LIMIT $lowerLimit , $upperLimit
						");
	}					
	if (!$qry)
		die("FAIL: " . mysql_error());

	
	while($row = mysql_fetch_array($qry) AND $i <= $upperLimit)
	{
		$song = new Song($row, $i);
		echo '<tr class="song" id="'.$i.'">';
		$song->showMin();
		echo '</tr>';
		$i ++;
	}
}
else { die("FAIL: POST not set in maxSongAjax.php"); }
?>