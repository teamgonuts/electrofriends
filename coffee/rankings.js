(function() {
  var Filters, Rankings;

  window.Filters = Filters = (function() {

    function Filters(genre, time, artist, user) {
      this.genre = genre != null ? genre : "all";
      this.time = time != null ? time : "new";
      this.artist = artist != null ? artist : "";
      this.user = user != null ? user : "";
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
      if (this.filters.genre === 'all') {
        $('#filter-all').addClass('highlight-filter');
      } else if (this.filters.genre === 'dnb') {
        $('#filter-dnb').addClass('highlight-filter');
      } else if (this.filters.genre === 'dubstep') {
        $('#filter-dubstep').addClass('highlight-filter');
      } else if (this.filters.genre === 'electro') {
        $('#filter-electro').addClass('highlight-filter');
      } else if (this.filters.genre === 'hardstyle') {
        $('#filter-hardstyle').addClass('highlight-filter');
      } else if (this.filters.genre === 'house') {
        $('#filter-house').addClass('highlight-filter');
      } else if (this.filters.genre === 'trance') {
        $('#filter-trance').addClass('highlight-filter');
      }
      if (this.filters.time === 'day') {
        return $('#filter-day').addClass('highlight-filter');
      } else if (this.filters.time === 'week') {
        return $('#filter-week').addClass('highlight-filter');
      } else if (this.filters.time === 'month') {
        return $('#filter-month').addClass('highlight-filter');
      } else if (this.filters.time === 'year') {
        return $('#filter-year').addClass('highlight-filter');
      } else if (this.filters.time === 'century') {
        return $('#filter-century').addClass('highlight-filter');
      }
    };

    return Filters;

  })();

  window.Rankings = Rankings = (function() {

    function Rankings() {
      this.filters = new Filters;
    }

    Rankings.prototype.filt = function(filter) {
      return this.filters.filt(filter);
    };

    return Rankings;

  })();

  $(function() {
    var rankings;
    rankings = new Rankings;
    rankings.filters.set('genre', 'dubstep');
    return $('.filter').click(function() {
      if ($(this).hasClass('genre-filter')) {
        return rankings.filters.set('genre', $(this).html().toLowerCase());
      } else {
        return rankings.filters.set('time', $(this).html().toLowerCase());
      }
    });
  });

}).call(this);
