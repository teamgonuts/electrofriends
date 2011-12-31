window.Filters = class Filters
    constructor: (@genre = "all", @time = "new", @artist="", @user="") ->

    isSet: (filter) ->
        @filt(filter)?.length > 0

    #returns the appropriate filter value
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

    #highlights the appropriate filter and sets the correct filter
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
            
    #highlights whatever filters are set        
    highlight:  ->
    #Highlighting genre
        if @filters.genre is 'all'
            $('#filter-all').addClass('highlight-filter')
        else if @filters.genre is 'dnb'
            $('#filter-dnb').addClass('highlight-filter')
        else if @filters.genre is 'dubstep'
            $('#filter-dubstep').addClass('highlight-filter')
        else if @filters.genre is 'electro'
            $('#filter-electro').addClass('highlight-filter')
        else if @filters.genre is 'hardstyle'
            $('#filter-hardstyle').addClass('highlight-filter')
        else if @filters.genre is 'house'
            $('#filter-house').addClass('highlight-filter')
        else if @filters.genre is 'trance'
            $('#filter-trance').addClass('highlight-filter')
    #Highlight top of
        if @filters.time is 'day'
            $('#filter-day').addClass('highlight-filter')
        else if @filters.time is 'week'
            $('#filter-week').addClass('highlight-filter')
        else if @filters.time is 'month'
            $('#filter-month').addClass('highlight-filter')
        else if @filters.time is 'year'
            $('#filter-year').addClass('highlight-filter')
        else if @filters.time is 'century'
            $('#filter-century').addClass('highlight-filter')


window.Rankings = class Rankings
    constructor: ->
        @filters = new Filters


    #filt("genre") = "all" == @genre = all
    filt: (filter) ->
        @filters.filt(filter)

    
            
$ ->
    rankings = new Rankings
    rankings.filters.set('genre', 'dubstep')
    $('.filter').click ->
        if $(this).hasClass('genre-filter')
            rankings.filters.set('genre', $(this).html().toLowerCase())
        else   
            rankings.filters.set('time', $(this).html().toLowerCase())
