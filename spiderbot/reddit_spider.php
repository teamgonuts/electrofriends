<html>
<head>
  <title>t3k.no Narwhalbot - Calvin Hawkes</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="reddit_spider.js"></script>
  <style type="text/css">
    input{
      width:500px;
    }
    #reddit-container{
      display:none;
    }
  </style>
</head>
<body >
<?php

require_once("simple_html_dom.php");

?>


<div style="margin-left:25px;">
  <h1>/r/ElectronicMusic</h1>
  <div id="songs">

  </div>
</div>
<div id="reddit-container">
<?php

$html = file_get_html('http://reddit.com/r/electronicmusic');

echo $html;
?>
</div>
</body>
</html>
