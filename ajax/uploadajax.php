<?php
include('../connection.php');
$table_name = 'songs';
$ok = true;
$user = "Anonymous";
//=======================Upload Song to DB==========================//
if(isset($_POST['title']) && validPost())
{
	$title = safeString($_POST['title']);
	$artist = safeString($_POST['artist']);
    $genre = safeString($_POST['genre']);
    $url = safeString(GetYouTubeVideoId($_POST['yturl']));
    $user = safeString($_POST['user']);
    $oldie = $_POST['oldie'];

    //What happens if I insert the same song twice
    if($ok)
    {
        if($oldie == 'checked') //its an old song
        {
            $date = new DateTime();
            $date = $date->sub(new DateInterval('P1000D'));
            $date = $date->format('Y-m-d');
            $qryStr = "INSERT INTO $table_name (title, artist,
                                                    genre, youtubecode,
                                                    user, uploaded_on)
                            VALUES ('$title', '$artist',
                                    '$genre', '$url',
                                    '$user', '$date')";
        }
        else
        {
            $qryStr = "INSERT INTO $table_name (title, artist,
                                                    genre, youtubecode,
                                                    user, uploaded_on)
                            VALUES ('$title', '$artist',
                                    '$genre', '$url',
                                    '$user', NOW())";
        }

        $qry = mysql_query($qryStr);
        if(!$qry)
        {
			$error = mysql_error();

			if(strpos($error, 'Duplicate entry') == 0) //THIS DOESNT WORK
            {
                echo $error . '<br />';
                echo $qryStr . '<br />';
				echo 'Upload Failed: Song Already Submitted <br />';
            }
			else
                die('Error: ' . $error . '<br />');
        }
        else
            echo 'Success';
	}
    else
        echo 'Upload Failed =( <br />';
}
?>



<?php //=======================__FUNCTIONS__==============================//

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

function safeString($in)
{
	$in = strip_tags($in);
	$in = mysql_real_escape_string($in);
	return $in;
}


//checks if forms are submitted and have a length greater than 0
function validPost()
{
    $field_checks = array(
      // 'fieldname' => 'errormessage'
       'title' => '  Please enter a title',
       'artist' => '  Please enter an artist',
       'genre' => '  Please enter a genre' ,
       'yturl' => '  Please enter a URL',
       'user' => '  Please enter a user to credit this song to'
    );

    $errors = array();    
    foreach ($field_checks as $field => $errmsg) 
    {       
        if (!isset($_POST[$field]) || ($_POST[$field] === '')) {
            $errors[] = $errmsg;
        }
    }

    if (count($errors) > 0) 
    {
         foreach($errors as $err)
         {
            echo $err . ',';

         }
         echo '<br />'; //For good measure
         echo 'YouTube URL: <input id="upload_yturl" type="text" name="url" /> <br />
            Title: <input id="upload_title" type="text" name="title" /> <br />
            Artist: <input id="upload_artist" type="text" name="artist" /> <br />
            Genre: <select id="upload_genre" name="genre">
                    <option value="DnB">Drum & Bass</option>
                    <option value="Dubstep">Dubstep</option>
                    <option value="Electro">Electro</option>
                    <option value="Hardstyle">Hardstyle</option>
                    <option value="House">House</option>
                    <option value="Trance">Trance</option>
                   </select> <br />
            Uploaded By: <input id="upload_user" type="text" name="user" value="" /> <br />
            <center><button id="upload_song">Upload Song</button>
            </center>';
         return false;
    }
    
    return true;
}
?>