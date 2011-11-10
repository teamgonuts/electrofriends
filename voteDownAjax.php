<?php
include('connection.php');

if($_POST)
{	
	$ytcode = $_POST['ytcode'];
	$score = $_POST['score'] - 1; //higher score
	$ups = $_POST['ups']; //one more vote
	$downs = $_POST['downs'] + 1;
	$i = $_POST['i'];
    
    //======================== UPDATING SONG'S SCORE =================//
    
    $qry = "UPDATE songs SET score=score-1 , downs=downs+1 WHERE youtubecode='". $ytcode."'";
			$qry = mysql_query($qry);
			if (!$qry)
				die("FAIL: " . mysql_error());
}
else { die("FAIL: POST not set in voteUpAjax"); }
?>
<input type="hidden" id="score_<?php echo $i; ?>" value="<?php echo $score; ?>"/> 
<input type="hidden" id="ups_<?php echo $i; ?>" value="<?php echo $ups; ?>"/> 
<input type="hidden" id="downs_<?php echo $i; ?>" value="<?php echo $downs; ?>"/> 
<center>
	<form action="#" method="post">
		<input type="submit" class="upvote" id="<?php echo $i; ?>" value=" + " style="width:30px;" />
	</form>
	<?php echo $score . '[' . $ups . '/' . $downs . '] <br />';?>
	<form action="#" method="post">
		<input type="submit" class="downvote" id="<?php echo $i; ?>" value=" - " style="width:30px;" />
	</form>
</center>