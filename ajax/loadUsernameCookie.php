
<?php
//returns Anonymous if no cookie is set, otherwise returns the username of the cookie

$username = 'Anonymous';
if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username'];
}

echo $username;
?>
