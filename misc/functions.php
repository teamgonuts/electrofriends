<?php

function showHeader()
{
	global $topof;
	$topof = "month"; //Default
	global $genre;
	$genre = "all"; //Default


	if (isset($_GET['topof']))
		$topof = $_GET['topof'];

	if (isset($_GET['genre']))
		$genre = $_GET['genre'];

	$html = '<center>
	<b><a href="http://t3kdev.tumblr.com">Dev Team Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b>
	<a href="index.php?topof=new&genre='.$genre.'">Freshest</a>&nbsp;&nbsp;
	<b>Top of the:</b>
		<a href="index.php?topof=day&genre='.$genre.'">Day</a>
		<a href="index.php?topof=week&genre='.$genre.'">Week</a>
		<a href="index.php?topof=month&genre='.$genre.'">Month</a>
		<a href="index.php?topof=year&genre='.$genre.'">Year</a>
		<a href="index.php?topof=century&genre='.$genre.'">Century</a>
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
}
?>