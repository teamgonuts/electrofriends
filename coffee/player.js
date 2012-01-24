(function() {
  var Player;

  window.Player = Player = (function() {

    function Player() {
      this.initializePlayer();
    }

    Player.prototype.initializePlayer = function() {
      var debug;
      debug = true;
      if (debug) console.log('Player.initializePlayer()');
      return this.loadSong(1);
    };

    Player.prototype.loadSong = function(i) {
      $('#currentSongTitle').html($('#title_' + i).val());
      $('#currentSongArtist').html($('#artist_' + i).val());
      $('#currentSongGenre').html($('#genre_' + i).val());
      return $('#currentSongUser').html($('#user_' + i).val());
    };

    return Player;

  })();

  $(function() {
    var ytplayer;
    return ytplayer = new Player;
  });

}).call(this);
