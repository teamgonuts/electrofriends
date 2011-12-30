(function() {
  var Filters;

  window.Filters = Filters = (function() {

    function Filters(genre, time, artist, user) {
      this.genre = genre != null ? genre : "all";
      this.time = time != null ? time : "new";
      this.artist = artist != null ? artist : "";
      this.user = user != null ? user : "";
      ({
        isSet: function(filter) {
          if (filter === "genre") {
            return this.genre.length > 0;
          } else if (filter === "time") {
            return this.time.length > 0;
          } else if (filter === "artist") {
            return this.artist.length > 0;
          } else if (filter === "user") {
            return this.user.length > 0;
          } else {
            return false;
          }
        }
      });
    }

    return Filters;

  })();

}).call(this);
