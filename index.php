<html>
<head>
    <Title>T3k.no - Your Electronic Music Connection &nbsp;&nbsp;&nbsp;&nbsp;</Title>
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
include ("classes/Filter.php");
include ("classes/DateFilter.php");
include ("classes/GenreFilter.php");
include ("classes/ArtistFilter.php");
include ("classes/Rankings.php");
include ("classes/Song.php");
include ("misc/functions.php");


?>

<?php
$topof = "new"; //Default
$genre = "all"; //Default
$artist = "none"; //Default

$filters = array(); //empty filters array
//setting filters
if (isset($_GET['topof']))
{
    $topof = $_GET['topof'];

}
if (isset($_GET['genre']))
{
    $genre = $_GET['genre'];

}
if (isset($_GET['artist']))
{
    $artist = $_GET['artist'];
    $filters['artist'] = new ArtistFilter($artist);
}

showHeader(); //show header uses $topOf and $genre

$daysBack = word2num($topof);
$filters['date'] =  new DateFilter($daysBack);
$filters['genre'] = new GenreFilter($genre);
$rankings = new Rankings($filters);
$rankings->display();



?>
</body>
</html>
