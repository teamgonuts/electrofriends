window.Filters = class Filters
    constructor: (@genre = "all", @time = "new", @artist="", @user="") ->

    isSet: (filter) ->
        if filter is "genre" 
            @genre.length > 0
        else if filter is "time" 
            @time.length > 0
        else if filter is "artist" 
            @artist.length > 0
        else if filter is "user" 
            @user.length > 0
        else
            false
