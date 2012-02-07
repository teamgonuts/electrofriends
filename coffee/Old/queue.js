(function() {
  var GeneratedQueue, Song;

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

  window.GeneratedQueue = GeneratedQueue = (function() {

    function GeneratedQueue() {
      this.refresh();
    }

    GeneratedQueue.prototype.refresh = function() {
      var i, _ref, _results;
      this.clear();
      _results = [];
      for (i = 1, _ref = $('.song-max').length; 1 <= _ref ? i <= _ref : i >= _ref; 1 <= _ref ? i++ : i--) {
        _results.push($('#gen-queue').append(' <li class="queue-item" id="gen-queue-' + i + '"><span class="title"> ' + $('#title_' + i).val() + '</span><span class="purple">//</span> ' + $('#artist_' + i).val() + '</li>'));
      }
      return _results;
    };

    GeneratedQueue.prototype.clear = function() {
      var debug;
      debug = true;
      if (debug) console.log('GenQueue.clear() called!');
      return $('#gen-queue').html('');
    };

    return GeneratedQueue;

  })();

  $(function() {
    var genQ;
    genQ = new GeneratedQueue;
    return $(document).on('click', '.filter', function() {
      return genQ.refresh();
    });
  });

}).call(this);
