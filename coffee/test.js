(function() {

  $(function() {
    var startHelper, stopHelper;
    console.log('sortable');
    $('#songs').sortable({
      start: startHelper,
      update: stopHelper
    });
    startHelper = function() {
      return console.log('startHelper');
    };
    return stopHelper = function() {
      return console.log('stopHelper');
    };
    /*$('.song').draggable( {
        containment: '#songs',
        cursor: 'move',
        helper: 'clone'
        drag: dragHelper
      })
    */
  });

}).call(this);
