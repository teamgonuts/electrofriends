
<?php

include ("../connection.php");
if($_POST) {
    $ytcode = $_POST['ytcode'];

    $qry = mysql_query('UPDATE songs SET plays = plays + 1 WHERE youtubecode="'. $ytcode . '"');

    if (!$qry)
                die("FAIL: " . mysql_error());

}
else {
	die("FAIL: POST not set in ShowMoreSongAjax.php");
}
?>
