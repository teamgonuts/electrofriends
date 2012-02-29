
<?php
include('../connection.php');

    //gets the user-queue's cookies that were set and produces the code to put them in the playlist
if(isset($_COOKIE['userQueue'])) { 
    $codeStr = $_COOKIE['userQueue'];

    $splitStr = explode("," , $codeStr);

    for($j=0; $j < sizeOf($splitStr); $j++)
    {
     
        $qry = mysql_query('SELECT * FROM songs WHERE youtubecode="' . $splitStr[$j] . '"'); 
        if (!$qry)
            die("FAIL: " . mysql_error());

        $row = mysql_fetch_array($qry);
        $title = str_replace('\\', '', $row['title']); //clean up string
        $artist = str_replace('\\', '', $row['artist']);
        $user = str_replace('\\', '', $row['user']);
        $genre = $row['genre'];
        $ytcode = $row['youtubecode'];

        $qry = mysql_query('SELECT points FROM users WHERE user = "' . $user .'"');
        if (!$qry)
            die("FAIL: " . mysql_error());
        $row = mysql_fetch_array($qry); 
        $userScore = $row['points'];

        $i = $j + 1;
        echo' <li class="queue-item user-queue" id="userQ_' . ($i) . '">
        <span class="title">' .  $title . '</span><br /><span class="purple">//</span> 
         ' . $artist . '<div class="hidden delete-song">[x]</div>';
        echo '<span class="delete-info"> 
            <input type="hidden" id="uq_ytcode_'. $i .'" value="'. $ytcode .'"/>
            <input type="hidden" id="uq_title_'. $i .'" value="'. $title .'"/>
            <input type="hidden" id="uq_artist_'. $i .'" value="'. $artist .'"/>
            <input type="hidden" id="uq_genre_'. $i .'" value="'. $genre .'"/>
            <input type="hidden" id="uq_user_'. $i .'" value="'. $user .'"/>
            <input type="hidden" id="uq_userScore_'. $i .'" value="'. $userScore .'"/>
            </span></li>';
    }
}
?>

