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
