<?php
include ("connection.php");
include ("classes/Song.php");
include ("misc/functions.php");

showHeader();
?>

<html>
<head>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="main.js"></script>
	<script src="//platform.twitter.com/widgets.js" type="text/javascript">
	//how to dynamically load http://www.ovaistariq.net/447/how-to-dynamically-create-twitters-tweet-button-and-facebooks-like-button/
	</script>
	<LINK href="styles/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>
<?php
	if (isset($_GET['s']))
		$ytcode = $_GET['s'];
	else
		die("No Song Specified...how did you get here");
		
	//get info by ytcode
	$qry = mysql_query("SELECT * FROM songs WHERE youtubecode='$ytcode'");
	if (!$qry) 
		die("FAIL: " . mysql_error());
	
	$row = mysql_fetch_array($qry);
    $song = new Song($row, '69'); //69 just cuz
?>
	<table border="1">
		<tr>
			<td>
				<iframe title="YouTube video player" class="youtube-player" type="text/html" 
				width="560" height="315" src="http://www.youtube.com/embed/<?php echo $ytcode ?>"
				frameborder="0" allowFullScreen></iframe>
			</td>
		</tr>
		<tr>
			<td>
				<?php $song->showView(); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php $song->showIComments('100'); ?>
			</td>
		</tr>
	<table>
</center>
</body>
</html>