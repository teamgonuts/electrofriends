#POST THE FOLLOWING DATA TO http://www.vidtomp3.com/process.php
#url=http%3A%2F%2Fwww.vidtomp3.com%2Fmiddle.php%3Fv%3DmhAg0COnqds&x=30&y=26&quality=0Ii
#                                                     :^ytcide   ^
$ ->
    $('#songs2').sortable({
        update: (event,ui) ->
            console.log '2nd list'
        connectWith: '#songs'
        receive: (event, ui) ->
            console.log 'receiving li'
    })
    
        

    $('#songs').sortable({
        update: (event,ui) ->
            console.log '1st list updated'
        receive: (event,ui) ->
            console.log '1st list received li'
    })
    

    
    ###$('.song').draggable( {
        containment: '#songs',
        cursor: 'move',
        helper: 'clone'
        drag: dragHelper
      })
    ###
