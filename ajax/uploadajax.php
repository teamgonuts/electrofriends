<?php
include('../connection.php');
$table_name = 'songs';
$ok = true;
$user = "Anonymous";
//test change
//=======================Upload Song to DB==========================//
if(isset($_POST['title']) && validPost())
{
    if(isset($_POST['oldie']))
        $oldie = $_POST['oldie'];
    else
        $oldie = 'unchecked';

    if(isset($_COOKIE['recent_upload'])){//if the user recently uploaded a song
        if($_COOKIE['recent_upload'] == '1'){ //only submitted one song, let the user submit again
            $inOneDay = 60 * 60 * 24 + time(); 
            setcookie("recent_upload", "2" , $inOneDay); //now the user can't submit again for another day
        }
        else {//user can't submit again
            echo 'Please wait 24 hours to submit again <br />';
            $ok = false;
        }
    }
    else //user hasn't submitted a song in the last 24 hours
    {
        $inOneDay = 60 * 60 * 24 + time(); 
        setcookie("recent_upload", "1" , $inOneDay);
    }

                
    $title = safeString($_POST['title']);
    $artist = safeString($_POST['artist']);
    $genre = safeString($_POST['genre']);
    $url = safeString(GetYouTubeVideoId($_POST['ytcode']));
    $user = safeString($_POST['user']);
    

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
        else{
            echo 'Upload Successful';

            //adding user to the user's database if he doesn't already exist
            $qry = mysql_query('INSERT IGNORE INTO users (user) VALUES ("' . $user . '")');
            if (!$qry)
                die("FAIL: " . mysql_error());
        }
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
       'ytcode' => '  Please enter a URL',
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
        //=====WHAT TO DISPLAY IF THERE ARE ERRORS===//
        echo 'Upload Failed:<br />';
         foreach($errors as $err)
         {
            echo $err . '<br />';
         }
         //correcting last comma

         return false;
    }
    
    return true;
}
?>
