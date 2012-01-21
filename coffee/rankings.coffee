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
    initializeSongs:(startIndex, endIndex) ->
#=================NOTE: When you change this method you also have to change the ajax in Rankings.search()
        this.enableMoreSongsButton()
        console.log ('Rankings.initializeComments(' + startIndex + ',' + endIndex + ') called')
        for i in [startIndex..endIndex]
            #========Comments=====#
            if $('#max_' + i).find('.comment-p').length is 0 #no comment
                $('#max_' + i).find('.comment-display').html('<p class="no-comment">No Comments. </p>')

            if $('#max_' + i).find('.comment-p').length < @commentsPerSong #no show-more-comments button
                $('#max_' + i).find('.see-more-comments').addClass('hidden')

    ###searches database for songs with 'searchterm' in either the title or artist 
    and displays results in rankings###
    search: (searchterm) ->
        upperlimit = @songsPerPage #creating a local variable for songsperpage so it can be accessed within the ajax
        commentsPerSong = @commentsPerSong
        searchterm = searchterm.trim()
        if searchterm.length is 0
            alert 'please enter a search term'
        else
            console.log 'search:' + searchterm
            this.changeTitle ('searching: ' + searchterm)
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



$ ->
    rankings = new Rankings

    #=============changing the rankings via filter=============
    $('.filter').click ->
        #highlighting filter on click
        if $(this).hasClass('genre-filter')
            rankings.filters.set('genre', $(this).html().toLowerCase())
        else   
            rankings.filters.set('time', $(this).html().toLowerCase())

        #todo: add loading screen to rankings here
        #ajax post
        $.post 'ajax/rankingsAjax.php',
                genrefilter: rankings.filt('genre')
                timefilter: rankings.filt('time')
                (data) ->
                    $('#rankings-table').html(data) 
                    rankings.initialize()
                    rankings.refreshTitle()
                    rankings.flag = 'normal' #resetting flag

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
    $(document).on 'click', '#queue-max', ->
        $('#max-queue').removeClass 'hidden'

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

    #===========Voting Buttons=====================#
#todo: this only works for voting buttons in the rankings, not in the player
    $(document).on 'click', '.vote-button', ->
        debug = true #turn on debugging for voting buttons
        if not $(this).hasClass('highlight-vote') #if I haven't alredy made the same vote
            temp = $(this).closest('.song').attr('id').split('_')
            i = temp[1] #index of song in rankings

            if $(this).attr('id') is 'up-vote' #up-vote
                if debug then console.log 'UpVote called on ' + i
                result = 'up'
                $('#max_' + i).find("#up-vote").addClass('highlight-vote') #highlighting new vote
                $('#max_' + i).find("#down-vote").removeClass('highlight-vote')#remove highlight from old vote
            else if $(this).attr('id') is 'down-vote' #down-vote
                if debug then console.log 'DownVote called on ' + i
                result = 'down'
                $('#max_' + i).find("#down-vote").addClass('highlight-vote')#highlight new vote
                $('#max_' + i).find("#up-vote").removeClass('highlight-vote')#remove highlight from old vote
            else
                if debug then console.log 'Error: Something went wrong with the vote-buttons'
                result = 'error'

            $.post 'ajax/voteAjax.php',
                    result: result
                    ytcode: $('#ytcode_' + i).val()
                    score: $('#score_' + i).val()
                    ups: $('#ups_' + i).val()
                    downs: $('#downs_' + i).val()
                    (data) ->
                        if debug then console.log 'Vote Success: ' + data
                        $('#max_' + i).find('.score-container').html(data) #changing score in max
                        $('#min_' + i).find('.score-container').html(data) #changing score in min
                    

    #===========Search=====================#
    $('#search-button').click -> rankings.search($('#search-input').val())
    $(document).on 'click', '.search-filter', -> rankings.search($(this).html())

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
                    else #upload failed
                        $('#upload-box-result').css('color', 'red')
                    


