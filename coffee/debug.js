(function() {

  jQuery(function() {
    var filter, result;
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
    return $('#rankings').append('<li>Filters.isSet("fail"):<b> ' + result + '</b></li>');
  });

}).call(this);
