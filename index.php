<?php

/*===========THE PLAN===========*/
/*
1. Grab top 50 pix based off rankNumber
2. Write <table> html
3. Iterate through rows:
	-Create RankableItem classes based off data
	-Call genHTML() of RankableItems to display in <tr><td>
4. Write </table>

On UP or DOWN Click
1. Calculate RankableItem's new rankNumber based off new win
2. Update DB with new rankNumber
*/

include ("connection.php");
include ("/classes/DateFilter.php");
include ("/classes/GenreFilter.php");
include ("/classes/Rankings.php");
include ("/classes/Song.php");

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
