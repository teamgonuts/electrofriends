<?php
include ("../connection.php");
include ("../classes/Song.php");
include ("../classes/Rankings.php");
//need to know time filter so I can either sort by upload date or score
if($_POST)
{
    $searchTerm = $_POST['searchTerm'];

    $qry = mysql_query("SELECT * FROM  `songs` ");
    if (!$qry)
                die("FAIL: " . mysql_error());

    $i =0;
    $qryArr = array(); //array of song results
    $levArr = array(); //array of levenshtein numbers

    //determining closest matches
    while($row = mysql_fetch_array($qry)) 
    {
        //if the search term is contained in the title or artist
        if (stristr($row['artist'], $searchTerm) != false)
        {
            $levArr[$i] = levenshtein($searchTerm, $row['artist']);
            $qryArr[$i] = $row; 
        }
        else if (stristr($row['title'], $searchTerm) != false)
        {
            $levArr[$i] = levenshtein($searchTerm, $row['title']);
            $qryArr[$i] = $row; 
        }

        $i++;
    }
    //checking is no results were returned
    if ($levArr == null)
        echo '<tr><td><h3>Sorry, there are no results matching your criteria. Bummer dude.</h3></td></tr>';
    else
    {
        asort($levArr);

        $i = 1;
        foreach (array_keys($levArr) as $key)
        {
            $song = new Song($qryArr[$key], $i);
            echo $song->showClasses();
            $i ++;
        }
    }
}
else {
	die("FAIL: POST not set in ShowMoreSongAjax.php");
}
?>

