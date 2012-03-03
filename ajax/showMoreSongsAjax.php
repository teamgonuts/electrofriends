<?php
include ("../connection.php");
include ("../classes/Song.php");
include ("../classes/Rankings.php");
include ("../classes/Filter.php");
include ("../classes/DateFilter.php");
include ("../classes/GenreFilter.php");

if($_POST)
{

    $lowerLimit = $_POST['lowerLimit'];
    $upperLimit = $_POST['upperLimit'];
    $flag = $_POST['flag'];

    if ($flag == 'normal') //filter by genre/time
    {
        //need to know time filter so I can either sort by upload date or score
        $topOf = $_POST['timefilter'];
        $genre = $_POST['genrefilter'];
        $filters = array("date" => new DateFilter($topOf), "genre" => new GenreFilter($genre));
        $where = $filters['date']->genSQL() . ' AND ' . $filters['genre']->genSQL();

        if($topOf == 'new') //newest was selected, order by upload date
        {
                $qry = mysql_query("SELECT * FROM  `songs` 
                    WHERE $where
                    ORDER BY uploaded_on DESC
                    LIMIT $lowerLimit , $upperLimit
                    ");
        }
        else //order by score
        {
                $qry = mysql_query("SELECT * FROM  `songs` 
                    WHERE $where
                    ORDER BY score DESC
                    LIMIT $lowerLimit , $upperLimit
                    ");
        }					
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
    else if ($flag == 'search') //show more search results
    {
        $searchTerm = $_POST['searchTerm'];
        $qry = mysql_query("SELECT * FROM  `songs`
                            ORDER BY `uploaded_on` DESC ");
        if (!$qry)
		die("FAIL: " . mysql_error());

        $songArr = array(); 
        //determining closest matches
        while($row = mysql_fetch_array($qry)) 
        {
            //if the search term is contained in the title or artist
            if (stristr($row['artist'], $searchTerm) != false ||
                stristr($row['title'], $searchTerm) != false ) 
            {
                $songArr[] = $row;
            }
        }

        $i = $lowerLimit + 1; //start one above
        while ($songArr[$i] != null && $i <= $upperLimit) {
            $song = new Song($songArr[$i], $i);
            echo $song->showClasses();
            $i ++;
        }
    }
    else
        die("FAIL: Invalid Flag - " . $flag);
    
}
else {
	die("FAIL: POST not set in ShowMoreSongAjax.php");
}

?>
