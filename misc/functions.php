<?php

function showFilters()
{
	global $topof;
	global $genre;
    global $artist;

	$html = '<input type="hidden" id="current-time-filter" value="" />
             <input type="hidden" id="current-genre-filter" value="" />
	<b><a href="http://t3kdev.tumblr.com" target="_blank">Dev Team Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b>';

    if($artist == "none") //artist has not been specified
    {
        $html .= '
            <a class="filter time-filter" id="freshest" href="#">Freshest</a>&nbsp;&nbsp;
            <b>Top of the:</b>
                <a class="filter time-filter" id="day" href="#">Day</a>
                <a class="filter time-filter" id="week" href="#">Week</a>
                <a class="filter time-filter" id="month" href="#">Month</a>
                <a class="filter time-filter" id="year" href="#">Year</a>
                <a class="filter time-filter" id="century" href="#">Century</a>';
    }
    else //artist has been chosen todo
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
		<a class="filter genre-filter" id="all" href="#">All</a>
		<a class="filter genre-filter" id="dnb" href="#">DnB</a>
		<a class="filter genre-filter" id="dubstep" href="#">Dubstep</a>
		<a class="filter genre-filter" id="electro" href="#">Electro</a>
		<a class="filter genre-filter" id="hardstyle" href="#">Hardstyle</a>
		<a class="filter genre-filter" id="house" href="#">House</a>
		<a class="filter genre-filter" id="trance" href="#">Trance</a>
	<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<a class="uploadlink" href="#">Upload</a></b>
	<div class="hidden" id="upload_box">
	    <div class="hidden" id="upload-box-result"></div>
        YouTube URL: <input id="upload_yturl" type="text" name="url" /><br />
        Title: <input id="upload_title" type="text" name="title" /><br />
        Artist: <input id="upload_artist" type="text" name="artist" /><br />
        Genre: <select id="upload_genre" name="genre">
                <option value="DnB">Drum & Bass</option>
                <option value="Dubstep">Dubstep</option>
                <option value="Electro">Electro</option>
                <option value="Hardstyle">Hardstyle</option>
                <option value="House">House</option>
                <option value="Trance">Trance</option>
               </select> <br />
        Uploaded By: <input id="upload_user" type="text" name="user" value="" /> <br />
        Old Song: <input type="checkbox" value="oldie" id="oldie"/><br />
        <center><button id="upload_song">Upload Song</button> </center>
	</div>
	<br />
	';

	echo $html;
}

function showLogo()
{
    echo '
    <center>
	    <span class="filter">
	        <span id="title">t3k.no</span>
	        <span id="sub_title">beta 2.0</span>
        </span>
	</center>';
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
        case 'freshest':
			return 100000;
    }
}
?>