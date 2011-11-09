<?php
include('connection.php');
if($_POST)
{	
	
	$comment_user=$_POST['name'];
	$comment_user= strip_tags($comment_user);
	$comment_user= mysql_real_escape_string($comment_user);
	$comment_dis=$_POST['comment'];
	$comment_dis = strip_tags($comment_dis);
    $comment_dis = mysql_real_escape_string($comment_dis);
	$ytcode=$_POST['ytcode'];
	$i=$_POST['i'];
	$date = new DateTime();
	

	$qry = mysql_query("insert into comments(com_user,com_dis,youtubecode, upload_date) values ('$comment_user','$comment_dis','$ytcode', NOW())");
	if (!$qry)
                die("FAIL: " . mysql_error());
}
else { }

$comment_dis = str_replace('\\' , '', $comment_dis);
?>
<ol id="update_<?php echo $i; ?>" class="timeline">
<li class="box">
<span class="com_name"> <?php echo $comment_user . ': ';?></span> 
<span class="com_text"> <?php echo $comment_dis;?></span>
<span class="com_date"> <?php echo $date->format('M. j, Y G:i:s'); ?></span>
</li>
</ol>