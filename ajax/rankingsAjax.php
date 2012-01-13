<?php
include ("../connection.php");
include ("../classes/Song.php");
include ("../classes/Rankings.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");

$topOf = $_POST['timefilter'];
$genre = $_POST['genrefilter'];
$filters = array("date" => new DateFilter($topOf), "genre" => new GenreFilter($genre));
$rankings = new Rankings($filters);
$rankings->display();
?>
