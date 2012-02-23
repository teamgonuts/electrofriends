
<?php
include('../connection.php');

    //gets the user-queue's cookies that were set and produces the code to put them in the playlist
if(isset($_COOKIE['userQueue'])) { 
    $codeStr = $_COOKIE['userQueue'];

    $splitStr = explode("," , $codeStr);

    for($i=0; $i < sizeOf($splitStr); $i++)
    {
     
        $qry = mysql_query('SELECT * FROM songs WHERE youtubecode="' . $splitStr[$i] . '"'); 
        if (!$qry)
            die("FAIL: " . mysql_error());

        $row = mysql_fetch_array($qry);

        echo' <li class="queue-item user-queue" id="userQ_' . ($i+1) . '">
        <span class="title">' . $row['title']. '</span><br /><span class="purple">//</span> 
         ' . $row['artist'] . '<span class="hidden delete-song">[x]</span>'; 
    }
}
else { die("FAIL: COOKIE not set in getsongCookies"); }
?>

