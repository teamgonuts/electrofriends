<html>
    <head>
        <script type="text/javascript" src="swfobject.js"></script>
    </head>
    <body>
    <div id="ytplayer">
        <p>You will need Flash 8 or better to view this content. Download it Here: http://get.adobe.com/flashplayer/</p>
    </div>
    <div id="ytplayer2">
        <p>You will need Flash 8 or better to view this content. Download it Here: http://get.adobe.com/flashplayer/</p>
    </div>
    <p><b>TEST MUTHA FUCKA</b></p>
    <script type="text/javascript">
        var params = { allowScriptAccess: "always" };
         swfobject.embedSWF("http://www.youtube.com/v/NI2b7qXUlnE&enablejsapi=1&playerapiid=ytplayer",
                            "ytplayer", "425", "365", "8", null, null, params);
         swfobject.embedSWF("http://www.youtube.com/v/n01UOw5uEhw&enablejsapi=1&playerapiid=ytplayer22",
                            "ytplayer2", "425", "365", "8", null, null, params);

        function switchPlayer()
        {
            ytplayer = document.getElementById("ytplayer2");
            if(ytplayer)
            {
                alert(ytplayer.getPlayerState());
                ytplayer.playVideo();
            }
        }

        function onYouTubePlayerReady(playerId) {
          ytplayer = document.getElementById(playerId);
          ytplayer.addEventListener("onStateChange", "ytplayergo");
        }
        //Possible values are unstarted (-1), ended (0), playing (1), paused (2), buffering (3), video cued (5)
        function ytplayergo(newState) {
           //alert("new state:" + newState);
           if(newState == 0)
           {
               ytplayer.playVideo();
           }
        }


    </script>
    <a href="javascript:void(0);" onclick="switchPlayer();">Switch</a
   </body>
 </html>