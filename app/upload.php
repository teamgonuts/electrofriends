<?php
include('../connection.php');

if (isset($_GET['ytcode']))
{
  $title = safeString($_GET['title']);
  $artist = safeString($_GET['artist']);
  $genre = safeString($_GET['genre']);
  $ytcode = safeString(($_GET['ytcode']));
  $user = safeString($_GET['user']);

  $validPost = validGenre($genre);

  if ($validPost)
  {
    $qryStr = "INSERT INTO songs (title, artist,
                                        genre, youtubecode,
                                        user, uploaded_on)
                    VALUES ('$title', '$artist',
                            '$genre', '$ytcode',
                            '$user', NOW())";

    $qry = mysql_query($qryStr);

    if(!$qry)
    {
      $error = mysql_error();
      if(strpos($error, 'Duplicate entry') == 0) //THIS DOESNT WORK
      {
          echo 'Upload Failed: Song Already Submitted';
      }
      else
        die('Error: ' . $error . '<br />');
    }
    else
    {
        echo 'Upload Successful';

        //adding user to the user's database if he doesn't already exist
        $qry = mysql_query('INSERT IGNORE INTO users (user) VALUES ("' . $user . '")');
        if (!$qry)
            die("FAIL: " . mysql_error());
    }
  }
  else //not valid post
    echo 'Upload Failed';
}
?>

<?php //FUNCTIONS

function safeString($in)
{
  $in = strip_tags($in);
  $in = mysql_real_escape_string($in);
  return $in;
}

function validGenre($genre)
{

  $temp = strtolower($genre);

  if ($temp == 'dnb' || $temp == 'dubstep' ||
      $temp == 'electro' || $temp == 'hardstyle' ||
      $temp == 'house' || $temp == 'trance')
  {
    return true;
  }
  else
    return false;
      
  
}

?>
