<html>
<head>

<meta charset="UTF-8">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="js/swfobject.js">//embedding youtube videos</script>
    <script type="text/javascript" src="coffee/rankings.js"></script>
    <script type="text/javascript" src="coffee/test.js"></script>
</head>

<body style="background-color:black;">
<?php
include ("connection.php");

$qry = mysql_query("SELECT user FROM songs");
if (!$qry)
    die("FAIL: " . mysql_error());

while($row = mysql_fetch_array($qry)) {
    $qry = mysql_query("INSERT INTO users VALUES " . $row['user']);
}
?>
</body>
</html>
