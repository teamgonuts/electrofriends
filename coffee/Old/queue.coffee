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

window.GeneratedQueue = class GeneratedQueue
    constructor: ->
        this.refresh()

        
    #pulls the current songs from the rankings into the queue
    refresh: ->
        this.clear()
        for i in [1..$('.song-max').length] #add each song in the rankings to the gen-queue
              $('#gen-queue').append(' <li class="queue-item" id="gen-queue-' + i + '"><span class="title"> ' + 
                      $('#title_' + i).val() + '</span><span class="purple">//</span> ' + 
                      $('#artist_' + i).val() + '</li>')

    #deletes all the songs from gen queue
    clear: ->
        debug = true
        if debug then console.log 'GenQueue.clear() called!'
        $('#gen-queue').html('')

$ ->
    genQ = new GeneratedQueue
    
    
    $(document).on 'click', '.filter', ->
        genQ.refresh()

