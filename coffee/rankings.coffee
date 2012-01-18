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
        this.enableMoreSongsButton()

    #filt("genre") = "all" is the same as @genre = all
    filt: (filter) ->
        @filters.filt(filter)

    ###checks to see if there should be a showMoreSongs button at the bottom of the rankings
    if there is, show the button and return true, else don't show buton and return false###
    enableMoreSongsButton: ->
        #console.log ('tr.song-min.length: ' + $('tr.song-min').length)
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
            $('#rankings-title').text("The Fresh List")
        else
            title = "Top " + genre + " of the " + this.filt('time')
            $('#rankings-title').text(title)

        
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
                lowerLimit: $('tr.song-min').length
                upperLimit: $('tr.song-min').length + rankings.songsPerPage
                (data) ->
                    $('#rankings-table').append(data) 
                    rankings.enableMoreSongsButton()

    #===========Search=====================#
    $('#search-button').click ->
        if $('#search-input').val().length is 0
            alert 'Please Enter a Search Term'
        else
            console.log 'Search: ' + $('#search-input').val() 
            rankings.changeTitle ('Searching For: ' + $('#search-input').val())
            $.post 'ajax/searchAjax.php',
                    searchTerm: $('#search-input').val() 
                    (data) ->
                        $('#rankings-table').html(data) 
                        $('#showMoreSongs').addClass('hidden')                                    
