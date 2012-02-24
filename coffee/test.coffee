$ ->
    console.log 'sortable'
    $('#songs').sortable({
        start: startHelper
        update: stopHelper
    })

    startHelper = ->
        console.log 'startHelper'
    stopHelper = ->
        console.log 'stopHelper'
    
    ###$('.song').draggable( {
        containment: '#songs',
        cursor: 'move',
        helper: 'clone'
        drag: dragHelper
      })
    ###
