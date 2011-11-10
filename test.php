<html>
<head>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
	</script>
</head>
<body>
<?php
if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} 
else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

echo $ip;
?>
</body>
</html>