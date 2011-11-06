<html>
<body style="background-color:black; color:white;">
<style type="text/css">

 a:link {color:#33FF33; text-decoration: underline; }
 a:visited {color:33ff33; text-decoration: underline; }

 </style>
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

$html = '<center>
<b>Top of the:</b>
    <a href="index.php?topof=day&genre='.$genre.'">'.format("Day").'</a>
    <a href="index.php?topof=week&genre='.$genre.'">'.format("Week").'</a>
    <a href="index.php?topof=month&genre='.$genre.'">'.format("Month").'</a>
    <a href="index.php?topof=year&genre='.$genre.'">'.format("Year").'</a>
    <a href="index.php?topof=century&genre='.$genre.'">'.format("Century").'</a>
<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Genre:</b>
    <a href="index.php?topof='.$topof.'&genre=all">All</a>
    <a href="index.php?topof='.$topof.'&genre=dnb">DnB</a>
    <a href="index.php?topof='.$topof.'&genre=dubstep">Dubstep</a>
    <a href="index.php?topof='.$topof.'&genre=electro">Electro</a>
    <a href="index.php?topof='.$topof.'&genre=house">House</a>
    <a href="index.php?topof='.$topof.'&genre=trance">Trance</a>
<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<a href="upload.php">Upload</a></b>
<br />
<br />
</center>';

echo $html;

$daysBack = word2num($topof);
$datefilter = new DateFilter($daysBack);

$genrefilter = new GenreFilter($genre);


$rankings = new Rankings($datefilter, $genrefilter);
$rankings->display();



?>

<?php //============================FUNCTIONS====================//


//highlights the correct genre and topof
//need to implement
function format($in)
{

    return $in;
/*
    global $genre;
    global $topof;
    
    switch($topof)
    {
        case "day":
            return '<div style="a:link{color:white; text-decoration: underline; }">Day</div>';
            break;
        case "week":
            return '<div style="">Week</div>';
            break;
        case "month":
            return '<div style="">Month</div>';
            break;
        case "year":
            return '<div style="">Year</div>';
            break;
        case "century":
            return '<div style="">Century</div>';
            break;
        default:
            return $in;
            break;
    }
*/
        
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
</body>
</html>