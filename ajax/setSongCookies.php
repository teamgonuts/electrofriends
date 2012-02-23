<?php
include('../connection.php');

if($_POST) {
    $ytcodes = $_POST['ytcodes'];

    $inTwoWeeks = 60 * 60 * 24 * 14 + time(); 
    setcookie("userQueue", $ytcodes, $inTwoWeeks);
}
else { die("FAIL: POST not set in setSongCookies.php"); }
?>
