
window.Player = class Player
    constructor: ->
        this.initializePlayer()
    
    initializePlayer: ->
        #loads the first song in the rankings into the player
        debug = false
        if debug then console.log 'Player.initializePlayer()'

        params = { allowScriptAccess: "always" };
        swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() +  "&enablejsapi=1&playerapiid=ytplayer" +
                       "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1",
                        "ytplayer", "275", "90", "8", null, null, params);

        this.loadSong(1)#loads first song

    #loads song i in the rankings into the player, loads up song info into current song:
    loadSong: (i) ->
        debug = false
        if debug then console.log 'loadSong Called()'
        $('#currentSongTitle').html $('#title_' + i).val()
        $('#currentSongArtist').html $('#artist_' + i).val()
        $('#currentSongGenre').html $('#genre_' + i).val()
        $('#currentSongUser').html $('#user_' + i).val()

# method is called when the player is ready
# binds listeneres to player
window.onYouTubePlayerReady = (playerid) ->
    debug = false
    if debug then console.log 'player ready'
    ytplayer = document.getElementById(playerid)
    ytplayer.addEventListener("onStateChange", "stateChange");
    ytplayer.addEventListener("onError", "onPlayerError");

#called when the player changes states
#possible values for  newState:
#unstarted (-1), ended (0), playing (1), paused (2), buffering (3), video cued (5)
window.stateChange = (newState) ->
    debug = false
    if debug then console.log 'Player State Change: ' + newState
    switch newState
        when 0 #song ended
            if debug then console.log 'Song Ended'
            #todo: nextSong()
        when 1 #song is playing
            if debug then console.log 'Song Playing'
            #change the title of the page
            document.title = $('#currentSongTitle').html() + ' by ' +
                             $('#currentSongArtist').html()
        when 2 #song is paused
            if debug then console.log 'Song Paused'
            document.title = 'Paused - T3K.NO' #change title of page
        when 3 #song is buffering
            if debug then console.log 'Song Loading'
            document.title = 'Loading Song - T3K.NO' #change title of page

#called when the player crashes
window.onPlayerError = (errorCode) ->
    debug = true
    if debug then console.log 'onPlayerError() called!'
    #play load new player and start playing
    #TODO: it loads the first song when it crashes right now
    params = { allowScriptAccess: "always" };
    swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() +  "&enablejsapi=1&playerapiid=ytplayer" +
                   "&hd=1&iv_load_policy=3&autoplay=1&rel=0&showinfo=0&autohide=1",
                    "ytplayer", "275", "90", "8", null, null, params);

$ ->
    player = new Player

    $(document).on 'click', '.filter', ->
        debug = false
        if debug then console.log 'filter clicked'
        player.loadSong(1) #load the first song in the new rankings

    $(document).on 'click', '.play-button', ->
        i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
        ytplayer = document.getElementById('ytplayer')
        ytplayer.loadVideoById $('#ytcode_' + i).val() #play song
        player.loadSong(i) #load song's info
