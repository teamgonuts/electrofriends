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

zeUbertest: ->
    alert 'bitch'

window.Rankings = class Rankings
    constructor: ->
        @filters = new Filters
        @maxed_song = -1 #song currently maximized in rankings, -1 for no maxed songs
        @songsPerPage = $('tr.song-min').length #default songsPerPage, set this in Rankings.php
        ###=================================
        #special flag to tell ajax what is currently displayed in rankings
        Possible values:
          'search' - if rankings currently display search results
          'user' - if rankings currently display a specific user's songs
          'normal' - if rankings display a typical genre/time rankings
        ###
        @flag = 'normal' 
        this.enableMoreSongsButton()

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

    ###searches database for songs with 'searchTerm' in either the title or artist 
    and displays results in rankings###
    search: (searchTerm) ->
        upperLimit = @songsPerPage #creating a local variable for songsPerPage so it can be accessed within the Ajax
        if searchTerm.length is 0
            alert 'Please Enter a Search Term'
        else
            console.log 'Search: ' + searchTerm
            this.changeTitle ('Searching: ' + searchTerm)
            @flag = 'search' 

            $.post 'ajax/searchAjax.php',
                    searchTerm: searchTerm
                    upperLimit: upperLimit
                    (data) ->
                        $('#rankings-table').html(data) 
                        #Pseudo enableMoreSongsButton because I can't call a function from here?
                        if $('tr.song-min').length > 0 and $('tr.song-min').length % upperLimit is 0 #more songs to show
                            $('#showMoreSongs').removeClass('hidden')
                        else
                            $('#showMoreSongs').addClass('hidden')



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
                    rankings.enableMoreSongsButton()
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
        else #state is 'max'
            $('#min_' + i).removeClass('hidden')
            $('#max_' + i).addClass('hidden')
            rankings.maxed_song = -1 #there are no more songs maxed

    #===========showMoreSongs=====================#
    $(document).on 'click', '#showMoreSongs', ->
        $.post 'ajax/showMoreSongsAjax.php',
                genrefilter: rankings.filt('genre')
                timefilter: rankings.filt('time')
                searchTerm: $('#search-input').val() 
                flag: rankings.flag
                lowerLimit: $('tr.song-min').length
                upperLimit: $('tr.song-min').length + rankings.songsPerPage
                (data) ->
                    $('#rankings-table').append(data) 
                    rankings.enableMoreSongsButton()

    #===========Search=====================#
    $('#search-button').click -> rankings.search($('#search-input').val())

    #===========Upload Song=====================#
    $('#upload_song').click -> 
        console.log('Upload_Song Clicked')
        console.log ('upload_yturl: ' +  $('#upload_yturl').val())
        console.log ('oldie: ' +  $('#upload_oldie').attr('checked'))
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
                    


