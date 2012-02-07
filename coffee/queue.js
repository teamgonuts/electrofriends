(function() {
  var Song;

  window.Song = Song = (function() {

    function Song(ytcode, title, genre, artist, user) {
      var debug;
      this.ytcode = ytcode;
      this.title = title;
      this.genre = genre;
      this.artist = artist;
      this.user = user;
      debug = false;
      if (debug) {
        console.log('Song Created! ytcode: ' + this.ytcode + ', title: ' + this.title + ', genre: ' + this.genre + ', artist: ' + this.artist + ', user: ' + this.user);
      }
    }

    Song.prototype.play = function() {
      var debug, ytplayer;
      debug = false;
      if (debug) console.log(this.title + '.play() called!');
      ytplayer = document.getElementById('ytplayer');
      ytplayer.loadVideoById(this.ytcode);
      $('#currentSongTitle').html(this.title);
      $('#currentSongArtist').html(this.artist);
      $('#currentSongGenre').html(this.genre);
      return $('#currentSongUser').html(this.user);
    };

    return Song;

  })();

}).call(this);
