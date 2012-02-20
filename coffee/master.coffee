###=================================================
----------------Youtube Player----------------------
=================================================###
window.Player = class Player
    constructor: ->
        this.initializePlayer()
        @previousSongs = new Array() 
        @songsToRemember = 10 #number of songs to keep in history
    
    initializePlayer: ->
        #loads the first song in the rankings into the player
        debug = false
        if debug then console.log 'Player.initializePlayer()'

        params = { allowScriptAccess: "always" };
        swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() +  "&enablejsapi=1&playerapiid=ytplayer" +
                       "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1",
                        "ytplayer", "275", "90", "8", null, null, params);

        this.loadSongInRankings(1)#loads first song

    #loads song 'i' info from rankings into current song in player
    #sets the global variable currentSong
    loadSongInRankings: (i) ->
        debug = false
        if debug then console.log 'loadSongInRankings Called()'
        window.currentSong = new Song($('#ytcode_' + i).val(), 
                                      $('#title_' + i).val(), 
                                      $('#genre_' + i).val(), 
                                      $('#artist_' + i).val(), 
                                      $('#user_' + i).val(), 
                                      $('#userScore_' + i).val())
        this.updateCurrentSongInfo()

    #loads the song in the userQ at index i into the player
    #sets the global variable currentSong
    loadSongInQueue: (i) ->
        debug = false
        if debug then console.log 'loadSongInQueue Called(' + i + ')'
        console.log window.queue.userQ.songs[0].title
        window.currentSong = new Song(window.queue.userQ.songs[i].ytcode,
                                      window.queue.userQ.songs[i].title,
                                      window.queue.userQ.songs[i].genre,
                                      window.queue.userQ.songs[i].artist,
                                      window.queue.userQ.songs[i].user,
                                      window.queue.userQ.songs[i].userScore)
        this.updateCurrentSongInfo()

    #adds song (object) to the previousSongs queue
    #if there are already 10 songs in queue, removes the last element to maintain size
    addToHistory: (song) ->
        debug = false
        if debug then console.log 'adding ' + song.title + ' to song history'
        @previousSongs.push song #adds the song to the end of the array
        if @previousSongs.length > @songsToRemember
            @previousSongs.shift()

    #loads the previous song played 
    #DOES NOT add currently playing song back into the queue
    previousSong: ->
        debug = false
        if debug then console.log 'player.previousSong()'
        if @previousSongs.length > 0 #if there are songs in the history
            window.currentSong = @previousSongs.pop()
            this.updateCurrentSongInfo()
            ytplayer = document.getElementById('ytplayer')
            ytplayer.loadVideoById currentSong.ytcode

    #updates the currentSong's info (title, artist, genre, user)
    #from window.currentSong
    updateCurrentSongInfo: ->
        debug = false
        if debug then console.log 'player.updateCurrentSongInfo()'
        $('#currentSongTitle').html window.currentSong.title
        $('#currentSongArtist').html window.currentSong.artist
        $('#currentSongGenre').html window.currentSong.genre
        $('#currentSongUser').html (window.currentSong.user + ' (' + window.currentSong.userScore + ')')
        

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
            $('.next-song').click()
            return
        when 1 #song is playing
            if debug then console.log 'Song Playing'
            incrementPlayCount()
            #change the title of the page
            document.title = $('#currentSongTitle').html() + ' by ' +
                             $('#currentSongArtist').html()
        when 2 #song is paused
            if debug then console.log 'Song Paused'
            document.title = 'Paused - T3K.NO' #change title of page
        when 3 #song is buffering
            if debug then console.log 'Song Loading'
            document.title = 'Loading Song - T3K.NO' #change title of page

window.incrementPlayCount =  ->
    debug = false
    #parsing ytcode
    ytplayer = document.getElementById('ytplayer')
    video_id = ytplayer.getVideoUrl().split('v=')[1];
    ampersandPosition = video_id.indexOf('&');
    if(ampersandPosition != -1) 
      ytcode = video_id.substring(0, ampersandPosition);

    if debug then console.log 'window.incrementPlayCount(' + ytcode + '), currentSong.played: ' + window.currentSong.played
    if not window.currentSong.played
        window.currentSong.played = true
        $.post 'ajax/incrementPlayCount.php',
            ytcode: ytcode
            (data) ->
                if debug then console.log ' Successfully Incremented Play Count!'

#called when the player crashes
window.onPlayerError = (errorCode) ->
    debug = false
    if debug then console.log 'onPlayerError() called!'
    $('.next-song').click() #start playing next song
    return


###=================================================
---------------------Queue--------------------------
=================================================###
#class for a Song in a queue
window.Song = class Song
    constructor:(@ytcode, @title, @genre, @artist, @user, @userScore) ->
        @played = false #boolean to determine if the song has already been played
        debug = false
        if debug then console.log 'Song Created! ytcode: ' + @ytcode +
                                    ', title: ' + @title +
                                    ', genre: ' + @genre + 
                                    ', artist: ' + @artist + 
                                    ', userScore: ' + @userScore + 
                                    ', user: ' + @user

#overall queue class, this queue contains the gen and user queue
window.Queue = class Queue
    constructor: ->
        @genQ = new GeneratedQueue
        @userQ = new UserQueue
        @minQ_MaxSongs = 3 #number of songs to display in the min queue
        this.initialize()


    initialize: ->
        @genQ.refresh() #loads all the visible songs on the page into the gen-queue
        this.updateMinQueue() 

    #shows the next 3 songs to be played
    #rankingsChange will be true if updateMinQueue is called as a result of a filter click
    updateMinQueue: (rankingsChange = false) ->
        debug = false
        if debug then console.log 'Queue.updateMinQueue() called!'

        $('#min-queue').html('')#clear out old queue
        #adding songs from userQ
        if $('#userQ').find('.queue-item').length != 0
            for i in [0..($('#userQ').find('.queue-item').length-1)]
                if debug then console.log 'Adding Song from UserQ'
                if $('#min-queue').find('.queue-item').length >= @minQ_MaxSongs then break #if we shouldn't add more songs
                if not @userQ.songs[i].played #if it hasnt been played
                    $('#min-queue').append(' <li class="queue-item" id="userQ_' + (i+1) + '_2"><span class="title"> ' + 
                              @userQ.songs[i].title + '</span><span class="purple"> //</span> ' + 
                              @userQ.songs[i].artist) 


        #if new rankings, add the 1st song to the queue
        if rankingsChange then i = 1 else i = parseInt(@genQ.curSong) + 1 
        #if it there are any songs in the genQ left to add AND we should add more songs
        while $('#genQ_' + i).html() != null and $('#min-queue').find('.queue-item').length < @minQ_MaxSongs 
            if debug then console.log 'Next song to add: ' + i
            if debug then console.log 'Adding Song from GenQ: ' + $('#genQ_' + i).html()
            $('#min-queue').append(' <li class="queue-item" id="genQ_' + i + '_2"><span class="title"> ' + 
                      $('#title_' + i).val() + '</span><span class="purple"> //</span> ' + 
                      $('#artist_' + i).val() + '</li>')
            i++


    #sets the current song and queue to the specified paraments then plays song
    #adds the song that was just played to the previouslyPlayed queue
    #params: queue should be 'gen' or 'user'; index should be valid song in queue
    playSong: (queue, index) ->
        debug = false
        if debug then console.log 'Play Song ' + index + ' in ' + queue
        #checking for valid params
        if queue != 'genQ' and queue != 'userQ' 
            console.log 'Queue.playSong ERROR: Invalid param queue: ' + queue
            return
        if index < 1 or index > $('#' + queue).find('.queue-item').length
            console.log 'Queue.playSong ERROR: Index out of bounds: ' + index
            return
        
        $('.queue-item').removeClass('selected-song') #remove current selection
        $('#' + queue + '_' + index).addClass('selected-song')#highlight new song

        window.player.addToHistory window.currentSong #adds previous song to history
        #play song
        if queue is 'genQ' 
            ytcode = $('#ytcode_' + index).val()
            window.player.loadSongInRankings(index)
            @genQ.curSong = index
            #marking all songs in userQ played so next song is the song 
            #directly below this song in the generated queue
            @userQ.markAllPlayed() 
        else #queue = 'userQ' 
            ytcode = @userQ.songs[index-1].ytcode #-1 because the userQ's array is 0 based
            i = index-1# i-1 because userQ array is 0 based
            window.player.loadSongInQueue(i)
            @userQ.songs[i].played = true #mark the song as played

            #marks all the songs below i not played so that the next song
            #to be played in the song directly below i in userQ
            @userQ.markAllNotPlayed(i) 
        this.updateMinQueue() #update the minQueue
        
        if debug then console.log '  about to play song with ytcode: ' + ytcode
        ytplayer = document.getElementById('ytplayer')
        ytplayer.loadVideoById ytcode



window.UserQueue = class UserQueue
    constructor: ->
        debug = false
        if debug then console.log 'User Queue Created!'
        @songs = new Array()

    #append song 'i' from the rankings to the back of the userQueue
    append: (i) ->
        @songs.push new Song( $('#ytcode_' + i).val()
                        $('#title_' + i).val()
                        $('#genre_' + i).val()
                        $('#artist_' + i).val()
                        $('#user_' + i).val()
                        $('#userScore_' + i).val())

        $('#userQ').append(' <li class="queue-item" id="userQ_' + @songs.length + '"><span class="title"> ' + 
                  $('#title_' + i).val() + '</span><span class="purple"> //</span> ' + 
                  $('#artist_' + i).val()) 
        

    #deletes all the songs from user queue
    clear: ->
        debug = false
        if debug then console.log 'UserQueue.clear() called!'
        this.initialize()

    #marks all songs played
    markAllPlayed: ->
        debug = false
        if debug then console.log 'UserQueue.markAllPlayed() called!'
        for song in @songs
            song.played = true

    #marks all the songs below 'index' not played
    markAllNotPlayed: (index) ->
        debug = false
        if debug then console.log 'UserQueue.markAllNotPlayed(' + index + ') called!'
        if $('#userQ').find('.queue-item').length > 1 #if it is 1, then no need to mark anything unplayed
            for i in [index+1..@songs.length-1]
                @songs[i].played = false
                if debug then console.log @songs[i].title + ' played: ' + @songs[i].played

window.GeneratedQueue = class GeneratedQueue
    constructor: ->
        debug = false
        if debug then console.log 'Generated Queue Created!'
        @songs = new Array()

        #current song selected in the generated queue
        #CAN be different than the current song playing, used to determine where in the
        #generated queue to start playing again if the userQueue runs out of songs
        @curSong = 1

    #pulls the current songs from the rankings into the queue
    refresh: ->
        this.clear()
        @curSong = 1
        for i in [1..$('.song-max').length] #add each song in the rankings to the gen-queue
            @songs.push(i)
            $('#genQ').append(' <li class="queue-item" id="genQ_' + i + '"><span class="title"> ' + 
                      $('#title_' + i).val() + '</span><span class="purple"> //</span> ' + 
                      $('#artist_' + i).val() + '</li>')

    #deletes all the songs from gen queue
    clear: ->
        debug = false
        if debug then console.log 'GenQueue.clear() called!'
        $('#genQ').html('')


###=================================================
---------------------Filters--------------------------
=================================================###
#the filters you can click on the side of the rankings (ie genre, time)
window.Filters = class Filters
    constructor: (@genre = "all", @time = "new", @artist="", @user="") ->
        this.highlight()

    isSet: (filter) ->
        @filt(filter)?.length > 0

    #=============returns the appropriate filter value=============
    filt: (filter) ->
        if filter is "genre" 
            @genre
        else if filter is "time" 
            @time
        else if filter is "artist" 
            @artist
        else if filter is "user" 
            @user
        else
            false

    #=============highlights the appropriate filter and sets the correct filter=============
    set: (filter, value) ->
        if filter is "genre" 
            @genre = value
            $('.genre-filter').removeClass('highlight-filter')
            $('#filter-' + value).addClass('highlight-filter')
        else if filter is "time" 
            @time = value
            $('.time-filter').removeClass('highlight-filter')
            $('#filter-' + value).addClass('highlight-filter')
        else if filter is "artist" 
            @artist = value
        else if filter is "user" 
            @user = value
        else
            false

    #=============highlights whatever filters are set=============
    highlight:  ->
        #Highlighting genre
        if @genre is 'all'
            $('#filter-all').addClass('highlight-filter')
        else if @genre is 'dnb'
            $('#filter-dnb').addClass('highlight-filter')
        else if @genre is 'dubstep'
            $('#filter-dubstep').addclass('highlight-filter')
        else if @genre is 'electro'
            $('#filter-electro').addclass('highlight-filter')
        else if @genre is 'hardstyle'
            $('#filter-hardstyle').addclass('highlight-filter')
        else if @genre is 'house'
            $('#filter-house').addclass('highlight-filter')
        else if @genre is 'trance'
            $('#filter-trance').addclass('highlight-filter')
        #highlight top of
        if @time is 'day'
            $('#filter-day').addclass('highlight-filter')
        else if @time is 'week'
            $('#filter-week').addclass('highlight-filter')
        else if @time is 'month'
            $('#filter-month').addclass('highlight-filter')
        else if @time is 'year'
            $('#filter-year').addclass('highlight-filter')
        else if @time is 'century'
            $('#filter-century').addclass('highlight-filter')

###=================================================
---------------------Rankings--------------------------
=================================================###
window.Rankings = class Rankings
    constructor: ->
        @filters = new Filters
        @maxed_song = -1 #song currently maximized in rankings, -1 for no maxed songs
        @songsPerPage = $('tr.song-min').length #default songsPerPage, set this in Rankings.php
        @commentsPerSong = 4 #how many comments can be shown per song
        ###=================================
        #special flag to tell ajax what is currently displayed in rankings
        Possible values:
          'search' - if rankings currently display search results
          'user' - if rankings currently display a specific user's songs
          'normal' - if rankings display a typical genre/time rankings
        ###
        @flag = 'normal' 
        this.initialize()

    #initializes the styles and buttons in the rankings
    initialize: ->
        this.initializeSongs(1, @songsPerPage)
        

    #filt("genre") = "all" is the same as @genre = all
    filt: (filter) ->
        @filters.filt(filter)

    #displays the next comments for song (i)
    nextComments:(index) ->
        debug = false
        if debug then console.log 'Rankings.nextComments(' + index + ')'
        #incrementing iterator value
        lowerLimit = parseInt $('#commentIterator_' + index).val()
        comments = @commentsPerSong
        iterator = lowerLimit + @commentsPerSong
        $('#commentIterator_' + index).val(iterator)
        
        if debug
            console.log 'POSTS: '
            console.log 'lowerLimit: ' + lowerLimit
            console.log 'upperLimit: ' + iterator
            console.log 'ytcode: ' + $('#ytcode_' + index).val()

        $.post 'ajax/nextCommentAjax.php',
            lowerLimit: lowerLimit
            upperLimit: iterator
            ytcode: $('#ytcode_' + index).val()
            (data) ->
                if debug then console.log data
                $('#max_' + index).find('.comment-display').html(data)
                if $('#max_' + index).find('.comment-p').length < comments #no show-more-comments button
                    $('#max_' + index).find('.see-more-comments').addClass('hidden')



    ###checks to see if there should be a showMoreSongs button at the bottom of the rankings
    if there is, show the button and return true, else don't show buton and return false###
    enableMoreSongsButton: ->
        #console.log ('Calling Rankings.enableMoreSongsButton()')
        if $('tr.song-min').length > 0 and $('tr.song-min').length % @songsPerPage is 0 #more songs to show
            $('#showMoreSongs').removeClass('hidden')
            return true
        else
            $('#showMoreSongs').addClass('hidden')
            return false
            
    #changes the title for the rankings
    changeTitle: (title) ->
        console.log('Rankings.changeTitle(' + title + ')')
        $('#rankings-title').text(title)

    #============Submit Comment========#
    #what is called when 'Submit Comment' is clicked, updates song in database and if the comments section of song
    #if it is in the rankings
    #params: comment - text of comment, index - song index to insert comment, -1 means its not in the rankings
    submitComment: (comment, user, index = -1) ->
        debug = false #set to true to debug comments
        if debug then console.log ('User ' + user + ' commenting on ' + index + ': ' + comment)

        if comment is '' then alert 'Please enter a comment'
        else if user is '' then alert 'Please enter a username to comment'
        else #submit comment
            if index != -1 #if its a song in the rankings
                ytcode = $('#ytcode_' + index).val()
            else
                ytcode = $('#upload_yturl').val()

            if debug then console.log 'POSTS:'
            if debug then console.log encodeURIComponent(user)
            if debug then console.log encodeURIComponent(comment)
            if debug then console.log 'ytcode: ' + ytcode
            #todo: if its an uploaded song
            $.post 'ajax/commentAjax.php',
                    user: encodeURIComponent(user)
                    comment: encodeURIComponent(comment)
                    ytcode: ytcode
                    (data) ->
                        if debug then console.log 'Data: ' + data
                        if index != -1
                            if not $('#max_' + index).find('.no-comment').hasClass('hidden')
                                $('#max_' + index).find('.no-comment').addClass('hidden')

                            $('#max_' + index).find('.comment-display').prepend(data)



    #refreshes the title for the rankings based off the filters applied
    refreshTitle: ->
        if this.filt('genre') is 'all' then genre = 'Tracks' else genre=this.filt('genre')
        if genre is 'all' and this.filt('time') is 'new' #the fresh list
            title = 'The Fresh List'
        else if this.filt('time') is 'new' #change up the title to make sense
            title = 'The Freshest ' + genre
        else
            title = "Top " + genre + " of the " + this.filt('time')

        $('#rankings-title').text(title)

    #Preps the comments for each song from startIndex to endIndex in the rankings, checks if it needs a show More Comments button
    #If there are no comments for song, adds Message
    #NOTE: When you change this method you also have to change the ajax in Rankings.search()
    initializeSongs:(startIndex, endIndex) ->
        debug = false
        this.enableMoreSongsButton()
        if debug then console.log ('Rankings.initializeComments(' + startIndex + ',' + endIndex + ') called')
        for i in [startIndex..endIndex]
            #========Comments=====#
            if debug then console.log 'Comments on Song ' + i + ": " + $('#max_' + i).find('.comment-p').length
            if $('#max_' + i).find('.comment-p').length < @commentsPerSong #no show-more-comments button
                $('#max_' + i).find('.see-more-comments').addClass('hidden')
            if $('#max_' + i).find('.comment-p').length is 0 #no comment
                $('#max_' + i).find('.comment-display').html('<p class="no-comment">No Comments. </p>')


    ###searches database for songs with 'searchterm' in either the title or artist 
    and displays results in rankings###
    search: (searchterm) ->
        upperlimit = @songsPerPage #creating a local variable for songsperpage so it can be accessed within the ajax
        commentsPerSong = @commentsPerSong
        searchterm = searchterm.trim()
        if searchterm.length is 0
            alert 'Please enter a search term'
        else
            console.log 'search:' + searchterm
            this.changeTitle ('Searching: ' + searchterm)
            @flag = 'search' 

            $.post 'ajax/searchAjax.php',
                    searchTerm: searchterm
                    upperLimit: upperlimit
                    (data) ->
                        $('#rankings-table').html(data) 
                        #pseudo enablemoresongsbutton because i can't call a function from here?
                        if $('tr.song-min').length > 0 and $('tr.song-min').length % upperlimit is 0 #more songs to show
                            $('#showMoreSongs').removeClass('hidden')
                        else
                            $('#showMoreSongs').addClass('hidden')
                        #pseudo initializing See More Comments
                        if $('tr.song-min').length > 0 
                            for i in [1..$('tr.song-min').length]
                                if $('#max_' + i).find('.comment-p').length is 0 #no comment
                                    $('#max_' + i).find('.comment-display').html('<p class="no-comment">No Comments. </p>')

                                if $('#max_' + i).find('.comment-p').length < commentsPerSong #no show-more-comments button
                                    $('#max_' + i).find('.see-more-comments').addClass('hidden')






###=================================================
-------------------On Load--------------------------
=================================================###

$ ->
    window.player = new Player
    window.queue = new Queue
    window.rankings = new Rankings
    
    #either add to queue or play song, whichever was pressed
    #when the play-button is clicked in a maxed song, loads the song in the player
    $(document).on 'click', '.song-button', ->
        i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
        if $(this).hasClass('play-button')
            queue.playSong('genQ', i)
        else if $(this).hasClass('queue-button')
            queue.userQ.append(i)
            queue.updateMinQueue()


    #when the next song button is clicked
    $(document).on 'click', '.next-song', ->
        debug = false
        if debug then console.log $('#min-queue').find('.queue-item:first-child').html()
        id = $('#min-queue').find('.queue-item:first-child').attr('id') #next songs id
        i = id.split('_')[1] #index of song clicked
        q = id.split('_')[0] #queue that was clicked
        if debug then console.log 'queue: ' + q + ', index: ' + i
        queue.playSong(q, i)

    $(document).on 'click', '.previous-song', ->
        player.previousSong()


    #when a song in the queue is clicked, highlight it and play
    $(document).on 'click', '.queue-item', ->
        debug = false
        if debug then console.log 'queue-item clicked'
        i = $(this).attr('id').split('_')[1] #index of song clicked
        q = $(this).attr('id').split('_')[0] #queue that was clicked
        if debug then console.log 'queue: ' + q + ', index: ' + i
        queue.playSong(q, i)

    #=============changing the rankings via filter=============
    $('.filter').click ->
        #highlighting filter on click
        if $(this).hasClass('genre-filter')
            rankings.filters.set('genre', $(this).html().toLowerCase())
        else   
            rankings.filters.set('time', $(this).html().toLowerCase())

        rankings.refreshTitle()
        #todo: add loading screen to rankings here
        #ajax post
        $.post 'ajax/rankingsAjax.php',
                genrefilter: rankings.filt('genre')
                timefilter: rankings.filt('time')
                (data) ->
                    $('#rankings-table').html(data) 
                    rankings.initialize()
                    rankings.flag = 'normal' #resetting flag
                    queue.genQ.refresh() #updating the generated queue
                    queue.updateMinQueue(true)

    #=============to maximize and minimize songs in the rankings=============
    $(document).on 'click', '.song', ->
        #getting index and song state
        temp = $(this).attr('id').split('_')
        i = temp[1] #index of song in rankings
        state = temp[0] #either min or max
        if state is 'min'
            #if there is another maxed song, hide it
            if rankings.maxed_song != -1
                $('#max_' + rankings.maxed_song).addClass('hidden')
                $('#min_' + rankings.maxed_song).removeClass('hidden')
            else

            #max clicked song
            $('#min_' + i).addClass('hidden')
            $('#max_' + i).removeClass('hidden')
            rankings.maxed_song = i #set clicked song to new maxed song

    #=====queue-controls===
    $(document).on 'click', '.queue-min', ->
        $('#max-queue').addClass 'hidden'
        $('.content').removeClass 'queue-open'
        $('#queue-max').html("[Show Queue]")

    $(document).on 'click', '#queue-max', ->
        if $('#max-queue').hasClass 'hidden'
            $('#max-queue').removeClass 'hidden'
            $('.content:not(#bottom-contents)').addClass 'queue-open'
            $('#queue-max').html("[Close Queue]")
        else #queue is already open, so close it
            $('.queue-min').click()

    #===========showMoreSongs=====================#
    $(document).on 'click', '#showMoreSongs', ->
        lowerLimit = $('tr.song-min').length
        $.post 'ajax/showMoreSongsAjax.php',
                genrefilter: rankings.filt('genre')
                timefilter: rankings.filt('time')
                searchTerm: $('#search-input').val() 
                flag: rankings.flag
                lowerLimit: lowerLimit
                upperLimit: $('tr.song-min').length + rankings.songsPerPage
                (data) ->
                    $('#rankings-table').append(data) 
                    rankings.initializeSongs(lowerLimit, (lowerLimit + rankings.songsPerPage ))
                    #updating queues
                    queue.genQ.refresh() 
                    queue.updateMinQ()

    #===========Submit Comment=====================#
    $(document).on 'click', '.submit-comment', ->
        i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
        rankings.submitComment($('#max_' + i).find('.comment-text').val() , 
                               $('#max_' + i).find('.comment-user').val() ,
                               i)

    #===========See More Comments====================#
    $(document).on 'click', '.see-more-comments', ->
        debug = false
        if debug then console.log 'See-More-Comments Clicked'
        rankings.nextComments $(this).closest('.song').attr('id').split('_')[1] #index of song clicked

        
    #===========Voting Buttons=====================#
    #todo: this only works for voting buttons in the rankings, not in the player
    $(document).on 'click', '.vote-button', ->
        debug = false #turn on debugging for voting buttons
        if not $(this).hasClass('highlight-vote') #if I haven't alredy made the same vote
            i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked

            if $(this).attr('id') is 'up-vote' #up-vote
                if debug then console.log 'UpVote called on ' + i
                result = 'up'
                toAdd = 1; #what to add to
                $('#max_' + i).find("#up-vote").addClass('highlight-vote') #highlighting new vote
                $('#max_' + i).find("#down-vote").removeClass('highlight-vote')#remove highlight from old vote userScore
            else if $(this).attr('id') is 'down-vote' #down-vote
                if debug then console.log 'DownVote called on ' + i
                result = 'down'
                toAdd = -1; #what to add to userScore
                $('#max_' + i).find("#down-vote").addClass('highlight-vote')#highlight new vote
                $('#max_' + i).find("#up-vote").removeClass('highlight-vote')#remove highlight from old vote
            else
                if debug then console.log 'Error: Something went wrong with the vote-buttons'
                result = 'error'

            $.post 'ajax/voteAjax.php',
                    result: result
                    ytcode: $('#ytcode_' + i).val()
                    user: $('#user_' + i).val()
                    score: $('#score_' + i).val()
                    ups: $('#ups_' + i).val()
                    downs: $('#downs_' + i).val()
                    (data) ->
                        if debug then console.log 'Vote Success: ' + data
                        $('#max_' + i).find('.score-container').html(data) #changing score in max
                        $('#min_' + i).find('.score-container').html(data) #changing score in min
                        oldScore = parseInt($('#max_' + i).find('.user-score').html()) #changing score in min
                        $('#max_' + i).find('.user-score').html(oldScore + toAdd)

    #===========Search=====================#
    $('#search-button').click -> rankings.search($('#search-input').val())
    $(document).on 'click', '.search-filter', -> 
        rankings.search($(this).html())

    #===========Upload Song=====================#
    $('#upload_song').click -> 
        debug = false
        $.post 'ajax/uploadAjax.php',
                ytcode: $('#upload_yturl').val()
                title: $('#upload_title').val()
                oldie: $('#upload_oldie').attr('checked')
                artist: $('#upload_artist').val()
                genre: $('#upload_genre').val()
                user: $('#upload_user').val()
                (data) ->
                    console.log('Upload Result: ' + data)
                    $("#upload-box-result").html(data);
                    $("#upload-box-result").removeClass('hidden');
                    if($("#upload-box-result").html().indexOf("Upload Failed") is -1)#upload success
                        $('#upload-box-result').css('color', '#33FF33')
                        if debug then console.log 'comment: ' + $('#upload_comment').val()
                        if $('#upload_comment').val() != ''#submitting initial comment
                            rankings.submitComment($('#upload_comment').val() , 
                                                   $('#upload_user').val() ,
                                                   -1) #-1 because song is not in rankings

                        #clearing fields
                        $('#upload_yturl').val('')
                        $('#upload_title').val('')
                        $('#upload_artist').val('')
                        $('#upload_comment').val('')
                    else #upload failed
                        $('#upload-box-result').css('color', 'red')
                    
