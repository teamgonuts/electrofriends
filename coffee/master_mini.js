
/*=================================================
if debug then console.log 'delete-song clicked'
----------------Youtube Player----------------------
=================================================
*/

(function() {
  var Filters, GeneratedQueue, Player, Queue, Rankings, Song, UserQueue;

  window.Player = Player = (function() {

    function Player() {
      this.initializePlayer();
      this.previousSongs = new Array();
      this.songsToRemember = 10;
    }

    Player.prototype.initializePlayer = function() {
      var params;
      params = {
        allowScriptAccess: "always"
      };
      swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() + "&enablejsapi=1&playerapiid=ytplayer" + "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1", "ytplayer", "200", "90", "8", null, null, params);
      this.loadSongInRankings(1);
      return this.resizeMaxQueue();
    };

    Player.prototype.refreshPlayer = function() {
      var params;
      $('.next-song').click();
      params = {
        allowScriptAccess: "always"
      };
      swfobject.embedSWF("http://www.youtube.com/v/" + window.currentSong.ytcode + "&enablejsapi=1&playerapiid=ytplayer" + "&hd=1&iv_load_policy=3&rel=0&showinfo=0&autohide=1&autoplay=1", "ytplayer", "200", "90", "8", null, null, params);
      return this.updateCurrentSongInfo();
    };

    Player.prototype.resizeMaxQueue = function() {
      var nh;
      nh = $(window).height() - 12 - $('#bottomControls').height();
      return $('#max-queue').height(nh);
    };

    Player.prototype.loadSongInRankings = function(i) {
      window.currentSong = new Song($('#ytcode_' + i).val(), $('#title_' + i).val(), $('#genre_' + i).val(), $('#artist_' + i).val(), $('#user_' + i).val(), $('#userScore_' + i).val(), $('#score_' + i).val(), $('#ups_' + i).val(), $('#downs_' + i).val());
      return this.updateCurrentSongInfo();
    };

    Player.prototype.loadSongInQueue = function(i) {
      window.currentSong = new Song(window.queue.userQ.songs[i].ytcode, window.queue.userQ.songs[i].title, window.queue.userQ.songs[i].genre, window.queue.userQ.songs[i].artist, window.queue.userQ.songs[i].user, window.queue.userQ.songs[i].userScore, window.queue.userQ.songs[i].score, window.queue.userQ.songs[i].ups, window.queue.userQ.songs[i].downs);
      return this.updateCurrentSongInfo();
    };

    Player.prototype.addToHistory = function(song) {
      this.previousSongs.push(song);
      if (this.previousSongs.length > this.songsToRemember) {
        return this.previousSongs.shift();
      }
    };

    Player.prototype.previousSong = function() {
      var ytplayer;
      if (this.previousSongs.length > 0) {
        window.currentSong = this.previousSongs.pop();
        this.updateCurrentSongInfo();
        ytplayer = document.getElementById('ytplayer');
        return ytplayer.loadVideoById(currentSong.ytcode);
      }
    };

    Player.prototype.playOrPause = function() {
      var state, ytplayer;
      ytplayer = document.getElementById('ytplayer');
      state = ytplayer.getPlayerState();
      if (state === 1) {
        return ytplayer.pauseVideo();
      } else {
        return ytplayer.playVideo();
      }
    };

    Player.prototype.updateCurrentSongInfo = function() {
      $('#currentSongTitle').html(window.currentSong.title);
      $('#currentSongArtist').html(window.currentSong.artist);
      $('#currentSongGenre').html(window.currentSong.genre);
      $('#currentSongUser').html(window.currentSong.user + ' {<span class="user-score">' + window.currentSong.userScore + '</span>}');
      $('#currentSongScore').html(window.currentSong.score);
      $('#currentSongUps').html(window.currentSong.ups);
      return $('#currentSongDowns').html(window.currentSong.downs);
    };

    return Player;

  })();

  window.onYouTubePlayerReady = function(playerid) {
    var ytplayer;
    ytplayer = document.getElementById(playerid);
    ytplayer.addEventListener("onStateChange", "stateChange");
    return ytplayer.addEventListener("onError", "onPlayerError");
  };

  window.stateChange = function(newState) {
    switch (newState) {
      case 0:
        $('.next-song').click();
        break;
      case 1:
        incrementPlayCount();
        return document.title = $('#currentSongTitle').html() + ' by ' + $('#currentSongArtist').html();
      case 2:
        return document.title = 'Paused - t3k.no';
      case 3:
        return document.title = 'Loading Song - t3k.no';
    }
  };

  window.incrementPlayCount = function() {
    var ampersandPosition, video_id, ytcode, ytplayer;
    ytplayer = document.getElementById('ytplayer');
    video_id = ytplayer.getVideoUrl().split('v=')[1];
    ampersandPosition = video_id.indexOf('&');
    if (ampersandPosition !== -1) {
      ytcode = video_id.substring(0, ampersandPosition);
    }
    if (!window.currentSong.played) {
      window.currentSong.played = true;
      return $.post('ajax/incrementPlayCount.php', {
        ytcode: ytcode
      }, function(data) {});
    }
  };

  window.onPlayerError = function(errorCode) {
    player.refreshPlayer();
  };

  /*=================================================
  ---------------------Queue--------------------------
  =================================================
  */

  window.Song = Song = (function() {

    function Song(ytcode, title, genre, artist, user, userScore, score, ups, downs, played) {
      this.ytcode = ytcode;
      this.title = title;
      this.genre = genre;
      this.artist = artist;
      this.user = user;
      this.userScore = userScore;
      this.score = score;
      this.ups = ups;
      this.downs = downs;
      this.played = played != null ? played : false;
    }

    return Song;

  })();

  window.Queue = Queue = (function() {

    function Queue() {
      this.genQ = new GeneratedQueue;
      this.userQ = new UserQueue;
      this.minQ_MaxSongs = 3;
      this.initialize();
    }

    Queue.prototype.initialize = function() {
      this.genQ.refresh();
      return this.updateMinQueue();
    };

    Queue.prototype.updateMinQueue = function(rankingsChange) {
      var i, id, song, songs, _i, _len, _ref, _results;
      if (rankingsChange == null) rankingsChange = false;
      $('#min-queue').html('');
      if ($('#userQ').find('.queue-item').length !== 0) {
        _ref = $('#userQ').find('li');
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          song = _ref[_i];
          if ($('#min-queue').find('.queue-item').length >= this.minQ_MaxSongs) {
            break;
          }
          i = song.id.split('_')[1] - 1;
          if (!this.userQ.songs[i].played) {
            $('#min-queue').append(' <li class="queue-item ellipsis" id="userQ_' + (i + 1) + '_2"><span class="title"> ' + this.userQ.songs[i].title + '</span><span class="purple"> //</span> ' + this.userQ.songs[i].artist);
          }
        }
      }
      if (rankingsChange) {
        i = 1;
      } else {
        i = parseInt(this.genQ.curSong) + 1;
      }
      songs = $('#genQ').find('li');
      _results = [];
      while ($('#genQ_' + i).html() && $('#min-queue').find('.queue-item').length < this.minQ_MaxSongs) {
        id = songs[i - 1].id.split('_')[1];
        $('#min-queue').append(' <li class="queue-item" id="genQ_' + id + '_2"><span class="title"> ' + $('#title_' + id).val() + '</span><span class="purple"> //</span> ' + $('#artist_' + id).val() + '</li>');
        _results.push(i++);
      }
      return _results;
    };

    Queue.prototype.playSong = function(queue, index, rankings) {
      var i, qindex, ytcode, ytplayer;
      if (rankings == null) rankings = false;
      if (queue !== 'genQ' && queue !== 'userQ') {
        console.log('Queue.playSong ERROR: Invalid param queue: ' + queue);
        return;
      }
      if (index < 1 || index > $('#' + queue).find('.queue-item').length) {
        console.log('Queue.playSong ERROR: Index out of bounds: ' + index);
        return;
      }
      $('.queue-item').removeClass('selected-song');
      $('#' + queue + '_' + index).addClass('selected-song');
      window.player.addToHistory(window.currentSong);
      if (queue === 'genQ') {
        ytcode = $('#ytcode_' + index).val();
        window.player.loadSongInRankings(index);
        if (rankings) {
          this.genQ.curSong = index;
        } else {
          this.genQ.curSong = $('#genQ_' + index).index() + 1;
        }
        this.userQ.markAllPlayed();
        if ($('#max_' + index).css('display') === 'none') {
          $('#min_' + index).click();
        }
      } else {
        i = index - 1;
        ytcode = this.userQ.songs[i].ytcode;
        window.player.loadSongInQueue(i);
        this.userQ.songs[i].played = true;
        qindex = $('#userQ_' + index).index();
        this.userQ.markAllNotPlayed(qindex + 1);
      }
      this.updateMinQueue();
      if ($('#min-queue').find('li').length <= 1) $('#showMoreSongs').click();
      ytplayer = document.getElementById('ytplayer');
      return ytplayer.loadVideoById(ytcode);
    };

    return Queue;

  })();

  window.UserQueue = UserQueue = (function() {

    function UserQueue() {
      this.songs = new Array();
      this.getSongCookies();
    }

    UserQueue.prototype.append = function(i) {
      this.songs.push(new Song($('#ytcode_' + i).val(), $('#title_' + i).val(), $('#genre_' + i).val(), $('#artist_' + i).val(), $('#user_' + i).val(), $('#userScore_' + i).val(), $('#score_' + i).val(), $('#ups_' + i).val(), $('#downs_' + i).val()));
      $('#userQ').append(' <li class="queue-item user-queue" id="userQ_' + this.songs.length + '"><div class="hidden delete-song">[x]</div><div class="queue-song"><span class="title"> ' + $('#title_' + i).val() + '</span><br /><span class="purple"> //</span> ' + $('#artist_' + i).val() + '</div>');
      window.queue.updateMinQueue();
      return this.updateSongCookies();
    };

    UserQueue.prototype.updateSongCookies = function() {
      var i, song, songStr, _i, _len, _ref;
      songStr = "";
      _ref = $('#userQ').find('li');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        song = _ref[_i];
        i = parseInt(song.id.split('_')[1]) - 1;
        songStr = songStr + this.songs[i].ytcode + ',';
      }
      songStr = songStr.substr(0, songStr.length - 1);
      return $.post('ajax/setSongCookies.php', {
        ytcodes: songStr
      }, function(data) {});
    };

    UserQueue.prototype.getSongCookies = function() {
      return $.get('ajax/getSongCookies.php', function(data) {
        var i, _ref;
        $('#userQ').html(data);
        if ($('.user-queue').length > 0) {
          for (i = 1, _ref = $('.user-queue').length; 1 <= _ref ? i <= _ref : i >= _ref; 1 <= _ref ? i++ : i--) {
            window.queue.userQ.songs.push(new Song($('#uq_ytcode_' + i).val(), $('#uq_title_' + i).val(), $('#uq_genre_' + i).val(), $('#uq_artist_' + i).val(), $('#uq_user_' + i).val(), $('#uq_userScore_' + i).val(), $('#uq_score_' + i).val(), $('#uq_ups_' + i).val(), $('#uq_downs_' + i).val(), true));
          }
          $('.delete-info').html('');
        }
        return window.queue.updateMinQueue();
      });
    };

    UserQueue.prototype["delete"] = function(i) {
      var song, _i, _len, _ref;
      $('#userQ_' + i).remove();
      this.songs.splice(i - 1, 1);
      i = 1;
      _ref = $('#userQ').children();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        song = _ref[_i];
        $(song).attr('id', 'userQ_' + i);
        i++;
      }
      this.updateSongCookies();
      return window.queue.updateMinQueue();
    };

    UserQueue.prototype.clear = function() {
      return this.initialize();
    };

    UserQueue.prototype.markAllPlayed = function() {
      var song, _i, _len, _ref, _results;
      _ref = this.songs;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        song = _ref[_i];
        _results.push(song.played = true);
      }
      return _results;
    };

    UserQueue.prototype.refresh = function() {
      var index;
      if ($('.selected-song') && $('.selected-song').closest('.queue').attr('id') === 'user-queue') {
        index = $('.selected-song').index() + 1;
        return this.markAllNotPlayed(index);
      }
    };

    UserQueue.prototype.markAllNotPlayed = function(index) {
      var i, qindex, _ref, _results;
      if ($('#userQ').children().length > 1) {
        _results = [];
        for (i = 0, _ref = this.songs.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
          qindex = $('#userQ_' + (i + 1)).index();
          if (i < index) {
            _results.push(this.songs[qindex].played = true);
          } else if (i >= index && i <= this.songs.length - 1) {
            _results.push(this.songs[qindex].played = false);
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      }
    };

    return UserQueue;

  })();

  window.GeneratedQueue = GeneratedQueue = (function() {

    function GeneratedQueue() {
      this.songs = new Array();
      this.curSong = 0;
    }

    GeneratedQueue.prototype.setCurrentSong = function() {
      if ($('.selected-song') && this.curSong > 1) {
        $('.selected-song').closest('queue').attr('id') === 'generated-queue';
        return this.curSong = $('.selected-song').index() + 1;
      }
    };

    GeneratedQueue.prototype.refresh = function(reset) {
      var i, _ref, _results;
      if (reset == null) reset = true;
      this.clear();
      if (reset) this.curSong = 1;
      _results = [];
      for (i = 1, _ref = $('.song-max').length; 1 <= _ref ? i <= _ref : i >= _ref; 1 <= _ref ? i++ : i--) {
        this.songs.push(i);
        _results.push($('#genQ').append(' <li class="queue-item" id="genQ_' + i + '"><span class="title"> ' + $('#title_' + i).val() + '</span><br /><span class="purple"> //</span> ' + $('#artist_' + i).val() + '</li>'));
      }
      return _results;
    };

    GeneratedQueue.prototype.clear = function() {
      return $('#genQ').html('');
    };

    return GeneratedQueue;

  })();

  /*=================================================
  ---------------------Filters--------------------------
  =================================================
  */

  window.Filters = Filters = (function() {

    function Filters(genre, time, artist, user) {
      this.genre = genre != null ? genre : "all";
      this.time = time != null ? time : "new";
      this.artist = artist != null ? artist : "";
      this.user = user != null ? user : "";
      this.highlight();
    }

    Filters.prototype.isSet = function(filter) {
      var _ref;
      return ((_ref = this.filt(filter)) != null ? _ref.length : void 0) > 0;
    };

    Filters.prototype.filt = function(filter) {
      if (filter === "genre") {
        return this.genre;
      } else if (filter === "time") {
        return this.time;
      } else if (filter === "artist") {
        return this.artist;
      } else if (filter === "user") {
        return this.user;
      } else {
        return false;
      }
    };

    Filters.prototype.set = function(filter, value) {
      if (filter === "genre") {
        this.genre = value;
        $('.genre-filter').removeClass('highlight-filter');
        return $('#filter-' + value).addClass('highlight-filter');
      } else if (filter === "time") {
        this.time = value;
        $('.time-filter').removeClass('highlight-filter');
        return $('#filter-' + value).addClass('highlight-filter');
      } else if (filter === "artist") {
        return this.artist = value;
      } else if (filter === "user") {
        return this.user = value;
      } else {
        return false;
      }
    };

    Filters.prototype.highlight = function() {
      if (this.genre === 'all') {
        $('#filter-all').addClass('highlight-filter');
      } else if (this.genre === 'dnb') {
        $('#filter-dnb').addClass('highlight-filter');
      } else if (this.genre === 'dubstep') {
        $('#filter-dubstep').addclass('highlight-filter');
      } else if (this.genre === 'electro') {
        $('#filter-electro').addclass('highlight-filter');
      } else if (this.genre === 'hardstyle') {
        $('#filter-hardstyle').addclass('highlight-filter');
      } else if (this.genre === 'house') {
        $('#filter-house').addclass('highlight-filter');
      } else if (this.genre === 'trance') {
        $('#filter-trance').addclass('highlight-filter');
      }
      if (this.time === 'day') {
        return $('#filter-day').addclass('highlight-filter');
      } else if (this.time === 'week') {
        return $('#filter-week').addclass('highlight-filter');
      } else if (this.time === 'month') {
        return $('#filter-month').addclass('highlight-filter');
      } else if (this.time === 'year') {
        return $('#filter-year').addclass('highlight-filter');
      } else if (this.time === 'century') {
        return $('#filter-century').addclass('highlight-filter');
      }
    };

    return Filters;

  })();

  /*=================================================
  ---------------------Rankings--------------------------
  =================================================
  */

  window.Rankings = Rankings = (function() {

    function Rankings() {
      this.filters = new Filters;
      this.maxed_song = -1;
      this.songsPerPage = $('tr.song-min').length;
      this.commentsPerSong = 5;
      /*=================================
      #special flag to tell ajax what is currently displayed in rankings
      Possible values:
        'search' - if rankings currently display search results
        'user' - if rankings currently display a specific user's songs
        'normal' - if rankings display a typical genre/time rankings
      */
      this.flag = 'normal';
      this.initialize();
    }

    Rankings.prototype.initialize = function() {
      this.initializeSongs(1, this.songsPerPage);
      this.loadUsernameCookie();
      return $('#min_1').click();
    };

    Rankings.prototype.loadUsernameCookie = function() {
      return $.get('ajax/loadUsernameCookie.php', function(data) {
        $('.song-max').find('.comment-user').val(data);
        return $('#upload_user').val(data);
      });
    };

    Rankings.prototype.update = function() {
      this.refreshTitle();
      return $.post('ajax/rankingsAjax.php', {
        genrefilter: rankings.filt('genre'),
        timefilter: rankings.filt('time')
      }, function(data) {
        $('#rankings-table').html(data);
        rankings.initialize();
        rankings.flag = 'normal';
        queue.genQ.refresh();
        return queue.updateMinQueue(true);
      });
    };

    Rankings.prototype.filt = function(filter) {
      return this.filters.filt(filter);
    };

    Rankings.prototype.nextComments = function(index) {
      var comments, iterator, lowerLimit;
      lowerLimit = parseInt($('#commentIterator_' + index).val());
      comments = this.commentsPerSong;
      iterator = lowerLimit + this.commentsPerSong;
      $('#commentIterator_' + index).val(iterator);
      return $.post('ajax/nextCommentAjax.php', {
        lowerLimit: lowerLimit,
        upperLimit: iterator,
        ytcode: $('#ytcode_' + index).val()
      }, function(data) {
        $('#max_' + index).find('.comment-display').html(data);
        if ($('#max_' + index).find('.comment-p').length < comments) {
          return $('#max_' + index).find('.see-more-comments').addClass('hidden');
        }
      });
    };

    /*checks to see if there should be a showMoreSongs button at the bottom of the rankings
    if there is, show the button and return true, else don't show buton and return false
    */

    Rankings.prototype.enableMoreSongsButton = function() {
      if ($('tr.song-min').length > 0 && $('tr.song-min').length % this.songsPerPage === 0) {
        $('#showMoreSongs').removeClass('hidden');
        return true;
      } else {
        $('#showMoreSongs').addClass('hidden');
        return false;
      }
    };

    Rankings.prototype.changeTitle = function(title) {
      return $('#rankings-title').text(title);
    };

    Rankings.prototype.submitComment = function(comment, user, index) {
      var ytcode;
      if (index == null) index = -1;
      if (comment === '') {
        return alert('Please enter a comment');
      } else if (user === '') {
        return alert('Please enter a username to comment');
      } else {
        if (index !== -1) {
          ytcode = $('#ytcode_' + index).val();
        } else {
          ytcode = $('#upload_yturl').val();
        }
        return $.post('ajax/commentAjax.php', {
          user: encodeURIComponent(user),
          comment: encodeURIComponent(comment),
          ytcode: ytcode
        }, function(data) {
          if (index !== -1) {
            if (!$('#max_' + index).find('.no-comment').hasClass('hidden')) {
              $('#max_' + index).find('.no-comment').addClass('hidden');
            }
            $('#max_' + index).find('.comment-display').prepend(data);
          }
          return $('.song-max').find('.comment-user').val(user);
        });
      }
    };

    Rankings.prototype.refreshTitle = function() {
      var genre, title;
      if (this.filt('genre') === 'all') {
        genre = 'Tracks';
      } else {
        genre = this.filt('genre');
      }
      if (genre === 'Tracks' && this.filt('time') === 'new') {
        title = 'The Fresh List';
      } else if (this.filt('time') === 'new') {
        title = 'The Freshest ' + genre;
      } else {
        title = "Top " + genre + " of the " + this.filt('time');
      }
      return $('#rankings-title').text(title);
    };

    Rankings.prototype.initializeSongs = function(startIndex, endIndex) {
      var i, _results;
      this.enableMoreSongsButton();
      _results = [];
      for (i = startIndex; startIndex <= endIndex ? i <= endIndex : i >= endIndex; startIndex <= endIndex ? i++ : i--) {
        if ($('#max_' + i).find('.comment-p').length < this.commentsPerSong) {
          $('#max_' + i).find('.see-more-comments').addClass('hidden');
        }
        if ($('#max_' + i).find('.comment-p').length === 0) {
          _results.push($('#max_' + i).find('.comment-display').html('<p class="no-comment">No Comments. </p>'));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*searches database for songs with 'searchterm' in either the title or artist 
    and displays results in rankings
    */

    Rankings.prototype.search = function(searchterm) {
      var commentsPerSong, upperlimit;
      upperlimit = this.songsPerPage;
      commentsPerSong = this.commentsPerSong;
      searchterm = searchterm.trim();
      if (searchterm.length === 0) {
        return alert('Please enter a search term');
      } else {
        this.changeTitle('Searching: ' + searchterm);
        this.flag = 'search';
        return $.post('ajax/searchAjax.php', {
          searchTerm: searchterm,
          upperLimit: upperlimit
        }, function(data) {
          var i, _ref, _results;
          $('#rankings-table').html(data);
          if ($('tr.song-min').length > 0 && $('tr.song-min').length % upperlimit === 0) {
            $('#showMoreSongs').removeClass('hidden');
          } else {
            $('#showMoreSongs').addClass('hidden');
          }
          if ($('tr.song-min').length > 0) {
            _results = [];
            for (i = 1, _ref = $('tr.song-min').length; 1 <= _ref ? i <= _ref : i >= _ref; 1 <= _ref ? i++ : i--) {
              if ($('#max_' + i).find('.comment-p').length === 0) {
                $('#max_' + i).find('.comment-display').html('<p class="no-comment">No Comments. </p>');
              }
              if ($('#max_' + i).find('.comment-p').length < commentsPerSong) {
                _results.push($('#max_' + i).find('.see-more-comments').addClass('hidden'));
              } else {
                _results.push(void 0);
              }
            }
            return _results;
          }
        });
      }
    };

    return Rankings;

  })();

  /*=================================================
  -------------------On Load--------------------------
  =================================================
  */

  $(function() {
    window.player = new Player;
    window.queue = new Queue;
    window.rankings = new Rankings;
    $(window).resize(function() {
      return player.resizeMaxQueue();
    });
    $('#genQ').sortable({
      update: function(event, ui) {
        queue.genQ.setCurrentSong();
        return queue.updateMinQueue();
      }
    });
    $('#userQ').sortable({
      update: function(event, ui) {
        window.queue.userQ.updateSongCookies();
        window.queue.userQ.refresh();
        return window.queue.updateMinQueue();
      }
    });
    $('#about-link').click(function() {
      $('.lightbox-div').addClass('hidden');
      return $('#about').removeClass('hidden');
    });
    $('#contact-link').click(function() {
      $('.lightbox-div').addClass('hidden');
      return $('#contact').removeClass('hidden');
    });
    $('#upload').click(function() {
      $('.lightbox-div').addClass('hidden');
      return $('#upload_box').removeClass('hidden');
    });
    $('#contact-submit').click(function() {
      return $.post('ajax/sendEmail.php', {
        email: $('#contact-email').val(),
        name: $('#contact-name').val(),
        message: $('#contact-message').val()
      }, function(data) {
        $('#contact-results').html(data);
        $('#contact-email').val('');
        $('#contact-name').val('');
        return $('#contact-message').val('');
      });
    });
    $(document).on('click', '.song-button', function() {
      var i;
      i = $(this).closest('.song').attr('id').split('_')[1];
      if ($(this).hasClass('play-button')) {
        return queue.playSong('genQ', i, true);
      } else if ($(this).hasClass('queue-button')) {
        return queue.userQ.append(i);
      }
    });
    $(document).on('click', '#hide-ads', function() {
      $('.ads').slideUp('slow');
      $('#adsPlease').html('Ads help support t3k.no\'s developlement. Help t3k.no stay fast, <span class="ads-button" id="show-ads">click here</span> to enable ads.');
      return $('#adBlock').animate({
        height: 30
      }, 'slow');
    });
    $(document).on('click', '#show-ads', function() {
      $('.ads').show('slow');
      $('#adBlock').animate({
        height: 300
      }, 'slow');
      $('.ads').slideDown('slow');
      return $('#adsPlease').html('Ads help support t3k.no\'s development. If it <i>really</i> bothers you, <span class="ads-button" id="hide-ads">click here</span> to hide the ads.');
    });
    $(document).on('hover', '.user-queue', function() {
      return $(this).find('.delete-song').toggleClass('hidden');
    });
    $(document).on('click', '.delete-song', function(event) {
      var i;
      i = $(this).closest('.queue-item').attr('id').split('_')[1];
      queue.userQ["delete"](i);
      return event.stopPropagation();
    });
    $(document).on('click', '.next-song', function() {
      var i, id, q;
      id = $('#min-queue').find('.queue-item:first-child').attr('id');
      i = id.split('_')[1];
      q = id.split('_')[0];
      return queue.playSong(q, i, true);
    });
    $(document).on('click', '.previous-song', function() {
      return player.previousSong();
    });
    $(document).on('click', '.queue-item', function() {
      var i, q;
      if ($(this).hasClass('selected-song')) {
        return window.player.playOrPause();
      } else {
        i = $(this).attr('id').split('_')[1];
        q = $(this).attr('id').split('_')[0];
        return queue.playSong(q, i);
      }
    });
    $('.filter').click(function() {
      if ($(this).hasClass('genre-filter')) {
        rankings.filters.set('genre', $(this).html().toLowerCase());
      } else {
        rankings.filters.set('time', $(this).html().toLowerCase());
      }
      return rankings.update();
    });
    $('#header-logo').click(function() {
      return $('#fresh-list').click();
    });
    $('#fresh-list').click(function() {
      rankings.filters.set('time', 'new');
      rankings.filters.set('genre', 'all');
      return rankings.update();
    });
    $('.shuffle').click(function() {
      var queue;
      queue = $(this).closest('.queue').attr('id');
      $('#' + queue + ' li').shuffle();
      if (queue === 'user-queue') {
        window.queue.userQ.updateSongCookies();
        window.queue.userQ.refresh();
      } else {
        window.queue.genQ.setCurrentSong();
      }
      return window.queue.updateMinQueue();
    });
    $(document).on('click', '.song', function() {
      var i, state, temp;
      temp = $(this).attr('id').split('_');
      i = temp[1];
      state = temp[0];
      if (state === 'min') {
        if (rankings.maxed_song !== -1) {
          $('#max_' + rankings.maxed_song).css('display', 'none');
          $('#min_' + rankings.maxed_song).removeClass('hidden');
        }
        $('#min_' + i).addClass('hidden');
        $('#max_' + i).show('slow');
        return rankings.maxed_song = i;
      }
    });
    $(document).on('click', '.queue-min', function() {
      $('#max-queue').hide(400);
      $('#queue-max').html("[Show Queue]");
      return $('.content').removeClass('queue-open');
    });
    $(document).on('click', '#queue-max', function() {
      if ($('#max-queue').css('display') === 'none') {
        $('.content:not(#bottom-contents)').addClass('queue-open');
        $('#max-queue').slideDown(400);
        $('#max-queue').removeClass('hidden');
        return $('#queue-max').html("[Close Queue]");
      } else {
        return $('.queue-min').click();
      }
    });
    $(document).on('click', '#showMoreSongs', function() {
      var lowerLimit;
      lowerLimit = $('tr.song-min').length;
      return $.post('ajax/showMoreSongsAjax.php', {
        genrefilter: rankings.filt('genre'),
        timefilter: rankings.filt('time'),
        searchTerm: $('#search-input').val(),
        flag: rankings.flag,
        lowerLimit: lowerLimit,
        upperLimit: $('tr.song-min').length + rankings.songsPerPage
      }, function(data) {
        $('#rankings-table').append(data);
        rankings.initializeSongs(lowerLimit, lowerLimit + rankings.songsPerPage);
        queue.genQ.refresh(false);
        return queue.updateMinQueue();
      });
    });
    $(document).on('click', '.submit-comment', function() {
      var i;
      i = $(this).closest('.song').attr('id').split('_')[1];
      return rankings.submitComment($('#max_' + i).find('.comment-text').val(), $('#max_' + i).find('.comment-user').val(), i);
    });
    $(document).on('click', '.see-more-comments', function() {
      return rankings.nextComments($(this).closest('.song').attr('id').split('_')[1]);
    });
    $(document).on('click', '.vote-button', function() {
      var downs, i, player, result, score, toAdd, ups, user, ytcode;
      if (!$(this).hasClass('highlight-vote')) {
        if ($(this).attr('id').indexOf('player') === -1) {
          i = $(this).closest('.song').attr('id').split('_')[1];
          if ($(this).attr('id') === 'up-vote') {
            result = 'up';
            toAdd = 1;
            $('#max_' + i).find("#up-vote").addClass('highlight-vote');
            $('#max_' + i).find("#down-vote").removeClass('highlight-vote');
          } else if ($(this).attr('id') === 'down-vote') {
            result = 'down';
            toAdd = -1;
            $('#max_' + i).find("#down-vote").addClass('highlight-vote');
            $('#max_' + i).find("#up-vote").removeClass('highlight-vote');
          } else {
            result = 'error';
          }
          player = false;
          ytcode = $('#ytcode_' + i).val();
          user = $('#user_' + i).val();
          score = $('#score_' + i).val();
          ups = $('#ups_' + i).val();
          downs = $('#downs_' + i).val();
        } else {
          if ($(this).attr('id').indexOf('up-vote') === -1) {
            result = 'down';
            toAdd = -1;
          } else {
            result = 'up';
            toAdd = 1;
          }
          player = true;
          ytcode = window.currentSong.ytcode;
          user = window.currentSong.user;
          score = window.currentSong.score;
          ups = window.currentSong.ups;
          downs = window.currentSong.downs;
        }
        return $.post('ajax/voteAjax.php', {
          result: result,
          ytcode: ytcode,
          user: user,
          score: score,
          ups: ups,
          downs: downs,
          player: player
        }, function(data) {
          var newScore, oldScore;
          if (player) {
            $('#bottomControls').find('.score-container').html(data);
            newScore = parseInt(window.currentSong.userScore) + toAdd;
            return $('#currentSongUser').html(window.currentSong.user + ' {<span class="user-score">' + newScore + '</span>}');
          } else {
            $('#max_' + i).find('.score-container').html(data);
            $('#min_' + i).find('.score-container').html(data);
            oldScore = parseInt($('#max_' + i).find('.user-score').html());
            return $('#max_' + i).find('.user-score').html(oldScore + toAdd);
          }
        });
      }
    });
    $('#search-button').click(function() {
      return rankings.search($('#search-input').val());
    });
    $(document).on('click', '.search-filter', function() {
      return rankings.search($(this).html());
    });
    $('#upload_song').click(function() {
      return $.post('ajax/uploadAjax.php', {
        ytcode: $('#upload_yturl').val(),
        title: $('#upload_title').val(),
        oldie: $('#upload_oldie').attr('checked'),
        artist: $('#upload_artist').val(),
        genre: $('#upload_genre').val(),
        user: $('#upload_user').val()
      }, function(data) {
        console.log('Upload Result: ' + data);
        $("#upload-box-result").html(data);
        $("#upload-box-result").removeClass('hidden');
        if ($("#upload-box-result").html().indexOf("Upload Failed") === -1) {
          $('#upload-box-result').css('color', '#33FF33');
          if ($('#upload_comment').val() !== '') {
            rankings.submitComment($('#upload_comment').val(), $('#upload_user').val(), -1);
          }
          $('#upload_yturl').val('');
          $('#upload_title').val('');
          $('#upload_artist').val('');
          return $('#upload_comment').val('');
        } else {
          return $('#upload-box-result').css('color', 'red');
        }
      });
    });
    return $('#min_1').click();
  });

}).call(this);
