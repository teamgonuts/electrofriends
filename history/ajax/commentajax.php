<?php
include('../connection.php');
if($_POST)
{	
	
	$user = $_POST['user'];
    $user = urldecode($user);
	$user= strip_tags($user);
	$user= mysql_real_escape_string($user);

	$comment=$_POST['comment'];
    $comment = urldecode($comment);
	$comment = strip_tags($comment);
    $comment = mysql_real_escape_string($comment);

	$ytcode=$_POST['ytcode'];
    $i = $_POST['i'];

    $date = new DateTime();
    
	$qry = mysql_query("insert into comments(com_user,com_dis,youtubecode, upload_date)
	                    values ('$user','$comment','$ytcode', NOW())");
	if (!$qry)
                die("FAIL: " . mysql_error());
}
else
{
    echo 'Comment Ajax Error: No POST set. Tell Calvin if you see this';
}
//fixing strings

$comment = str_replace('\\' , '', $comment);
$user  = str_replace('\\' , '', $user);

?>

<ol id="update_<?php echo $i; ?>" class="timeline">
    <li class="box">
        <span class="com_name"> <?php echo $user . ': ';?></span>
        <span class="com_text"> <?php echo $comment;?></span>
        <span class="com_date"> <?php echo $date->format('M. j, Y G:i:s'); ?></span>
    </li>
</ol>