<?php
    include ("connection.php");
    include ("classes/Filter.php");
    include ("classes/DateFilter.php");
    include ("classes/GenreFilter.php");
    include ("classes/ArtistFilter.php");
    include ("classes/Rankings.php");
    include ("classes/Song.php");
    include ("misc/functions.php");


    $filters = array();
    $daysBack = word2num($topof);
    $filters['date'] =  new DateFilter($daysBack);
    $filters['genre'] = new GenreFilter($genre);
    $rankings = new Rankings($filters);
    $rankings->display();
?>
