<?php

function showHeader()
{
	global $topof;
	global $genre;
    global $artist;

	$html = '<center>
	<b><a href="http://t3kdev.tumblr.com">Dev Team Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b>';

    if($artist == "none") //artist has not been specified
    {
        $html .= '
            <a href="index.php?topof=new&genre='.$genre.'">Freshest</a>&nbsp;&nbsp;
            <b>Top of the:</b>
                <a href="index.php?topof=day&genre='.$genre.'">Day</a>
                <a href="index.php?topof=week&genre='.$genre.'">Week</a>
                <a href="index.php?topof=month&genre='.$genre.'">Month</a>
                <a href="index.php?topof=year&genre='.$genre.'">Year</a>
                <a href="index.php?topof=century&genre='.$genre.'">Century</a>';
    }
    else //artist has been chosen
    {
        $html .= '
         <a href="index.php?topof=new&artist='.$artist.'">Freshest</a>&nbsp;&nbsp;
            <b>Top of the:</b>
                <a href="index.php?topof=day&artist='.$artist.'">Day</a>
                <a href="index.php?topof=week&artist='.$artist.'">Week</a>
                <a href="index.php?topof=month&artist='.$artist.'">Month</a>
                <a href="index.php?topof=year&artist='.$artist.'">Year</a>
                <a href="index.php?topof=century&artist='.$artist.'">Century</a>';
    }

	$html .= '
	<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Genre:</b>
		<a href="index.php?topof='.$topof.'&genre=all">All</a>
		<a href="index.php?topof='.$topof.'&genre=dnb">DnB</a>
		<a href="index.php?topof='.$topof.'&genre=dubstep">Dubstep</a>
		<a href="index.php?topof='.$topof.'&genre=electro">Electro</a>
		<a href="index.php?topof='.$topof.'&genre=hardstyle">Hardstyle</a>
		<a href="index.php?topof='.$topof.'&genre=house">House</a>
		<a href="index.php?topof='.$topof.'&genre=trance">Trance</a>
	<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<a class="uploadlink" href="#">Upload</a></b>
	<span id="upload_box"></span>
	</center>
	<br />
	<center>
	<a href="index.php" style="text-decoration:none;"><span id="title">t3k.no</span><span id="sub_title">beta</span></a>
	</center>
	<br /><br />
	';

	echo $html;
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