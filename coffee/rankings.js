(function() {
  var Filters, Rankings;

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

  window.Rankings = Rankings = (function() {

    function Rankings() {
      this.filters = new Filters;
      this.maxed_song = -1;
      this.songsPerPage = $('tr.song-min').length;
      this.commentsPerSong = 4;
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
      return this.initializeSongs(1, this.songsPerPage);
    };

    Rankings.prototype.filt = function(filter) {
      return this.filters.filt(filter);
    };

    Rankings.prototype.nextComments = function(index) {
      var comments, debug, iterator, lowerLimit;
      debug = false;
      if (debug) console.log('Rankings.nextComments(' + index + ')');
      lowerLimit = parseInt($('#commentIterator_' + index).val());
      comments = this.commentsPerSong;
      iterator = lowerLimit + this.commentsPerSong;
      $('#commentIterator_' + index).val(iterator);
      if (debug) {
        console.log('POSTS: ');
        console.log('lowerLimit: ' + lowerLimit);
        console.log('upperLimit: ' + iterator);
        console.log('ytcode: ' + $('#ytcode_' + index).val());
      }
      return $.post('ajax/nextCommentAjax.php', {
        lowerLimit: lowerLimit,
        upperLimit: iterator,
        ytcode: $('#ytcode_' + index).val()
      }, function(data) {
        if (debug) console.log(data);
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
      console.log('Rankings.changeTitle(' + title + ')');
      return $('#rankings-title').text(title);
    };

    Rankings.prototype.submitComment = function(comment, user, index) {
      var debug, ytcode;
      if (index == null) index = -1;
      debug = false;
      if (debug) {
        console.log('User ' + user + ' commenting on ' + index + ': ' + comment);
      }
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
        if (debug) console.log('POSTS:');
        if (debug) console.log(encodeURIComponent(user));
        if (debug) console.log(encodeURIComponent(comment));
        if (debug) console.log('ytcode: ' + ytcode);
        return $.post('ajax/commentAjax.php', {
          user: encodeURIComponent(user),
          comment: encodeURIComponent(comment),
          ytcode: ytcode
        }, function(data) {
          if (debug) console.log('Data: ' + data);
          if (index !== -1) {
            if (!$('#max_' + index).find('.no-comment').hasClass('hidden')) {
              $('#max_' + index).find('.no-comment').addClass('hidden');
            }
            $('#max_' + index).find('.comment-display').prepend(data);
            return $('#max_' + index).find('.submit-comment').addClass('hidden');
          }
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
      if (genre === 'all' && this.filt('time') === 'new') {
        title = 'The Fresh List';
      } else if (this.filt('time') === 'new') {
        title = 'The Freshest ' + genre;
      } else {
        title = "Top " + genre + " of the " + this.filt('time');
      }
      return $('#rankings-title').text(title);
    };

    Rankings.prototype.initializeSongs = function(startIndex, endIndex) {
      var debug, i, _results;
      debug = false;
      this.enableMoreSongsButton();
      if (debug) {
        console.log('Rankings.initializeComments(' + startIndex + ',' + endIndex + ') called');
      }
      _results = [];
      for (i = startIndex; startIndex <= endIndex ? i <= endIndex : i >= endIndex; startIndex <= endIndex ? i++ : i--) {
        if (debug) {
          console.log('Comments on Song ' + i + ": " + $('#max_' + i).find('.comment-p').length);
        }
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
        console.log('search:' + searchterm);
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

  $(function() {
    var rankings;
    rankings = new Rankings;
    $('.filter').click(function() {
      if ($(this).hasClass('genre-filter')) {
        rankings.filters.set('genre', $(this).html().toLowerCase());
      } else {
        rankings.filters.set('time', $(this).html().toLowerCase());
      }
      return $.post('ajax/rankingsAjax.php', {
        genrefilter: rankings.filt('genre'),
        timefilter: rankings.filt('time')
      }, function(data) {
        $('#rankings-table').html(data);
        rankings.initialize();
        rankings.refreshTitle();
        return rankings.flag = 'normal';
      });
    });
    $(document).on('click', '.song', function() {
      var i, state, temp;
      temp = $(this).attr('id').split('_');
      i = temp[1];
      state = temp[0];
      if (state === 'min') {
        if (rankings.maxed_song !== -1) {
          $('#max_' + rankings.maxed_song).addClass('hidden');
          $('#min_' + rankings.maxed_song).removeClass('hidden');
        } else {

        }
        $('#min_' + i).addClass('hidden');
        $('#max_' + i).removeClass('hidden');
        return rankings.maxed_song = i;
      }
    });
    $(document).on('click', '.queue-min', function() {
      return $('#max-queue').addClass('hidden');
    });
    $(document).on('click', '#queue-max', function() {
      return $('#max-queue').removeClass('hidden');
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
        return rankings.initializeSongs(lowerLimit, lowerLimit + rankings.songsPerPage);
      });
    });
    $(document).on('click', '.submit-comment', function() {
      var i;
      i = $(this).closest('.song').attr('id').split('_')[1];
      return rankings.submitComment($('#max_' + i).find('.comment-text').val(), $('#max_' + i).find('.comment-user').val(), i);
    });
    $(document).on('click', '.see-more-comments', function() {
      var debug;
      debug = false;
      if (debug) console.log('See-More-Comments Clicked');
      return rankings.nextComments($(this).closest('.song').attr('id').split('_')[1]);
    });
    $(document).on('click', '.vote-button', function() {
      var debug, i, result;
      debug = false;
      if (!$(this).hasClass('highlight-vote')) {
        i = $(this).closest('.song').attr('id').split('_')[1];
        if ($(this).attr('id') === 'up-vote') {
          if (debug) console.log('UpVote called on ' + i);
          result = 'up';
          $('#max_' + i).find("#up-vote").addClass('highlight-vote');
          $('#max_' + i).find("#down-vote").removeClass('highlight-vote');
        } else if ($(this).attr('id') === 'down-vote') {
          if (debug) console.log('DownVote called on ' + i);
          result = 'down';
          $('#max_' + i).find("#down-vote").addClass('highlight-vote');
          $('#max_' + i).find("#up-vote").removeClass('highlight-vote');
        } else {
          if (debug) {
            console.log('Error: Something went wrong with the vote-buttons');
          }
          result = 'error';
        }
        return $.post('ajax/voteAjax.php', {
          result: result,
          ytcode: $('#ytcode_' + i).val(),
          score: $('#score_' + i).val(),
          ups: $('#ups_' + i).val(),
          downs: $('#downs_' + i).val()
        }, function(data) {
          if (debug) console.log('Vote Success: ' + data);
          $('#max_' + i).find('.score-container').html(data);
          return $('#min_' + i).find('.score-container').html(data);
        });
      }
    });
    $('#search-button').click(function() {
      return rankings.search($('#search-input').val());
    });
    $(document).on('click', '.search-filter', function() {
      return rankings.search($(this).html());
    });
    return $('#upload_song').click(function() {
      var debug;
      debug = false;
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
          if (debug) console.log('comment: ' + $('#upload_comment').val());
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
  });

}).call(this);
