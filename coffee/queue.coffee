#class for a Song in a queue
window.Song = class Song
    constructor:(@ytcode, @title, @genre, @artist, @user) ->
        debug = false
        if debug then console.log 'Song Created! ytcode: ' + @ytcode +
                                    ', title: ' + @title +
                                    ', genre: ' + @genre + 
                                    ', artist: ' + @artist + 
                                    ', user: ' + @user
    play: ->
        debug = false 
        if debug then console.log @title + '.play() called!'
        ytplayer = document.getElementById('ytplayer')
        ytplayer.loadVideoById @ytcode

        #loading song into player
        $('#currentSongTitle').html @title
        $('#currentSongArtist').html @artist
        $('#currentSongGenre').html @genre
        $('#currentSongUser').html @user

