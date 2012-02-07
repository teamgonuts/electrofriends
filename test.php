<html>
<head>

<meta charset="UTF-8">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="js/swfobject.js">//embedding youtube videos</script>
    <script type="text/javascript" src="coffee/queue.js"></script>
    
</head>

<body>
<div id="ytplayer">
    <p>You will need Flash 8 or better to view this content.</p>
</div>
<a href="javascript:void(0);" onclick="play();">Play</a>
</body>
<script type="text/javascript">
        var params = { allowScriptAccess: "always" };
        swfobject.embedSWF( "http://www.youtube.com/v/yeClJneSNXA&enablejsapi=1&playerapiid=ytplayer", "ytplayer", "425", "365", "8", null, null, params);

        function play() {
            ytplayer = document.getElementById('ytplayer');
            if (ytplayer) {
                ytplayer.playVideo();
            }
        }
        
</script>
</html>
