<?php
include('connection.php');
if($_POST)
{	
	
	$name=$_POST['name'];
	$comment_dis=$_POST['comment'];
	$ytcode=$_POST['ytcode'];
	$date = new DateTime();
	

	$qry = mysql_query("insert into comments(com_user,com_dis,youtubecode, upload_date) values ('$name','$comment_dis','$ytcode', NOW())");
	if (!$qry)
                die("FAIL: " . mysql_error());
}
else { }
?>
<ol id="update" class="timeline">
<li class="box">
<span class="com_name"> <?php echo $name . ': ';?></span> 
<span class="com_text"> <?php echo $comment_dis;?></span>
<span class="com_date"> <?php echo $date->format('M. j, Y G:i:s'); ?></span>
</li>
</ol>