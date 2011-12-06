<html>
<head>
    <link rel="shortcut icon" href="img/favicon.ico" />
    <Title>T3k.no - Your Electronic Music Connection &nbsp;&nbsp;&nbsp;&nbsp;</Title>
	<script type="text/javascript" src="js/jquery.js">//jquery</script>
	<script type="text/javascript" src="js/main.js">//code by calvin</script>
	<script type="text/javascript" src="js/youtube_player.js">//code by calvin</script>
    <script type="text/javascript" src="js/swfobject.js">//embedding youtube videos</script>
    <script type="text/javascript"> //Google Analytics
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-27461232-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    
	<LINK href="styles/main.css" rel="stylesheet" type="text/css">
	<LINK href="styles/static.css" rel="stylesheet" type="text/css">
	<LINK href="styles/rankings.css" rel="stylesheet" type="text/css">
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
$artist = ""; //Default

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
?>

<span id="rankings-container">
    <?php $rankings->display(); ?>
</span>
</div>

<div class="hidden" id="max-queue">
    <span class="queue-control queue-control-max">[min]</span>
    <div id="user-queue">
        <div class="queue-title">User Queue</div>
        <ol class="max-queue">
            <li class="queue-item" id="user-queue-1"></li>
            <li class="queue-item" id="user-queue-2"></li>
            <li class="queue-item" id="user-queue-3"></li>
            <li class="queue-item" id="user-queue-4"></li>
            <li class="queue-item" id="user-queue-5"></li>
            <li class="queue-item" id="user-queue-6"></li>
            <li class="queue-item" id="user-queue-7"></li>
            <li class="queue-item" id="user-queue-8"></li>
            <li class="queue-item" id="user-queue-9"></li>
            <li class="queue-item" id="user-queue-10"></li>

        </ol>
    </div>
    <div id="generated-queue">
        <div class="queue-title">Generated Queue</div>
        <ol class="max-queue" id="gen-queue">
            <li class="queue-item" id="gen-queue-1"></li>
            <li class="queue-item" id="gen-queue-2"></li>
            <li class="queue-item" id="gen-queue-3"></li>
            <li class="queue-item" id="gen-queue-4"></li>
            <li class="queue-item" id="gen-queue-5"></li>
            <li class="queue-item" id="gen-queue-6"></li>
            <li class="queue-item" id="gen-queue-7"></li>
            <li class="queue-item" id="gen-queue-8"></li>
            <li class="queue-item" id="gen-queue-9"></li>
            <li class="queue-item" id="gen-queue-10"></li>
            <li class="queue-item" id="gen-queue-11"></li>
            <li class="queue-item" id="gen-queue-12"></li>
            <li class="queue-item" id="gen-queue-13"></li>
            <li class="queue-item" id="gen-queue-14"></li>
            <li class="queue-item" id="gen-queue-15"></li>
            <li class="queue-item" id="gen-queue-16"></li>
            <li class="queue-item" id="gen-queue-17"></li>
            <li class="queue-item" id="gen-queue-18"></li>
            <li class="queue-item" id="gen-queue-19"></li>
            <li class="queue-item" id="gen-queue-20"></li>
        </ol>
    </div>
</div>

<div class="bottom-container">
    <table id="song-table" border="1">
        <tr>
            <td id="song-playlist">
                <span class="queue-control queue-control-min">[max]</span>
                <input type="hidden" id="playlist-next-index" value="" />
                <div class="queue-title" style="padding-top: 0px;">Queue</div>
                <ol class="queue" id="min-queue">
                    <li class="queue-item" id="playlist-1"></li>
                    <li class="queue-item" id="playlist-2"></li>
                    <li class="queue-item" id="playlist-3"></li>
                </ol>
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
            <td id="hub-voting-td">
                <span id="song-voting">
                    <div class="vote-button" id="up">+</div>
                    <div id="song-score"></div>
                    <div class="vote-button" id="down">-</div>
                </span>
            </td>
            <td id="song-info">
                <p style="text-align: center; font-weight: bold;">Current Song</p>
                <table id="song-info-table">
                    <tr>
                        <td colspan="2" id="song-info-titleartist">
                            <span id="song-title"></span> - <a  href="#" class="filter artist-link" id="song-artist"></a>
                        </td>
                    </tr>
                    <tr>
                            <td>Genre: <a class="genre-link" id="hub-genre-link" href="#"><span id="song-genre"></span></a></td>
                            <td class="song-info-item">Uploaded By: <a href="#" class="filter user-link" id="song-user"></a></td>
                    </tr>
                    <tr>
                        <td colspan="2">Download: <a href="#"><span id="song-download"></span></a></td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
</div>
<span id="resizer" style="visibility: hidden;"></span>
</body>
</html>
