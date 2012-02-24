(function() {

  $(function() {
    $('#songs2').sortable({
      update: function(event, ui) {
        return console.log('2nd list');
      },
      connectWith: '#songs',
      receive: function(event, ui) {
        return console.log('receiving li');
      }
    });
    return $('#songs').sortable({
      update: function(event, ui) {
        return console.log('1st list updated');
      },
      receive: function(event, ui) {
        return console.log('1st list received li');
      }
    });
    /*$('.song').draggable( {
        containment: '#songs',
        cursor: 'move',
        helper: 'clone'
        drag: dragHelper
      })
    */
  });

}).call(this);
