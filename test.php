<html>
<head>

<meta charset="UTF-8">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
</head>

<body>
<?php
include ("connection.php");

/* ADDING EVERY USER TO THE USER DATABASE
$qry = mysql_query("SELECT user FROM songs");
if (!$qry)
    die("FAIL: " . mysql_error());

while($row = mysql_fetch_array($qry)) {
    echo $row['user'] . '<br>';
    $qry2 = mysql_query('INSERT IGNORE INTO users (user) VALUES ("' . $row['user'] . '")');
    if (!$qry2)
        die("FAIL: " . mysql_error());
}
*/
/* UPDATING SCORES FOR EVERY USER
$qry = mysql_query("SELECT user, score FROM songs");
if (!$qry)
    die("FAIL: " . mysql_error());

while($row = mysql_fetch_array($qry)) {
    echo $row['user'] . ': ' . $row['score'] . '<br>';
    $qry2 = mysql_query('UPDATE users SET points = points + ' . $row['score'] . ' WHERE user = "' . $row['user'] . '"');
    if (!$qry2)
        die("FAIL: " . mysql_error());
}
*/
?>
</body>
</html>
