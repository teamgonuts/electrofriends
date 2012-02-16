<?php
include ("../connection.php");
include ("../classes/Song.php");
include ("../classes/Rankings.php");
//need to know time filter so I can either sort by upload date or score
if($_POST)
{
    $searchTerm = $_POST['searchTerm'];
    $upperLimit = $_POST['upperLimit'];
    //$searchTerm = 'zedd';

    $qry = mysql_query("SELECT * FROM  `songs`
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

    //checking is no results were returned
    if ($levArr == null)
        echo '<tr><td id="no-result"><h3>Sorry, there are no results matching your criteria. Bummer.</h3></td></tr>';
    else {
        $i = 1;
        foreach ($levArr as $row) {
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

