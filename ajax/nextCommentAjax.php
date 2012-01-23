
<?php
include('../connection.php');

if($_POST)
{	
    $ytcode = $_POST['ytcode'];
    $lowerLimit = $_POST['lowerLimit'];
    $upperLimit = $_POST['upperLimit'];

    $qry = mysql_query("SELECT * FROM  `comments` 
                                WHERE `youtubecode` = \"$ytcode\"
                                ORDER BY upload_date DESC
                                LIMIT $lowerLimit,$upperLimit ");
    if (!$qry)
        die("FAIL: " . mysql_error());

    $html = '';
    while($row = mysql_fetch_array($qry))
    {
        $com_user=$row['com_user'];
        $com_user = str_replace('\\','',$com_user);
        $comment_dis=$row['com_dis'];
        $comment_dis = str_replace('\\', '', $comment_dis);
        $date = new DateTime($row['upload_date']);

        $html .= '<p class="comment-p">
                    <span class="userName">' . $com_user . '</span>
                    <span class="divider">//</span>
                    <time datetime="' . $date->format('Y-m-d') .'">'.
                     $date->format('d/m/y g:i a')
                    .'</time> : '.  $comment_dis .'
                  </p>'; 
    }
    
    echo $html;
}
else
    echo 'Comment Ajax Error: No POST set. Tell Calvin if you see this';
