###=================================================
if debug then console.log 'delete-song clicked'
----------------Youtube Player----------------------
=================================================###
window.Player = class Player
    constructor: ->
        this.initializePlayer()
        @previousSongs = new Array() 
        @songsToRemember = 10 #number of songs to keep in history
    
    initializePlayer: ->
        #loads the first song in the rankings into the player

        
        params = { allowScriptAccess: "always" };
        swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() +  "&enablejsapi=1&playerapiid=ytplayer" +
                       "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1",
                        "ytplayer", "200", "90", "8", null, null, params);

        this.loadSongInRankings(1)#loads first song
        this.resizeMaxQueue()

    #refreshes the embed of the player to help with errors
    #automatically starts playing the next-song
    refreshPlayer: ->
        $('.next-song').click() #window.current_song is set
        params = { allowScriptAccess: "always" };
        swfobject.embedSWF("http://www.youtube.com/v/" + window.currentSong.ytcode +  "&enablejsapi=1&playerapiid=ytplayer" +
                       "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1&autoplay=1",
                        "ytplayer", "200", "90", "8", null, null, params);
        this.updateCurrentSongInfo()
        
    #resizes the max queue to fit the screen
    resizeMaxQueue: ->
        nh = $(window).height() - 12 - $('#bottomControls').height()
        $('#max-queue').height(nh)

    #loads song 'i' info from rankings into current song in player
    #sets the global variable currentSong
    loadSongInRankings: (i) ->
        window.currentSong = new Song($('#ytcode_' + i).val(), 
                                      $('#title_' + i).val(), 
                                      $('#genre_' + i).val(), 
                                      $('#artist_' + i).val(), 
                                      $('#user_' + i).val(), 
                                      $('#userScore_' + i).val(),
                                      $('#score_' + i).val(), 
                                      $('#ups_' + i).val(), 
                                      $('#downs_' + i).val())
        this.updateCurrentSongInfo()

    #loads the song in the userQ at index i into the player
    #sets the global variable currentSong
    loadSongInQueue: (i) ->
        window.currentSong = new Song(window.queue.userQ.songs[i].ytcode,
                                      window.queue.userQ.songs[i].title,
                                      window.queue.userQ.songs[i].genre,
                                      window.queue.userQ.songs[i].artist,
                                      window.queue.userQ.songs[i].user,
                                      window.queue.userQ.songs[i].userScore,
                                      window.queue.userQ.songs[i].score,
                                      window.queue.userQ.songs[i].ups,
                                      window.queue.userQ.songs[i].downs)
        this.updateCurrentSongInfo()

    #adds song (object) to the previousSongs queue
    #if there are already 10 songs in queue, removes the last element to maintain size
    addToHistory: (song) ->
        @previousSongs.push song #adds the song to the end of the array
        if @previousSongs.length > @songsToRemember
            @previousSongs.shift()

    #loads the previous song played 
    #DOES NOT add currently playing song back into the queue
    previousSong: ->
        if @previousSongs.length > 0 #if there are songs in the history
            window.currentSong = @previousSongs.pop()
            this.updateCurrentSongInfo()
            ytplayer = document.getElementById('ytplayer')
            ytplayer.loadVideoById currentSong.ytcode

    #pauses the current song
    playOrPause: ->
        ytplayer = document.getElementById('ytplayer')
        state = ytplayer.getPlayerState()
        if state is 1 #video is playing
            ytplayer.pauseVideo()
        else#video is paused
            ytplayer.playVideo()

        
    #updates the currentSong's info (title, artist, genre, user)
    #from window.currentSong
    updateCurrentSongInfo: ->
        $('#currentSongTitle').html window.currentSong.title
        $('#currentSongArtist').html window.currentSong.artist
        $('#currentSongGenre').html window.currentSong.genre
        $('#currentSongUser').html (window.currentSong.user + ' {<span class="user-score">' + window.currentSong.userScore + '</span>}')
        $('#currentSongScore').html window.currentSong.score
        $('#currentSongUps').html window.currentSong.ups
        $('#currentSongDowns').html window.currentSong.downs
        

# method is called when the player is ready
# binds listeneres to player
window.onYouTubePlayerReady = (playerid) ->
    ytplayer = document.getElementById(playerid)
    ytplayer.addEventListener("onStateChange", "stateChange");
    ytplayer.addEventListener("onError", "onPlayerError");

#called when the player changes states
#possible values for  newState:
#unstarted (-1), ended (0), playing (1), paused (2), buffering (3), video cued (5)
window.stateChange = (newState) ->
    switch newState
        when 0 #song ended
            $('.next-song').click()
            return
        when 1 #song is playing
            incrementPlayCount()
            #change the title of the page
            document.title = $('#currentSongTitle').html() + ' by ' +
                             $('#currentSongArtist').html()
        when 2 #song is paused
            document.title = 'Paused - t3k.no' #change title of page
        when 3 #song is buffering
            document.title = 'Loading Song - t3k.no' #change title of page

window.incrementPlayCount =  ->
    #parsing ytcode
    ytplayer = document.getElementById('ytplayer')
    video_id = ytplayer.getVideoUrl().split('v=')[1];
    ampersandPosition = video_id.indexOf('&');
    if(ampersandPosition != -1) 
      ytcode = video_id.substring(0, ampersandPosition);

    if not window.currentSong.played
        window.currentSong.played = true
        $.post 'ajax/incrementPlayCount.php',
            ytcode: ytcode
            (data) ->

#called when the player crashes
window.onPlayerError = (errorCode) ->
    player.refreshPlayer()
    return


###=================================================
---------------------Queue--------------------------
=================================================###
#class for a Song in a queue
window.Song = class Song
    constructor:(@ytcode, @title, @genre, @artist, @user, @userScore, @score, @ups, @downs, @played = false) ->

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

        $('#min-queue').html('')#clear out old queue
        #adding songs from userQ
        if $('#userQ').find('.queue-item').length != 0
            for song in $('#userQ').find('li')
                if $('#min-queue').find('.queue-item').length >= @minQ_MaxSongs then break #if we shouldn't add more songs
                i = song.id.split('_')[1] - 1 #index of song in queue array
                if not @userQ.songs[i].played #if it hasnt been played
                    $('#min-queue').append(' <li class="queue-item ellipsis" id="userQ_' + (i+1) + '_2"><span class="title"> ' + 
                              @userQ.songs[i].title + '</span><span class="purple"> //</span> ' + 
                              @userQ.songs[i].artist) 


        #if new rankings, add the 1st song to the queue
        if rankingsChange then i = 1 else i = parseInt(@genQ.curSong) + 1 #i is the next song to be played
        #if it there are any songs in the genQ left to add AND we should add more songs
        songs = $('#genQ').find('li')
        while $('#genQ_' + i).html() and $('#min-queue').find('.queue-item').length < @minQ_MaxSongs 
            id = songs[i-1].id.split('_')[1]
            $('#min-queue').append(' <li class="queue-item" id="genQ_' + id + '_2"><span class="title"> ' + 
                      $('#title_' + id).val() + '</span><span class="purple"> //</span> ' + 
                      $('#artist_' + id).val() + '</li>')
            i++


    #sets the current song and queue to the specified paraments then plays song
    #adds the song that was just played to the previouslyPlayed queue
    #params: queue should be 'gen' or 'user'; index should be valid song in queue
             #rankings will be true if the index of the songs should come from the rankings
    playSong: (queue, index, rankings=false) ->
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
            #incase the order of the songs has been swtiched, lets look up the ytcode out of the genQ
            ytcode = $('#ytcode_' + index).val()
            window.player.loadSongInRankings(index)

            if rankings then @genQ.curSong = index else @genQ.curSong = $('#genQ_' + index).index() + 1
            #marking all songs in userQ played so next song is the song 
            #directly below this song in the generated queue
            @userQ.markAllPlayed() 
            if $('#max_' + index).css('display') is 'none' then $('#min_' + index).click() #open the song that is being played
        else #queue = 'userQ' 
            i = index-1# i-1 because userQ array is 0 based
            ytcode = @userQ.songs[i].ytcode #-1 because the userQ's array is 0 based
            window.player.loadSongInQueue(i)
            @userQ.songs[i].played = true #mark the song as played

            #marks all the songs below i not played so that the next song
            #to be played in the song directly below i in userQ
            qindex = $('#userQ_' + index).index() #index of song in the queue
            @userQ.markAllNotPlayed(qindex + 1) 

        this.updateMinQueue() #update the minQueue
        #if this is the last song in the queue, load more songs
        if $('#min-queue').find('li').length <= 1  #showing more songs at 19 so we can instantly play 20
            $('#showMoreSongs').click() #no more songs in rankings, let's see some more

        ytplayer = document.getElementById('ytplayer')
        ytplayer.loadVideoById ytcode



window.UserQueue = class UserQueue
    constructor: ->
        @songs = new Array()
        this.getSongCookies()#retrieve old userQueue songs

    #append song 'i' from the rankings to the back of the userQueue
    append: (i) ->
        @songs.push new Song( $('#ytcode_' + i).val()
                        $('#title_' + i).val()
                        $('#genre_' + i).val()
                        $('#artist_' + i).val()
                        $('#user_' + i).val()
                        $('#userScore_' + i).val()
                        $('#score_' + i).val()
                        $('#ups_' + i).val()
                        $('#downs_' + i).val())

        $('#userQ').append(' <li class="queue-item user-queue" id="userQ_' + @songs.length + '"><div class="hidden delete-song">[x]</div><div class="queue-song"><span class="title"> ' + 
                  $('#title_' + i).val() + '</span><br /><span class="purple"> //</span> ' + 
                  $('#artist_' + i).val() + '</div>') 
        window.queue.updateMinQueue()
        this.updateSongCookies()

    #sets a new cookie for the current userSongs
    updateSongCookies: ->
        songStr = ""
        for song in $('#userQ').find('li')
            i = parseInt(song.id.split('_')[1]) - 1#index of song in @songs
            songStr = songStr + @songs[i].ytcode + ','
        songStr = songStr.substr(0, songStr.length-1) #removing last comma
            
        $.post 'ajax/setSongCookies.php',
                ytcodes: songStr
                (data) ->

    #retrieves songs that were stored in cookies from last time
    getSongCookies: ->

        $.get 'ajax/getSongCookies.php' , (data) ->
            $('#userQ').html(data) 
            #creating songs and adding to userq
            if $('.user-queue').length > 0
                for i in [1..$('.user-queue').length]
                    window.queue.userQ.songs.push new Song( $('#uq_ytcode_' + i).val()
                                    $('#uq_title_' + i).val()
                                    $('#uq_genre_' + i).val()
                                    $('#uq_artist_' + i).val()
                                    $('#uq_user_' + i).val()
                                    $('#uq_userScore_' + i).val()
                                    $('#uq_score_' + i).val()
                                    $('#uq_ups_' + i).val()
                                    $('#uq_downs_' + i).val()
                                    , true)
                $('.delete-info').html('')
            window.queue.updateMinQueue()
    

    #deletes the song i from the user queue
    # the i that is a param is the correct number for the queue but i-1 should be used for @songs
    delete: (i) ->
        $('#userQ_' + i).remove() #remove the li
        @songs.splice(i-1, 1) #remove it from the array
        #renaming the ids of all songs in the userQ
        i = 1
        for song in $('#userQ').children()
            $(song).attr('id', 'userQ_' + i)
            i++
        this.updateSongCookies()
        window.queue.updateMinQueue() #update the minQueue



    #deletes all the songs from user queue
    clear: ->
        this.initialize()

    #marks all songs played
    markAllPlayed: ->
        for song in @songs
            song.played = true

    #corrects any mistakes as the result of reordering of user queue
    refresh: ->
        if $('.selected-song') and $('.selected-song').closest('.queue').attr('id') is 'user-queue'
            index = $('.selected-song').index() + 1 #list number
            this.markAllNotPlayed(index)


            

    #marks all the songs below 'index' not played
    markAllNotPlayed: (index) ->
        if $('#userQ').children().length > 1 #if it is 1, then no need to mark anything unplayed
            for i in [0..@songs.length-1]
                qindex = $('#userQ_' + (i+1)).index()
                if i < index
                    @songs[qindex].played = true
                else if i >= index and i <= @songs.length-1
                    @songs[qindex].played = false

window.GeneratedQueue = class GeneratedQueue
    constructor: ->
        @songs = new Array()

        #current song selected in the generated queue
        #CAN be different than the current song playing, used to determine where in the
        #generated queue to start playing again if the userQueue runs out of songs
        @curSong = 0

    #sets the current song to the correct index after the genQ has been shuffled or drag+drop
    setCurrentSong: ->
        if $('.selected-song') and this.curSong > 1
            $('.selected-song').closest('queue').attr('id') is 'generated-queue'
            this.curSong = $('.selected-song').index() + 1

    #pulls the current songs from the rankings into the queue
    refresh: (reset = true) ->
        this.clear()
        if reset then @curSong = 1

        for i in [1..$('.song-max').length] #add each song in the rankings to the gen-queue
            @songs.push(i)
            $('#genQ').append(' <li class="queue-item" id="genQ_' + i + '"><span class="title"> ' + 
                      $('#title_' + i).val() + '</span><br /><span class="purple"> //</span> ' + 
                      $('#artist_' + i).val() + '</li>')

    #deletes all the songs from gen queue
    clear: ->
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
        #if you change @commentsPerSong, you must also change SQL statement in Song.php
        @commentsPerSong = 5 #how many comments can be shown per song,
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
        this.loadUsernameCookie()
        $('#min_1').click() #open the first song



    #if a cookie is set for a username, load it
    loadUsernameCookie: ->
        $.get 'ajax/loadUsernameCookie.php', (data) ->
            $('.song-max').find('.comment-user').val(data)
            $('#upload_user').val(data)

    #updates the rankings based off the current filters
    update: ->
        this.refreshTitle()
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
        
        

    #filt("genre") = "all" is the same as @genre = all
    filt: (filter) ->
        @filters.filt(filter)

    #displays the next comments for song (i)
    nextComments:(index) ->
        #incrementing iterator value
        lowerLimit = parseInt $('#commentIterator_' + index).val()
        comments = @commentsPerSong
        iterator = lowerLimit + @commentsPerSong
        $('#commentIterator_' + index).val(iterator)
        

        $.post 'ajax/nextCommentAjax.php',
            lowerLimit: lowerLimit
            upperLimit: iterator
            ytcode: $('#ytcode_' + index).val()
            (data) ->
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
        $('#rankings-title').text(title)

    #============Submit Comment========#
    #what is called when 'Submit Comment' is clicked, updates song in database and if the comments section of song
    #if it is in the rankings
    #params: comment - text of comment, index - song index to insert comment, -1 means its not in the rankings
    submitComment: (comment, user, index = -1) ->
        if comment is '' then alert 'Please enter a comment'
        else if user is '' then alert 'Please enter a username to comment'
        else #submit comment
            if index != -1 #if its a song in the rankings
                ytcode = $('#ytcode_' + index).val()
            else
                ytcode = $('#upload_yturl').val()

            #todo: if its an uploaded song
            $.post 'ajax/commentAjax.php',
                    user: encodeURIComponent(user)
                    comment: encodeURIComponent(comment)
                    ytcode: ytcode
                    (data) ->
                        if index != -1
                            if not $('#max_' + index).find('.no-comment').hasClass('hidden')
                                $('#max_' + index).find('.no-comment').addClass('hidden')

                            $('#max_' + index).find('.comment-display').prepend(data)

                        #setting all usernames to whichever username was just used to comment
                        $('.song-max').find('.comment-user').val(user)



    #refreshes the title for the rankings based off the filters applied
    refreshTitle: ->
        if this.filt('genre') is 'all' then genre = 'Tracks' else genre=this.filt('genre')
        if genre is 'Tracks' and this.filt('time') is 'new' #the fresh list
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
        this.enableMoreSongsButton()
        for i in [startIndex..endIndex]
            #========Comments=====#
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

    
    #when the window is resized, resize the maxQ
    $(window).resize ->
        player.resizeMaxQueue()
    
    #makings shit sortable
    $('#genQ').sortable({
        update: (event, ui) ->
            queue.genQ.setCurrentSong()
            queue.updateMinQueue()
            #connectWith: $('#userQ')
    })
    $('#userQ').sortable({
        update: (event, ui) ->
            window.queue.userQ.updateSongCookies()
            window.queue.userQ.refresh()
            window.queue.updateMinQueue()
    })
        

    #=======lightbox logic========
    $('#about-link').click ->
        $('.lightbox-div').addClass('hidden') #hiding all other lightbox content
        $('#about').removeClass('hidden') #showing about
    $('#contact-link').click ->
        $('.lightbox-div').addClass('hidden') #hiding all other lightbox content
        $('#contact').removeClass('hidden') #showing contact
    $('#upload').click ->
        $('.lightbox-div').addClass('hidden') #hiding all other lightbox content
        $('#upload_box').removeClass('hidden') #showing contact

    #=====contact =====
    $('#contact-submit').click ->
        $.post 'ajax/sendEmail.php',
                email: $('#contact-email').val()
                name: $('#contact-name').val()
                message: $('#contact-message').val()
                (data) ->
                    $('#contact-results').html(data)
                    #erasing old results
                    $('#contact-email').val('')
                    $('#contact-name').val('')
                    $('#contact-message').val('')


    #either add to queue or play song, whichever was pressed
    #when the play-button is clicked in a maxed song, loads the song in the player
    $(document).on 'click', '.song-button', ->
        i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
        if $(this).hasClass('play-button')
            queue.playSong('genQ', i, true)
        else if $(this).hasClass('queue-button')
            queue.userQ.append(i)

    #=============Show/Hide ads============
    $(document).on 'click', '#hide-ads', ->
        $('.ads').slideUp('slow')
        $('#adsPlease').html('Ads help support t3k.no\'s developlement. Help t3k.no stay fast, <span class="ads-button" id="show-ads">click here</span> to enable ads.')
        $('#adBlock').animate({height:30}, 'slow')
        
    $(document).on 'click', '#show-ads', ->
        $('.ads').show('slow')
        $('#adBlock').animate({height:300}, 'slow')
        $('.ads').slideDown('slow')
        $('#adsPlease').html('Ads help support t3k.no\'s development. If it <i>really</i> bothers you, <span class="ads-button" id="hide-ads">click here</span> to hide the ads.')

    
    #=============Delete Song Button=============
    $(document).on 'hover' , '.user-queue', -> 
        $(this).find('.delete-song').toggleClass('hidden')
            
        
    $(document).on 'click', '.delete-song', (event) ->
        i = $(this).closest('.queue-item').attr('id').split('_')[1] #index of song clicked
        queue.userQ.delete(i)
        event.stopPropagation() #song should not be played after its deleted

    #=============Song Controls=============
    $(document).on 'click', '.next-song', ->
        id = $('#min-queue').find('.queue-item:first-child').attr('id') #next songs id

        i = id.split('_')[1] #index of song clicked
        q = id.split('_')[0] #queue that was clicked
        queue.playSong(q, i, true)

    $(document).on 'click', '.previous-song', ->
        player.previousSong()


    #when a song in the queue is clicked, highlight it and play
    $(document).on 'click', '.queue-item', ->
        if $(this).hasClass('selected-song') #current song playing
            window.player.playOrPause()
        else
            i = $(this).attr('id').split('_')[1]
            q = $(this).attr('id').split('_')[0] #queue that was clicked
            queue.playSong(q, i)

    #=============changing the rankings via filter=============
    $('.filter').click ->
        #highlighting filter on click
        if $(this).hasClass('genre-filter')
            rankings.filters.set('genre', $(this).html().toLowerCase())
        else   
            rankings.filters.set('time', $(this).html().toLowerCase())
        rankings.update()

    #when the logo is clicked, go to the fresh list
    $('#header-logo').click ->
        $('#fresh-list').click()
    #when the fresh list link is clicked on the top navigation
    $('#fresh-list').click ->
        rankings.filters.set('time', 'new')
        rankings.filters.set('genre', 'all')
        rankings.update()

    #=============SHUFFLING THE QUEUE============
    $('.shuffle').click ->
        queue = $(this).closest('.queue').attr('id')
        $('#' + queue + ' li').shuffle()
        if queue is 'user-queue' 
            window.queue.userQ.updateSongCookies()
            window.queue.userQ.refresh()
        else #queue is 'generated-queue'
            window.queue.genQ.setCurrentSong()
        window.queue.updateMinQueue()

    #=============to maximize and minimize songs in the rankings=============
    $(document).on 'click', '.song', ->
        #getting index and song state
        temp = $(this).attr('id').split('_')
        i = temp[1] #index of song in rankings
        state = temp[0] #either min or max
        if state is 'min'
            #if there is another maxed song, hide it
            if rankings.maxed_song != -1
                $('#max_' + rankings.maxed_song).css('display', 'none')
                $('#min_' + rankings.maxed_song).removeClass('hidden')

            #max clicked song
            $('#min_' + i).addClass('hidden')
            $('#max_' + i).show('slow')
            rankings.maxed_song = i #set clicked song to new maxed song

    #=====queue-controls===
    $(document).on 'click', '.queue-min', ->
        $('#max-queue').hide 400
        $('#queue-max').html("[Show Queue]")
        $('.content').removeClass 'queue-open'

    $(document).on 'click', '#queue-max', ->
        if $('#max-queue').css('display') is 'none'
            $('.content:not(#bottom-contents)').addClass 'queue-open'
            $('#max-queue').slideDown(400)
            $('#max-queue').removeClass('hidden')
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
                    queue.genQ.refresh(false) #false so that it doesn't reset genQ.curSong 
                    queue.updateMinQueue()

    #===========Submit Comment=====================#
    $(document).on 'click', '.submit-comment', ->
        i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
        rankings.submitComment($('#max_' + i).find('.comment-text').val() , 
                               $('#max_' + i).find('.comment-user').val() ,
                               i)

    #===========See More Comments====================#
    $(document).on 'click', '.see-more-comments', ->
        rankings.nextComments $(this).closest('.song').attr('id').split('_')[1] #index of song clicked

        
    #===========Voting Buttons=====================#
    #todo: this only works for voting buttons in the rankings, not in the player
    $(document).on 'click', '.vote-button', ->
        if not $(this).hasClass('highlight-vote') #if I haven't alredy made the same vote
            if $(this).attr('id').indexOf('player') is -1 #vote button is from rankings
                i = $(this).closest('.song').attr('id').split('_')[1] #index of song clicked
                if $(this).attr('id') is 'up-vote' #up-vote
                    result = 'up'
                    toAdd = 1; #what to add to
                    $('#max_' + i).find("#up-vote").addClass('highlight-vote') #highlighting new vote
                    $('#max_' + i).find("#down-vote").removeClass('highlight-vote')#remove highlight from old vote userScore
                else if $(this).attr('id') is 'down-vote' #down-vote
                    result = 'down'
                    toAdd = -1; #what to add to userScore
                    $('#max_' + i).find("#down-vote").addClass('highlight-vote')#highlight new vote
                    $('#max_' + i).find("#up-vote").removeClass('highlight-vote')#remove highlight from old vote
                else
                    result = 'error'
                player = false #vote button was not in player
                ytcode = $('#ytcode_' + i).val()
                user = $('#user_' + i).val()
                score = $('#score_' + i).val()
                ups = $('#ups_' + i).val()
                downs = $('#downs_' + i).val()
            else #vote-button is in the player
                if $(this).attr('id').indexOf('up-vote') is -1 #downvote clicked
                    result = 'down'
                    toAdd = -1
                else #upvote clicked
                    result = 'up'
                    toAdd = 1
                player = true #boolean to say vote button was in player
                ytcode = window.currentSong.ytcode
                user = window.currentSong.user
                score = window.currentSong.score
                ups = window.currentSong.ups
                downs = window.currentSong.downs

            $.post 'ajax/voteAjax.php',
                    result: result
                    ytcode: ytcode
                    user: user
                    score: score
                    ups: ups
                    downs: downs
                    player: player
                    (data) ->
                        if player
                            $('#bottomControls').find('.score-container').html(data)#updating score in player
                            newScore = parseInt(window.currentSong.userScore) + toAdd
                            $('#currentSongUser').html (window.currentSong.user + ' {<span class="user-score">' + newScore + '</span>}')
                        else
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
                    
    #finally, click the first song so the player embedding doesn't get messed up
    $('#min_1').click()

