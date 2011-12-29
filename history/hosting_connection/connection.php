<?php
//My SQL Connection
$con = mysql_connect("localhost","tknodewp_cal","@A$(H0[+rpE}");
if(!$con)
{
	die('Could not connect: ' . mysql_error());
}

//Selecting database
$db = 'tknodewp_ts';
$db_selected = mysql_select_db($db);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}
?>