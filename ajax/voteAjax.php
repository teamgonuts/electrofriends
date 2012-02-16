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
	
	
	if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) //adding ip for user
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
	else 
		$ip = $_SERVER['REMOTE_ADDR'];

	$qry = mysql_query("INSERT INTO ipcheck (ytcode, ip) VALUES ('$ytcode', '$ip')");
			if (!$qry)
				die("FAIL: " . mysql_error());

    //updating user's score in database
    $qry = mysql_query('UPDATE users SET points=points + '. $toAdd . ' WHERE user="'. $user .'"');
    if (!$qry)
        die("FAIL: " . mysql_error());
}
else { die("FAIL: POST not set in voteUpAjax"); }
?>

	<?php echo '<span class="score">' . $score . '</span> [' . $ups . '/' . $downs . '] ';?>
