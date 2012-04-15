<?php
include ("../connection.php");
include ("AppSong.php");


//need to know time filter so I can either sort by upload date or score
if (isset($_GET['searchTerm'])) {
  
    $searchTerm = $_GET['searchTerm'];
    $upperLimit = 30;

    $qry = mysql_query("SELECT title,artist,youtubecode,score,genre FROM  `songs`
                        ORDER BY `uploaded_on` DESC ");
    if (!$qry)
                die("FAIL: " . mysql_error());

    $levArr = array(); //array of levenshtein numbers

    //determining closest matches
    while($row = mysql_fetch_array($qry)) {
        //if the search term is contained in the title or artist
        if (stristr($row['artist'], $searchTerm) != false ||
            stristr($row['title'], $searchTerm) != false ) 
        {
            $levArr[] = $row;
            if (count($levArr) >= $upperLimit)
                break;
        }
    }

    $songs = array();
    //checking is no results were returned
    if ($levArr != null) {
      foreach ($levArr as $row) {
        $song = new Song($row['title'], $row['artist'], $row['youtubecode'], $row['score'], $row['genre']);
        array_push($songs, $song);
      }
    }

  echo json_encode($songs);
}
