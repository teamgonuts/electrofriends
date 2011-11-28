
$(function() {
    initializeStaticContent();

    $(document).on('hover', 'tr.song', function() //highlights song rows during hover
    {
        $(this).children().toggleClass('highlightRow');
    });

    $(document).on('click', '.filter', function(){
        if($(this).hasClass('time-filter'))
        {
            $('#current-time-filter').val($(this).attr('id')); //setting new time filter
            $('.time-filter').removeClass('highlight-filter'); //removing old highlighting
            $(this).toggleClass('highlight-filter'); //highlighting new filter
        }
        else if($(this).hasClass('genre-filter')) //genre filter
        {
            $('#current-genre-filter').val($(this).attr('id')); //setting new time filter
            $('.genre-filter').removeClass('highlight-filter'); //removing old highlighting
            $(this).toggleClass('highlight-filter'); //highlighting new filter
        }

        $.post('ajax/rankingsajax.php', { timefilter: $('#current-time-filter').val(), genrefilter: $('#current-genre-filter').val()},
        function(data) {
          $('#rankings-container').html(data);
          //hide all maximized songs except the first
            $('.min#min1').addClass('hidden');
            $('.max:not(#max1)').addClass('hidden');
        });

        //when a filter is changed, don't change the current queue, but the next song
        //to be added to the queue is the song at the top of the rankings
        $('#playlist-next-index').val('1');

    }); //highlights correct filter and dynamically loads new rankings

    $(document).on('click', '.genre-link', function(){
        if($(this).attr('id') == 'hub-genre-link')
            var genre = $('#song-genre').html().toLowerCase();
        else //figure out which song was clicked, get its genre
        {
            //Hacky Way to Get Index
            var temp = $(this).attr("id");
            temp = temp.split('_');
            var i = temp[1];
            var genre = $('#genre_' + i).val().toLowerCase();
        }

        $.post('ajax/rankingsajax.php', { timefilter: $('#current-time-filter').val(), genrefilter: genre},
            function(data) {
              $('#rankings-container').html(data);
              //hide all maximized songs except the first
                $('.min#min1').addClass('hidden');
                $('.max:not(#max1)').addClass('hidden');
            });

            //when a filter is changed, don't change the current queue, but the next song
            //to be added to the queue is the song at the top of the rankings
            $('#playlist-next-index').val('1');

            //highlight correct genre
            $('.genre-filter').removeClass('highlight-filter');
            $('#' + genre).addClass('highlight-filter');
    });

	$(document).on('click', '.submit-comment', function() //todo rename, this is for comments
	{
		//Hacky Way to Get Index
		var temp = $(this).attr("id");
		temp = temp.split('_');
		var i = temp[1];
        //alert(i);

		var user = encodeURIComponent($("#comment-user_"+i).val());
		//alert('user '+ user);
		var comment = encodeURIComponent($("#comment-text_"+i).val());
		//alert('comment '+comment);

		if(user=='' || comment=='')
		{
			alert('Please Give Valid Details');
		}
		else
		{
			//Disable Button
			$('#submit-comment_'+i).attr("disabled", true);
			$('#submit-comment_'+i).addClass("disabled");
            $('#comment-text_'+i).addClass('disabled');
            $('#comment-text_'+i).attr("disabled", true);
            $('#comment-user_'+i).addClass('disabled');
            $('#comment-user_'+i).attr("disabled", true);

			$.post('ajax/commentajax.php', { comment: comment, user: user, ytcode: $("#ytcode_"+i).val(), i:i},
            function(html) {
                //alert(html);
                html = decodeURIComponent(html);
                $("ol#update_"+i).prepend(html);
                $("ol#update_"+i+" li:first").fadeIn("slow");
                //alert('end');
			});
		}

	});

	$(document).on('click', '.clickable', function(e) //everything within a song's maxed row
	{
		//Figuring out which element a user clicked
		var targ;
		if (!e) 
			e = window.event;
		if (e.target) 
			targ = e.target;
		else if (e.srcElement) 
			targ = e.srcElement;
		if (targ.nodeType == 3) // defeat Safari bug
			targ = targ.parentNode;
			
		//Hacky Way to Get Index
		var temp = $(this).attr("id");
		temp = temp.split('_');
		var i = temp[1];

		if ($(targ).attr("class") == "link")
        {
            if($(targ).attr('id') == 'title_link') //open the individual song page
            {
                var ytcode = $("#ytcode_"+i).val().toLowerCase();
                window.open('view.php?s=' + ytcode,'_blank');
            }
            else if($(targ).attr('id') == 'artist_link') //creates new rankings with artist filter
            {
                //var topOf = $("#topOf").val();
                var topOf = $("#topOf").val().toLowerCase();
                var artist = encodeURIComponent($("#artist_"+i).val().toLowerCase());
                window.location.href = ('index.php?topof=' + topOf + '&artist=' + artist);
            }
            else if($(targ).attr('id') == 'genre_link')
            {
                var topOf = $("#topOf").val().toLowerCase();
                var genre = $("#genre_"+i).val();
                window.location.href = ('index.php?topof=' + topOf + '&genre=' + genre);
            }
        }
        else if($(targ).hasClass("play-button")) //the user clicked play
        {
            //1. start playing video
            ytplayer = document.getElementById('ytp');
            ytplayer.loadVideoById($('#ytcode_' + i).val());
            //2. add current_song to recently played
            recently_played.push(current_song);
            loadSongInfoIndex(i);

        }
        else if($(targ).hasClass("play-next-button")) //the user clicked queue
        {
            //move everything in the queue down and add new song to first item
            $('#playlist-3').html($('#playlist-2').html());
            $('#playlist-2').html($('#playlist-1').html());
            $('#playlist-1').html($('#song-info-min_'+i).html());

            queue.pop(); //delete last song
            queue.unshift(new Song($('#ytcode_'+i).val(), //add new song to beginning of array
                        $('#title_'+i).val(),
                        $('#artist_'+i).val(),
                        $('#genre_'+i).val(),
                        $('#score_'+i).val(),
                        $('#ups_'+i).val(),
                        $('#downs_'+i).val(),
                        $('#user_'+i).val()));
        }
        else if($(targ).hasClass("queue-button")) //the user clicked queue
        {
            $('#playlist-3').html($('#song-info-min_'+i).html()); //setting last song to this html
            queue.pop(); //delete last song
            queue.push(new Song($('#ytcode_'+i).val(), //add clicked song to end of array
                        $('#title_'+i).val(),
                        $('#artist_'+i).val(),
                        $('#genre_'+i).val(),
                        $('#score_'+i).val(),
                        $('#ups_'+i).val(),
                        $('#downs_'+i).val(),
                        $('#user_'+i).val()));
        }
		else //user wants to minimize song
		{
            if($('.max#max' + i).hasClass('hidden')) //if I'm hidden
            {
                $('.max').addClass('hidden'); //hide all maxed songs
                $('.min').removeClass('hidden'); //show all min songs
                $('.max#max' + i).toggleClass('hidden'); //unhide max self
                $('.min#min' + i).toggleClass('hidden'); //hide my min self

            }
            else //if im open
            {
                $('.max#max' + i).toggleClass('hidden'); //close myself
                $('.min#min' + i).toggleClass('hidden');
            }
		}
	});

	$(document).on('click', '.vote-button', function(e)
	{

        $.post('ajax/voteAjax.php', { ytcode: current_song.ytcode,
                                               result: $(this).attr('id'),
                                               score: current_song.score,
                                               ups: current_song.ups,
                                               downs: current_song.downs},
            function(html) {
                //alert(html);
                $('#song-score').html(html);
                $('.vote-button').disable();
            });
	});

    $(document).on('click', '#showMoreSongs', function()
    {
        //Hacky Way to Get Index
		var temp = $('.song:last').attr("id");
		temp = temp.split('max');
		var lowerLimit = temp[1];
        var upperLimit = parseInt(lowerLimit) + parseInt($('#songs-per-page').val());
        //alert('lowerLimit: ' + lowerLimit + " upperLimit" + upperLimit);
        $.post('ajax/showMoreSongsAjax.php', { timefilter: $('#current-time-filter').val(),
                                               genrefilter: $('#current-genre-filter').val(),
                                               lowerLimit: lowerLimit,
                                               upperLimit: upperLimit},
            function(html) {
                //alert('showMoreSongsAjax.php: ' + html);
                if(html.length > 0) //rows were returned
                {
                    $('.rankings-table').append(html);
                    //alert(lowerLimit);
                    nextSongIndex = (parseInt(lowerLimit) +1).toString();
                    $('.min').removeClass('hidden');
                    $('.max').addClass('hidden');
                    $('.max#max'+ nextSongIndex).removeClass('hidden');
                    $('.min#min'+ nextSongIndex).addClass('hidden');
                }
                else
                    $('#showMoreSongs').addClass('hidden');

                //if we added less than the amount of songs possible to add aka all the songs are shown
                var songsCount= $('.song').size() / 2; //divide by 2 because there is min and max
                //alert(songsCount);
                if(songsCount < upperLimit)
                {
                    $('#showMoreSongs').addClass('hidden');
                }
            });
        //alert("done");
    });

    $(document).on('click', '.showMoreComments', showMoreComments);

    $(document).on('click', '.uploadlink', function() //toggles hidden upload box
    {
        $('#upload-box-result').addClass('hidden');
        $('#upload_box').toggleClass('hidden');
    });
    
    $(document).on('click', '#upload_song', function() //upload's song to db
        {
            var title = encodeURIComponent($('#upload_title').val());
            var artist = encodeURIComponent($('#upload_artist').val());
            var yturl = $('#upload_yturl').val();
            var user = encodeURIComponent($('#upload_user').val());
            var genre = $('#upload_genre').val();
            var oldie = $('#oldie').attr('checked'); //if the song uploaded is an old song
            var dataString = 'title='+title+'&artist='+artist+
                             '&yturl='+yturl+'&user='+user+
                             '&genre='+genre+'&oldie='+oldie;
            $.ajax({
                type: "POST",
                url: "ajax/uploadajax.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#upload-box-result").html(html);
                    $("#upload-box-result").removeClass('hidden');
                },
                error:function(xhr, ajaxOptions, thrownError)
                {
                    alert("Ajax fail: \n" + xhr.statusText);
                }
            });
        });
    return false;
});

/*resizes the song's title and artist in the bottom player so it fits on one line
  if contents already fit on one line, keep the same size */
function resizeText()
{
    var size = 16;
    var desired_width = $('#song-info').width();
    $('#resizer').html($('#song-info-titleartist').html()); //setting resizer to desired text
    $('#resizer').css("font-size", size);
    var actual_width = $('#resizer').width();
    
    while(desired_width <= actual_width+10) //+10 for saftey net
    {
        size--;
        $('#resizer').css("font-size", size);
        actual_width = $('#resizer').width();
    }

    $('#song-info-titleartist').css("font-size", size);
}
//initializes all the static content on the page (queue, filters...etc)
function initializeStaticContent()
{
    //hide all maximized songs except the first
    $('.min#min1').addClass('hidden');
    $('.max:not(#max1)').addClass('hidden');

    //highlight default filtering
    $('.filter#freshest').toggleClass('highlight-filter');
    $('.filter#all').toggleClass('highlight-filter');
    $('#current-time-filter').val('freshest');
    $('#current-genre-filter').val('all');

    //load up first song
    loadSongInfoIndex(1); //load song-info
    var params = { allowScriptAccess: "always" }; //load song-swf
    swfobject.embedSWF("http://www.youtube.com/v/" + $('#ytcode_1').val() + "&enablejsapi=1&playerapiid=ytp" +
                       "&hd=1&iv_load_policy=3&rel=0&showinfo=0",
                        "ytp", "275", "90", "8", null, null, params);
    $('#song-score').html($('#song-voting_1').html());

    //loading queue
    $('#playlist-1').html($('#song-info-min_2').html());
    $('#playlist-2').html($('#song-info-min_3').html());
    $('#playlist-3').html($('#song-info-min_4').html());

    //setting up initial queue
    queue.push(new Song($('#ytcode_2').val(),
                        $('#title_2').val(),
                        $('#artist_2').val(),
                        $('#genre_2').val(),
                        $('#score_2').val(),
                        $('#ups_2').val(),
                        $('#downs_2').val(),
                        $('#user_2').val()));
    queue.push(new Song($('#ytcode_3').val(),
                        $('#title_3').val(),
                        $('#artist_3').val(),
                        $('#genre_3').val(),
                        $('#score_3').val(),
                        $('#ups_3').val(),
                        $('#downs_3').val(),
                        $('#user_3').val()));
    queue.push(new Song($('#ytcode_4').val(),
                        $('#title_4').val(),
                        $('#artist_4').val(),
                        $('#genre_4').val(),
                        $('#score_4').val(),
                        $('#ups_4').val(),
                        $('#downs_4').val(),
                        $('#user_4').val()));

    $('#playlist-next-index').val('5'); //the next song to pull info from
}
//loads a song from the rankigns based of index
function loadSongInfoIndex(i)
{
    current_song = new Song($('#ytcode_' + i).val(),
                        $('#title_' + i).val(),
                        $('#artist_' + i).val(),
                        $('#genre_' + i).val(),
                        $('#score_' + i).val(),
                        $('#ups_' + i).val(),
                        $('#downs_' + i).val(),
                        $('#user_' + i).val());

    loadSongInfoCurrentSong()
    }

//loads song info from current_Song
function loadSongInfoCurrentSong()
{
    $('#song-title').html(current_song.title);
    $('#song-artist').html(current_song.artist);
    $('#song-genre').html(current_song.genre);
    $('#song-user').html(current_song.user);
    $('#song-score').html(current_song.score +
                     '[' + current_song.ups + '/' + current_song.downs + ']')
    $('#song-download').html('Amazon');

    resizeText();
}

//returns a ajax formatted datastring given i, the index of the song in the rankings
function getDataString(i)
{
    //stats
    var user = $("#user_"+i).val();
    var ytcode = $("#ytcode_"+i).val();
    var title = encodeURIComponent($("#title_"+i).val());
    var artist = encodeURIComponent($("#artist_"+i).val());
    var genre = $("#genre_"+i).val();
    var score = $("#score_"+i).val();
    var ups = $("#ups_"+i).val();
    var downs = $("#downs_"+i).val();
    var id = $("#id_"+i).val();
    var upload_date = $("#upload_date_"+i).val();
    return 'user='+ user + '&ytcode=' + ytcode +
            '&title=' + title + '&artist=' + artist +
            '&genre=' + genre + '&score=' + score +
            '&ups=' + ups + '&downs=' + downs +
            '&id=' + id + '&upload_date=' + upload_date +
            '&i=' + i;
}

//parses dataString and returns the entry specified by the parameter
function parseEntry(entry, dataString)
{
    //getting index of entry
    var idx = dataString.indexOf(entry + "=");
    //if the entry is title, get the data after title=
    var temp = dataString.substring(idx + entry.length + 1, dataString.length-1); // from where we want to the end of string
    //return up until the next &
    return temp.substring(0, temp.indexOf('&'));
}

//dataString to pass to ajax, i is index of song
function maximizeSong(dataString, i)
{
    $.ajax(
    {
        type: "POST",
        url: "ajax/maxSongAjax.php",
        data: dataString,
        cache: false,
        success: function(html)
        {
            $('#'+i).html(html);
        }
    });
    $('#'+i).ajaxComplete(function(){
            //reloads script
             $.getScript('http://platform.twitter.com/widgets.js');
    });
}

function minimizeSong(dataString, i)
{
    $.ajax({
        type: "POST",
        url: "ajax/minSongAjax.php",
        data: dataString,
        cache: false,
        success: function(html){
            //alert("success");
            $('#'+i).fadeIn(1000).html(html);
        }
    });
}

function showMoreComments()
{
	var temp = $(this).attr("id");
	temp = temp.split('_');
	var i = temp[1];

	var where = $('#whereCom').val();
	var upperLimit = parseInt($('#upperLimitCom').val());
	var commentsShown = parseInt($('#commentsShown').val());

	var dataString = 'where='+ where + '&upperLimit='+upperLimit+
	'&commentsShown='+commentsShown;
    
	$.ajax({
		type: "POST",
		url: "ajax/showMoreCommentsAjax.php",
		data: dataString,
		cache: false,
		success: function(html)
		{


			if(html.length > 0) //rows are returned
				$("ol#update_"+i).append(html);
			else //no rows are returned, disable buttons
			{
				$('.showMoreComments').hide();
			}

			var commentsAdded = $('ol#update_'+i+' li').size() - upperLimit;
			if(commentsAdded < commentsShown) //if there are no new comments to qry in the database
			{
				$('.showMoreComments').hide();
			}
				
		},
		error:function(xhr, ajaxOptions, thrownError)
		{
			alert("Ajax fail: \n" + xhr.statusText);
		}
	});
	
	$('#upperLimitCom').val(upperLimit + commentsShown);
}



