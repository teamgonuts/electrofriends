<?php
include ("../connection.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");
include ("AppSong.php");


if (isset($_GET['timefilter'])) {
  $songsPerPage = 30;

  $topOf = $_GET['timefilter'];
  //$topOf = 'new';
  $genre = $_GET['genrefilter'];
  //$genre = 'all';

  $timeFilter = new DateFilter($topOf);
  $genreFilter = new GenreFilter($genre);

  $where = $timeFilter->genSQL() . ' AND ' . $genreFilter->genSQL();


  if($topOf == 'new'){ //newest was selected, order by upload date
    $qry = mysql_query("SELECT title,artist,youtubecode,score,genre FROM  `songs` 
                        WHERE $where
                        ORDER BY uploaded_on DESC
                        LIMIT 0 , $songsPerPage
                        ");
  }
  else {//order by score
    $qry = mysql_query("SELECT title,artist,youtubecode,score,genre FROM  `songs` 
                        WHERE $where
                        ORDER BY score DESC
                        LIMIT 0 , $songsPerPage
                        ");
  }					
  if (!$qry)
    die("FAIL: " . mysql_error());

  $songs = array();
  while($row = mysql_fetch_array($qry)) {
    $song = new Song($row['title'], $row['artist'], $row['youtubecode'], $row['score'], $row['genre']);
    array_push($songs, $song);
  }

  echo json_encode($songs);
  //printing out each item
  //print_r($songs);

/*  HttpResponse::setCache(true);
  HttpResponse::setContentType('text/html');
  HttpResponse::setData("<html>hellow world...</html>");
  HttpResponse::send(); */
} //end if
?>
