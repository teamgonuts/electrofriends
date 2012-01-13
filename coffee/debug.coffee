

jQuery ->
    #Rankings
    filter = new Filters
    if filter.isSet('genre') then result='Success' else result='Fail'
    $('#rankings').append('<li>Filters.isSet("genre"):<b> ' + result + '</b></li>')
    if filter.isSet('user') then result='Fail' else result='Success'
    $('#rankings').append('<li>Filters.isSet("user"):<b> ' + result + '</b></li>')
    if filter.isSet('weird') then result='Fail' else result='Success'
    $('#rankings').append('<li>Filters.isSet("Fail"):<b> ' + result + '</b></li>')
    #sets
    filter.set('genre', 'dnb')
    if filter.filt('genre') is 'dnb' then result='Success' else result='Fail'
    $('#rankings').append('<li>Filters.set("genre","dnb"):<b> ' + result + '</b></li>')
    
    rankings = new Rankings
    if rankings.filters.genre is 'all' then result='Success' else result='Fail'
    $('#rankings').append('<li>Rankings.filters.genre: <b> ' + result + '</b></li>')
    if rankings.filt('genre') is 'all' then result='Success' else result='Fail'
    $("#rankings").append('<li>Rankings.filt("genre"): <b> ' + result + '</b></li>')
    if rankings.maxed_song is -1 then result ='Success' else result='Fail'
    $("#rankings").append('<li>Rankings.maxed_song: <b> ' + result + '</b></li>')
    rankings.maxed_song = 4 #simulating new max song
    if rankings.maxed_song != -1 then result ='Success' else result='Fail'
    $("#rankings").append('<li>Rankings.maxed_song = 4: <b> ' + result + '</b></li>')
