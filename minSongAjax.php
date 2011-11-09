<?php
include('connection.php');
include ("classes/Song.php");

if($_POST)
{	
	$title = $_POST['title'];
	$artist = $_POST['artist'];
	$genre = $_POST['genre'];
	$ytcode = $_POST['ytcode'];
	$user = $_POST['user'];
	$ups = $_POST['ups'];
	$downs = $_POST['downs'];
	$score = $_POST['score'];
	$i = $_POST['i'];
	$id = $_POST['id'];
	$upload_date = $_POST['upload_date'];
	
	$song_max = Song::Song_Info($title, $artist, $genre, $ytcode, $user, $score, $ups, $downs, $id, $upload_date, $i);
	$song_max->showMinGuts();
}
else { die("FAIL: POST not set in maxSongAjax.php"); }
?>
