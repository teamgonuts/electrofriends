(function() {
  var Player;

  window.Player = Player = (function() {

    function Player() {
      this.initializePlayer();
    }

    Player.prototype.initializePlayer = function() {
      var debug;
      debug = false;
      if (debug) console.log('Player.initializePlayer()');
      return this.loadSong(1);
    };

    Player.prototype.loadSong = function(i) {
      $('#currentSongTitle').html($('#title_' + i).val());
      $('#currentSongArtist').html($('#artist_' + i).val());
      $('#currentSongGenre').html($('#genre_' + i).val());
      return $('#currentSongUser').html($('#user_' + i).val());
    };

    Player.prototype.onYouTubePlayerReady = function(playerID) {
      var ytplayer;
      ytplayer = document.getElementById(playerID);
      ytplayer.addEventListener("onStateChange", "stateChange");
      return ytplayer.addEventListener("onError", "onPlayerError");
    };

    Player.prototype.stateChange = function(newState) {
      var debug;
      debug = true;
      if (debug) return console.log('New Player State: ' + newState);
    };

    Player.prototype.onPlayerError = function(error) {
      var debug;
      debug = true;
      if (debug) return console.log('Error Code: ' + error);
    };

    return Player;

  })();

  $(function() {
    var ytplayer;
    ytplayer = new Player;
    return {
      onYouTubePlayerReady: function(playerID) {
        var debug;
        debug = true;
        if (debug) console.log('onYouTubePlayerReady( ' + playerID + ')');
        ytplayer = document.getElementById(playerID);
        ytplayer.addEventListener("onStateChange", "stateChange");
        return ytplayer.addEventListener("onError", "onPlayerError");
      }
    };
  });

}).call(this);
