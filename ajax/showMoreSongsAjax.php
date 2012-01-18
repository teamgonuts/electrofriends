<?php
include ("../connection.php");
include ("../classes/Song.php");
include ("../classes/Rankings.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");

if($_POST)
{
    //need to know time filter so I can either sort by upload date or score
    $topOf = $_POST['timefilter'];
    $genre = $_POST['genrefilter'];
    $lowerLimit = $_POST['lowerLimit'];
    $upperLimit = $_POST['upperLimit'];
    $filters = array("date" => new DateFilter($topOf), "genre" => new GenreFilter($genre));

    $where = $filters['date']->genSQL() . ' AND ' . $filters['genre']->genSQL();
    

    $qry = mysql_query("SELECT * FROM  `songs`
						WHERE $where
						ORDER BY 'score' DESC
						LIMIT $lowerLimit , $upperLimit
						");
    if (!$qry)
		die("FAIL: " . mysql_error());

    //creating rows
    $i = $lowerLimit + 1; //start one above
    while($row = mysql_fetch_array($qry) AND $i <= $upperLimit)
    {
        $song = new Song($row, $i);
        echo $song->showClasses();
        $i ++;
    }
}
else {
	die("FAIL: POST not set in ShowMoreSongAjax.php");
}

?>
