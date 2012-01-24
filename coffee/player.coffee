
window.Player = class Player
    constructor: ->
        this.initializePlayer()
    
    initializePlayer: ->
        #loads the first song in the rankings into the player
        debug = true
        if debug then console.log 'Player.initializePlayer()'
        this.loadSong(1)#loads first song

    #loads song i in the rankings into the player, loads up song info into current song:
    loadSong: (i) ->
        $('#currentSongTitle').html $('#title_' + i).val()
        $('#currentSongArtist').html $('#artist_' + i).val()
        $('#currentSongGenre').html $('#genre_' + i).val()
        $('#currentSongUser').html $('#user_' + i).val()

$ ->
    ytplayer = new Player
