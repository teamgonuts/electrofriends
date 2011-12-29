<?php
include ("../connection.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");
include ("../classes/ArtistFilter.php");
include ("../classes/Rankings.php");
include ("../classes/Song.php");
include ("../misc/functions.php");

    $topOf = $_POST['timefilter'];
    $genre = $_POST['genrefilter'];
    $artist = fixjQueryPost($_POST['artistfilter']);
    $daysBack = word2num($topOf);

    $filters = array();
    $filters['date'] =  new DateFilter($daysBack);
    $filters['genre'] = new GenreFilter($genre);
    if($artist != '')
        $filters['artist'] = new ArtistFilter($artist);

    $rankings = new Rankings($filters);

    $rankings->display();
?>
