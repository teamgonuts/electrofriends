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
      this.enableMoreSongsButton();
    }

    Rankings.prototype.filt = function(filter) {
      return this.filters.filt(filter);
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

    Rankings.prototype.refreshTitle = function() {
      var genre, title;
      if (this.filt('genre') === 'all') {
        genre = 'Tracks';
      } else {
        genre = this.filt('genre');
      }
      if (genre === 'all' && this.filt('time') === 'new') {
        return $('#rankings-title').text("The Fresh List");
      } else {
        title = "Top " + genre + " of the " + this.filt('time');
        return $('#rankings-title').text(title);
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
        rankings.enableMoreSongsButton();
        return rankings.refreshTitle();
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
      } else {
        $('#min_' + i).removeClass('hidden');
        $('#max_' + i).addClass('hidden');
        return rankings.maxed_song = -1;
      }
    });
    $(document).on('click', '#showMoreSongs', function() {
      return $.post('ajax/showMoreSongsAjax.php', {
        genrefilter: rankings.filt('genre'),
        timefilter: rankings.filt('time'),
        lowerLimit: $('tr.song-min').length,
        upperLimit: $('tr.song-min').length + rankings.songsPerPage
      }, function(data) {
        $('#rankings-table').append(data);
        return rankings.enableMoreSongsButton();
      });
    });
    return $('#search-button').click(function() {
      if ($('#search-input').val().length === 0) {
        return alert('Please Enter a Search Term');
      } else {
        console.log('Search: ' + $('#search-input').val());
        rankings.changeTitle('Searching For: ' + $('#search-input').val());
        return $.post('ajax/searchAjax.php', {
          searchTerm: $('#search-input').val()
        }, function(data) {
          $('#rankings-table').html(data);
          return $('#showMoreSongs').addClass('hidden');
        });
      }
    });
  });

}).call(this);
