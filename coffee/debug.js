(function() {

  jQuery(function() {
    var filter, rankings, result;
    filter = new Filters;
    if (filter.isSet('genre')) {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $('#rankings').append('<li>Filters.isSet("genre"):<b> ' + result + '</b></li>');
    if (filter.isSet('user')) {
      result = 'Fail';
    } else {
      result = 'Success';
    }
    $('#rankings').append('<li>Filters.isSet("user"):<b> ' + result + '</b></li>');
    if (filter.isSet('weird')) {
      result = 'Fail';
    } else {
      result = 'Success';
    }
    $('#rankings').append('<li>Filters.isSet("Fail"):<b> ' + result + '</b></li>');
    filter.set('genre', 'dnb');
    if (filter.filt('genre') === 'dnb') {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $('#rankings').append('<li>Filters.set("genre","dnb"):<b> ' + result + '</b></li>');
    rankings = new Rankings;
    if (rankings.filters.genre === 'all') {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $('#rankings').append('<li>Rankings.filters.genre: <b> ' + result + '</b></li>');
    if (rankings.filt('genre') === 'all') {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $("#rankings").append('<li>Rankings.filt("genre"): <b> ' + result + '</b></li>');
    if (rankings.maxed_song === -1) {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $("#rankings").append('<li>Rankings.maxed_song: <b> ' + result + '</b></li>');
    rankings.maxed_song = 4;
    if (rankings.maxed_song !== -1) {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    $("#rankings").append('<li>Rankings.maxed_song = 4: <b> ' + result + '</b></li>');
    if (rankings.enableMoreSongsButton() === true) {
      result = 'Success';
    } else {
      result = 'Fail';
    }
    return $("#rankings").append('<li>Rankings.enableMoreSongsButton =  true: <b> ' + result + '</b></li>');
  });

}).call(this);
