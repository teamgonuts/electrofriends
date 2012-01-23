<?php
include('../connection.php');

if($_POST)
{	
	
	$user = $_POST['user'];
        $user = urldecode($user);
	$user = strip_tags($user);
	$user = mysql_real_escape_string($user);

        $comment = $_POST['comment'];
        $comment = urldecode($comment);
        $comment = strip_tags($comment);
        $comment = mysql_real_escape_string($comment);

	$ytcode = $_POST['ytcode'];
        if (strlen( $ytcode) > 15) //it is the url not the code
            $ytcode = GetYouTubeVideoId($_POST['ytcode']);

        $date = new DateTime();
    
	$qry = mysql_query("insert into comments(com_user,com_dis,youtubecode, upload_date)
	                    values ('$user','$comment','$ytcode', NOW())");
	if (!$qry)
                die("FAIL: " . mysql_error());
}
else
{
    echo 'Comment Ajax Error: No POST set. Tell Calvin if you see this';
}

//fixing strings
$comment = str_replace('\\' , '', $comment);
$user  = str_replace('\\' , '', $user);
?>
<p class="comment-p">
    <span class="userName"><?php echo $user ?></span>
    <span class="divider">//</span>
    <time datetime="<?php echo $date->format('d/m/y g:i a') ?>"></time> : <?php echo $comment ?>
</p> 


<?php //functions
//Gets youtube song ID
//Source: http://codestips.com/php-parse-youtube-video-id/
function GetYouTubeVideoId($youtubeUrl)
{
    global $ok;
    $youtubeID = 'invalid'; //should be overwritten if valid url
    try
    {
        $link = parse_url_helper($youtubeUrl);
        parse_str($link['query'], $qs);
        $youtubeID = $qs['v'];
    }
    catch (Exception $e) 
    {
        echo 'Error: Invalid URL: ',  $e->getMessage(), '<br />';
        $ok = false;
    }
    
    return $youtubeID;
}

//using php's parse URL but throws exception if not in valid format
function parse_url_helper($youtubeURL)
{
    $link = parse_url($youtubeURL);
    if(!array_key_exists('query', $link))
        throw new Exception('No \'Query\'');
    else
        return $link;
}
?>
