<html>
<body style="background-color:black; color:white;">
 <script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="main.js"></script>

<style type="text/css">
	 a:link {color:#33FF33; text-decoration: underline; }
	 a:visited {color:33ff33; text-decoration: underline; }
	 
	*{margin:0;padding:0;}
	
	.box
	{
		margin:0;
		padding:0;
	}
	
	
	#title
	{
		font-family:Impact;
		font-size:500%;
		color:#FFFF00;
		text-decoration:none;
	}
	
	#sub_title
	{
		font-family:Lucida Console;
		font-size:small;
		color:#00FF00;
		margin-left:5px;
		margin-top:-30px;
		text-decoration:none;
	}
	ol.timeline{list-style:none;font-size:1.2em;}
	ol.timeline li{display:none;position:relative;}
	ol.timeline li:first-child{}
	
	#flash
	{
		margin-left:100px;
	}
	
	.song_min
	{
		
	}
	
	.commentTD
	{
		padding-top: 5px;
		padding-bottom: 5px;
		padding-left: 5px;
		padding-right: 5px;
		vertical-align:text-top;
	}
	
	#name
	{
		color:#000000;
		font-size:14px;
		border:black solid 1px;
		height:24px;
		margin-top:2px;
		margin-bottom:10px;
		width:100px;
		text-align:center;
	}
	
	#submit
	{
		color:#000000;
		margin-top:2px;
		font-size:14px;
		border:#black solid 1px;
		height:24px;
		margin-bottom:10px;
		width:300px;
	}
	
	textarea
	{
		color:#000000;
		font-size:14px;
		border:#666666 solid 2px;
		height:50px;
		width:500px;
	}

	.com_name
	{
		font-size: 12px; 
		color: rgb(102, 51, 153); 
		font-weight: bold;
	}
	
	.com_date
	{
		font-size: 10px;
		color:white;
		font-style:italic;
	}
	
	.com_text
	{
		font-size: 12px; 
		color: white; 
	}
</style>

<?php //HEADER

/*
Calvin Hawkes 2011
*/

include ("connection.php");
include ("classes/DateFilter.php");
include ("classes/GenreFilter.php");
include ("classes/Rankings.php");
include ("classes/Song.php");

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
<b><a href="http://t3kdev.tumblr.com">Dev Team Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b>
<a href="index.php?topof=new&genre='.$genre.'">Freshest</a>&nbsp;&nbsp;
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
	<a href="index.php?topof='.$topof.'&genre=hardstyle">Hardstyle</a>
    <a href="index.php?topof='.$topof.'&genre=house">House</a>
    <a href="index.php?topof='.$topof.'&genre=trance">Trance</a>
<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<a href="upload.php">Upload</a></b>
<br />
<a href="index.php" style="text-decoration:none;"><span id="title">t3k.no</span><span id="sub_title">beta</span></a>
<br /><br />
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
		case 'new':
			return 100000;
    }
}
?>
</body>
</html>