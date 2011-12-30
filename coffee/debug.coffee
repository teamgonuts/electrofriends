


jQuery ->
    filter = new Filters
    #Rankings 
    if filter.isSet('genre') then result='Success' else result='Fail'
    $('#rankings').append('<li>Filters.isSet("genre"):<b> ' + result + '</b></li>')
    if filter.isSet('user') then result='Fail' else result='Success'
    $('#rankings').append('<li>Filters.isSet("user"):<b> ' + result + '</b></li>')
    if filter.isSet('weird') then result='Fail' else result='Success'
    $('#rankings').append('<li>Filters.isSet("fail"):<b> ' + result + '</b></li>')


