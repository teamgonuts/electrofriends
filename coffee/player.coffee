
window.Player = class Player
    constructor: ->
        this.initializePlayer()
    
    initializePlayer: ->
        #loads the first song in the rankings into the player
        debug = false
        if debug then console.log 'Player.initializePlayer()'
        this.loadSong(1)#loads first song

    #loads song i in the rankings into the player, loads up song info into current song:
    loadSong: (i) ->
        $('#currentSongTitle').html $('#title_' + i).val()
        $('#currentSongArtist').html $('#artist_' + i).val()
        $('#currentSongGenre').html $('#genre_' + i).val()
        $('#currentSongUser').html $('#user_' + i).val()

    #function is executed when embedded player is loaded
    onYouTubePlayerReady: (playerID) ->
        ytplayer = document.getElementById(playerID)
        ytplayer.addEventListener("onStateChange", "stateChange");
        ytplayer.addEventListener("onError", "onPlayerError");

    #function is executed when the player changes state
    stateChange: (newState) ->
        debug = true
        if debug then console.log 'New Player State: ' + newState

    #function is executed when the player crashes
    onPlayerError: (error) ->
        debug = true
        if debug then console.log 'Error Code: ' + error
$ ->
    ytplayer = new Player

    onYouTubePlayerReady: (playerID) ->
        debug = true
        if debug then console.log 'onYouTubePlayerReady( ' + playerID + ')'
        ytplayer = document.getElementById(playerID)
        ytplayer.addEventListener("onStateChange", "stateChange");
        ytplayer.addEventListener("onError", "onPlayerError");
