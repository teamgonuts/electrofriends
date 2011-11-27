<html>
<head>
    <Title>T3k.no - Your Electronic Music Connection &nbsp;&nbsp;&nbsp;&nbsp;</Title>
	<script type="text/javascript" src="js/jquery.js">//jquery</script>
	<script type="text/javascript" src="js/main.js">//code by calvin</script>
	<script type="text/javascript" src="js/youtube_player.js">//code by calvin</script>
    <script type="text/javascript" src="js/swfobject.js">//embedding youtube videos</script>
	<script src="//platform.twitter.com/widgets.js" type="text/javascript">
	//how to dynamically load http://www.ovaistariq.net/447/how-to-dynamically-create-twitters-tweet-button-and-facebooks-like-button/
	</script>
    
	<LINK href="styles/main.css" rel="stylesheet" type="text/css">
	<LINK href="styles/static.css" rel="stylesheet" type="text/css">
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
?>
<div class="filters">
    <?php showFilters(); //show header uses $topOf and $genre ?>
</div>

<div class="main">
<?php
    showLogo();
    $daysBack = word2num($topof);
    $filters['date'] =  new DateFilter($daysBack);
    $filters['genre'] = new GenreFilter($genre);
    $rankings = new Rankings($filters);
    $rankings->display();
?>
</div>

<div class="bottom-container">
    <table id="song-table" border="1">
        <tr>
            <td id="song-info">
                <p style="text-align: center; font-weight: bold;">Current Song</p>
                <table id="song-info-table">
                    <tr>
                        <td colspan="2" id="song-info-titleartist">
                            <a href="#"><span id="song-title"></span></a> - <a href="#"><span id="song-artist"></span></a>
                        </td>
                    </tr>
                    <tr>
                            <td>Genre: <a href="#"><span id="song-genre"></span></a></td>
                            <td class="song-info-item">Uploaded By: <a href="#"><span id="song-user"></span></a></td>
                    </tr>
                    <tr>
                        <td colspan="2">Download: <a href="#"><span id="song-download"></span></a></td>
                    </tr>
                </table>
            </td>
            <td id="song-swf">
                <span class="track-button" id="prev-track"><<</span>
                <span id="ytp">
                    <p>You will need Flash 8 or better to view this content. Download it
                            <a href="http://get.adobe.com/flashplayer/">HERE</a>
                    </p>
                </span>
                <span class="track-button" id="next-track">>></span>
            </td>
            <td id="song-voting"></td>
            <td id="song-playlist">
                
                <input type="hidden" id="playlist-next-index" value="" />
                <p style="text-align: center; font-weight: bold;">Queue</p>
                <ol id="song-queue">
                    <li id="playlist-1"></li>
                    <li id="playlist-2"></li>
                    <li id="playlist-3"></li>
                </ol>
            </td>
        </tr>
    </table>
</div>
<span id="resizer" style="visibility: hidden;"></span>
</body>
</html>
