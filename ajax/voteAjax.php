<?php
include('../connection.php');

if($_POST)
{
        $result = $_POST['result'];
	$ytcode = $_POST['ytcode'];
	$score = $_POST['score']; //higher score
	$ups = $_POST['ups']; //one more vote
	$downs = $_POST['downs'];

    //calulating new score, ups, downs
    if($result == 'up')
    {
        $score ++;
        $ups ++;
    }
    else //result == 'down'
    {
        $score --;
        $downs ++;
    }


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
}
else { die("FAIL: POST not set in voteUpAjax"); }
?>

	<?php echo '<span class="score">' . $score . '</span> [' . $ups . '/' . $downs . '] ';?>
