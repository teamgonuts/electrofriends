<html>
<head>
    <Title>T3k.no - The Electronic Music Connection</Title>
	<script type="text/javascript" src="jquery.js">//jquery</script>
	<script type="text/javascript" src="main.js">//code by calvin</script>
    <script type="text/javascript" src="swfobject.js">//embedding youtube videos</script>
	<script src="//platform.twitter.com/widgets.js" type="text/javascript">
	//how to dynamically load http://www.ovaistariq.net/447/how-to-dynamically-create-twitters-tweet-button-and-facebooks-like-button/
	</script>

	<LINK href="styles/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php //HEADER

/*
Calvin Hawkes 2011
*/

include ("connection.php");
include ("classes/DateFilter.php");
include ("classes/GenreFilter.php");
include ("classes/Rankings.php");
include ("classes/Song.php");
include ("misc/functions.php");


?>

<?php

showHeader();

$daysBack = word2num($topof);
$datefilter = new DateFilter($daysBack);

$genrefilter = new GenreFilter($genre);


$rankings = new Rankings($datefilter, $genrefilter);
$rankings->display();



?>
</body>
</html>
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
