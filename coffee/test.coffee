window.onYouTubePlayerReady = (playerid) ->
    console.log 'player ready'
    ytplayer = document.getElementById(playerid)
    ytplayer.playVideo()


