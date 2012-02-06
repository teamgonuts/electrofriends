(function() {
  var Player;

  window.Player = Player = (function() {

    function Player() {
      this.initializePlayer();
    }

    Player.prototype.initializePlayer = function() {
      var debug, params;
      debug = false;
      if (debug) console.log('Player.initializePlayer()');
      params = {
        allowScriptAccess: "always"
      };
      swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() + "&enablejsapi=1&playerapiid=ytplayer" + "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1", "ytplayer", "275", "90", "8", null, null, params);
      return this.loadSong(1);
    };

    Player.prototype.loadSong = function(i) {
      console.log('loadSong Called()');
      $('#currentSongTitle').html($('#title_' + i).val());
      $('#currentSongArtist').html($('#artist_' + i).val());
      $('#currentSongGenre').html($('#genre_' + i).val());
      return $('#currentSongUser').html($('#user_' + i).val());
    };

    return Player;

  })();

  window.onYouTubePlayerReady = function(playerid) {
    var debug, ytplayer;
    debug = false;
    if (debug) console.log('player ready');
    ytplayer = document.getElementById(playerid);
    ytplayer.addEventListener("onStateChange", "stateChange");
    return ytplayer.addEventListener("onError", "onPlayerError");
  };

  window.stateChange = function(newState) {
    var debug;
    debug = false;
    if (debug) console.log('Player State Change: ' + newState);
    switch (newState) {
      case 0:
        if (debug) return console.log('Song Ended');
        break;
      case 1:
        if (debug) console.log('Song Playing');
        return document.title = $('#currentSongTitle').html() + ' by ' + $('#currentSongArtist').html();
      case 2:
        if (debug) console.log('Song Paused');
        return document.title = 'Paused - T3K.NO';
      case 3:
        if (debug) console.log('Song Loading');
        return document.title = 'Loading Song - T3K.NO';
    }
  };

  window.onPlayerError = function(errorCode) {
    var debug;
    debug = true;
    if (debug) return console.log('onPlayerError() called!');
  };

  $(function() {
    var ytplayer;
    ytplayer = new Player;
    return $('.filter').click(function() {
      console.log('filter clicked');
      return ytplayer.loadSong(1);
    });
  });

}).call(this);
