<?php
include('../connection.php');
include ("../classes/Song.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");
include ("../misc/functions.php");

if($_POST)
{
    //need to know time filter so I can either sort by upload date or score
    $topOf = $_POST['timefilter'];
    $genre = $_POST['genrefilter'];
    $artist = $_POST['artistfilter'];
    $lowerLimit = $_POST['lowerLimit'];
    $upperLimit = $_POST['upperLimit'];
    $daysBack = word2num($topOf);
    
    $filters = array();
    $filters['date'] =  new DateFilter($daysBack);
    $filters['genre'] = new GenreFilter($genre);
    if($artist == '')
        $where = $filters['date']->genSQL() . ' AND ' . $filters['genre']->genSQL();
    else
        $where = $filters['date']->genSQL() . ' AND ' . "'$artist' = artist";
    
    if($topOf == 'freshest') //determining to sort by upload date or score
        $orderBy = 'uploaded_on';
    else
        $orderBy = 'score';

    $qry = mysql_query("SELECT * FROM  `songs`
						WHERE $where
						ORDER BY $orderBy DESC
						LIMIT $lowerLimit , $upperLimit
						");
	if (!$qry)
		die("FAIL: " . mysql_error());

    //creating rows
    $i = $lowerLimit + 1; //start one above
    if(mysql_num_rows($qry) > 1) //fixes weird error
    {
       	while($row = mysql_fetch_array($qry) AND $i <= $upperLimit)
        {
            $song = new Song($row, $i);
            echo $song->showClasses();
            $i ++;
        }
    }
}
else {
	die("FAIL: POST not set in ShowMoreSongAjax.php");
}

?>