<?php //HEADER

/*
Calvin Hawkes 2011
*/

include ("connection.php");
include ("/classes/DateFilter.php");
include ("/classes/GenreFilter.php");
include ("/classes/Rankings.php");
include ("/classes/Song.php");

if(isset($_POST['vote']))
{
    $vote = $_POST['vote'];
    $ytcode = $_POST['ytcode'];
    $qry = mysql_query("SELECT * FROM  `songs` WHERE youtubecode='".$ytcode."'");
    if (!$qry)
        die("FAIL: " . mysql_error());
    
    //======================== UPDATING SONG'S SCORE =================//
    $song = mysql_fetch_array($qry);
    
    $new_score = $song['score'] + $vote;
    $new_ups = $song['ups'];
    $new_downs = $song['downs'];
    if($vote == 1)
        $new_ups ++;
    else
        $new_downs ++;
    
    $qry = "UPDATE songs SET score=$new_score , ups=$new_ups, downs=$new_downs WHERE youtubecode='". $ytcode."'";
			$qry = mysql_query($qry);
			if (!$qry)
				die("FAIL: " . mysql_error());
}
?>

<?php
$topof = "month"; //Default
$genre = "all"; //Default


if (isset($_GET['topof']))
    $topof = $_GET['topof'];

if (isset($_GET['genre']))
    $genre = $_GET['genre'];

$html = '
<b><u>Filters</u></b>
<br />
<b>Top of the:</b>
    <a href="index.php?topof=day&genre='.$genre.'"> Day </a>
    <a href="index.php?topof=week&genre='.$genre.'">Week </a>
    <a href="index.php?topof=month&genre='.$genre.'">Month </a>
    <a href="index.php?topof=year&genre='.$genre.'">Year </a>
    <a href="index.php?topof=century&genre='.$genre.'">Century </a>
<br />
<b>Genre:</b>
    <a href="index.php?topof='.$topof.'&genre=all"> All </a>
    <a href="index.php?topof='.$topof.'&genre=dnb">DnB </a>
    <a href="index.php?topof='.$topof.'&genre=dubstep">Dubstep </a>
    <a href="index.php?topof='.$topof.'&genre=electro">Electro </a>
    <a href="index.php?topof='.$topof.'&genre=house">House </a>
    <a href="index.php?topof='.$topof.'&genre=trance">Trance</a>
<br />
<b><a href="upload.php">Upload</a></b>
<br />
<br />
<center>
<b>The Best '. spitGenre($genre) . ' of the ' . ucfirst($topof) . '</b>
</center>
';

echo $html;

$daysBack = word2num($topof);
$datefilter = new DateFilter($daysBack);

$genrefilter = new GenreFilter($genre);


$rankings = new Rankings($datefilter, $genrefilter);
$rankings->display();



?>

<?php //============================FUNCTIONS====================//
//returns correct formatted genre
function spitGenre($in)
{
    if($in== 'all')
        $in = 'shit';
    if($in == 'dnb')
        $in = 'DnB';
    
    return ucfirst($in);
}

//coverts dateFilter word into coresponding days
function word2num($topof)
{
    switch($topof)
    {
        case 'day':
            return 1;
            break;
        case 'week':
            return 7;
            break;
        case 'month':
            return 30;
            break;
        case 'year':
            return 369;
            break;
        case 'century':
            return 36500;
            break;
    }
}
?>
