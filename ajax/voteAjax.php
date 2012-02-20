<?php
include('../connection.php');

if($_POST)
{
        $result = $_POST['result'];
	$ytcode = $_POST['ytcode'];
	$score = $_POST['score']; //higher score
	$ups = $_POST['ups']; //one more vote
	$downs = $_POST['downs'];
	$user = $_POST['user'];

    //first, let's make sure the user hasn't voted before
    if(isset($_COOKIE[$ytcode])) { 
        //user voted before, let's just return the same score
	echo '<span class="score">' . $score . '</span> [' . $ups . '/' . $downs . '] ';
    } else {//user hasn't voted before

        //let's set a cookie so the user can't vote again
        $inTwoMonths = 60 * 60 * 24 * 60 + time(); 
        setcookie($ytcode, $result, $inTwoMonths);

        //calulating new score, ups, downs
        $toAdd = -1; //what to add to the user's score. 
        if($result == 'up')
        {
            $score ++;
            $ups ++;
            $toAdd = 1;
        }
        else //result == 'down'
        {
            $score --;
            $downs ++;
        }

        //updating song's score in database
        $qry = "UPDATE songs SET score='$score' , ups='$ups', downs='$downs' WHERE youtubecode='$ytcode'";
        $qry = mysql_query($qry);
        if (!$qry)
            die("FAIL: " . mysql_error());
            

        //updating user's score in database
        $qry = mysql_query('UPDATE users SET points=points + '. $toAdd . ' WHERE user="'. $user .'"');
        if (!$qry)
            die("FAIL: " . mysql_error());

        echo '<span class="score">' . $score . '</span> [' . $ups . '/' . $downs . '] ';
    }
}
else { die("FAIL: POST not set in voteUpAjax"); }
?>

