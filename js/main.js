var current_time_filter;
var current_genre_filter;
var current_artist_filter;
var current_user_filter;

var maxed_song;

$(function() {
    //alert('init Static');
    initializeStaticContent();

    $(document).on('hover', 'tr.song', function() //highlights song rows during hover
    {
        $(this).children().toggleClass('highlightRow');
    });
    //alert('on hover tr.song');
    
    $(document).on('click', '.filter', function(){
        if($(this).hasClass('time-filter'))
        {
            current_time_filter = $(this).attr('id'); //setting new time filter
            $('.time-filter').removeClass('highlight-filter'); //removing old highlighting
            $(this).toggleClass('highlight-filter'); //highlighting new filter
        }
        else if($(this).hasClass('genre-filter')) //genre filter
        {
            current_genre_filter = $(this).attr('id'); //setting the new genre filter
            $('.genre-filter').removeClass('highlight-filter'); //removing old highlighting
            $(this).toggleClass('highlight-filter'); //highlighting new filter
            current_artist_filter = '';
        }
        else if($(this).hasClass('artist-link')) //artist filter
        {
            current_artist_filter = $(this).html();
            //alert(current_artist_filter);
            $('.genre-filter').removeClass('highlight-filter'); //removing old highlighting
        }
        else if($(this).hasClass('user-link')) //user filter
        {
            //alert($(this).html());
            current_user_filter = $(this).html();
            $('.genre-filter').removeClass('highlight-filter'); //removing old highlighting
            $('.time-filter').removeClass('highlight-filter'); //removing old highlighting
            $('.time-filter#freshest').addClass('highlight-filter'); //highlight freshest
            current_time_filter = 'freshest';
            //alert(current_user_filter);
        }

        $.post('ajax/rankingsajax.php', { timefilter: current_time_filter,
                                          genrefilter: current_genre_filter,
                                          artistfilter: current_artist_filter,
                                          userfilter: current_user_filter},
        function(data) {

            //alert(data);
            $('#rankings-container').html(data);
            if(!$('#max-queue').hasClass('hidden')) //if max-queue is open
                $('#rankings-table').css('width', '100%');
            //hide all maximized songs except the first
            $('#min_1').addClass('hidden');
            $('#max_1').removeClass('hidden');
            generateQueue();

        });

        //when a filter is changed, don't change the current queue, but the next song
        //to be added to the queue is the song at the top of the rankings
        $('#playlist-next-index').val('1');

    }); //highlights correct filter and dynamically loads new rankings
    //alert('on click .filter');

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
        current_genre_filter = genre;
        current_artist_filter = '';
        $.post('ajax/rankingsajax.php', { timefilter: current_time_filter,
                                          genrefilter: current_genre_filter,
                                          artistfilter: current_artist_filter,
                                          userfilter: current_user_filter},
            function(data) {
              //alert(data);
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
    //alert('on click genre-link');

	$(document).on('click', '.submit-comment', function()
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

		//Getting index;
		var temp = $(this).closest('tr').attr("id");
		temp = temp.split('_');
		var i = temp[1];
        //alert(i);

		if ($(targ).hasClass('link'))
        {
            //dont minimize
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
            $('#playlist-1').html($('#title_'+i).val() + ' - ' + $('#artist_' + i).val());

            queue.pop(); //delete last song
            queue.unshift(new Song($('#ytcode_'+i).val(), //add new song to beginning of array
                        $('#title_'+i).val(),
                        $('#artist_'+i).val(),
                        $('#genre_'+i).val(),
                        $('#score_'+i).val(),
                        $('#ups_'+i).val(),
                        $('#downs_'+i).val(),
                        $('#user_'+i).val()));

            resizeQueue();
        }
        else if($(targ).hasClass("queue-button")) //the user clicked queue
        {
            $('#playlist-3').html($('#title_'+i).val() + ' - ' + $('#artist_' + i).val()); //setting last song to this html
            queue.pop(); //delete last song
            queue.push(new Song($('#ytcode_'+i).val(), //add clicked song to end of array
                        $('#title_'+i).val(),
                        $('#artist_'+i).val(),
                        $('#genre_'+i).val(),
                        $('#score_'+i).val(),
                        $('#ups_'+i).val(),
                        $('#downs_'+i).val(),
                        $('#user_'+i).val()));

            resizeQueue();
        }
		else //user wants to change songs state
		{
            //alert("i=" + i + " - maxed="+maxed_song);
            if(maxed_song == i)
                min(i);
            else
                max(i);
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

    $(document).on('click', '#showMoreSongs', function() //todo broken
    {
        //Hacky Way to Get Index
		var temp = $('.song:last').attr("id");
        temp = temp.split('_');
        var lowerLimit = temp[1];
        var upperLimit = parseInt(lowerLimit) + parseInt($('#songs-per-page').val());
        //alert('lowerLimit: ' + lowerLimit + " upperLimit" + upperLimit);
        //todo: fix for artist and user filters
        $.post('ajax/showMoreSongsAjax.php', { timefilter: current_time_filter,
                                               genrefilter: current_genre_filter,
                                               artistfilter: current_artist_filter,
                                               userfilter: current_user_filter,
                                               lowerLimit: lowerLimit,
                                               upperLimit: upperLimit},
            function(html) {
                if(html.length > 0) //rows were returned
                {
                    //alert(html);
                    $('#rankings-table').append(html);
                    //alert(lowerLimit);
                    nextSongIndex = (parseInt(lowerLimit) +1).toString();
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

                    if($("#upload-box-result").html().indexOf("Success") != '-1')
                    {
                        //alert("hi");
                        //clear old entries
                        $('#upload_yturl').val('');
                        $('#upload_title').val('');
                        $('#upload_artist').val('');
                    }
                },
                error:function(xhr, ajaxOptions, thrownError)
                {
                    alert("Ajax fail: \n" + xhr.statusText);
                }
            });
        });
    //alert('end on Load');
});

//minimizes song i in rankings
function min(i)
{
    //alert('function min called');
    $('#max_' +i).addClass('hidden');
    $('#min_' +i).removeClass('hidden');

    if(maxed_song = i)
        maxed_song = -1; //-1 because no songs are maxed

}
//maximizes song i in rankings
function max(i)
{
    //alert('function maxed called');

    //min current song
    $('#max_' + maxed_song).addClass('hidden');
    $('#min_' + maxed_song).removeClass('hidden');
    //max i
    $('#min_' + i).addClass('hidden');
    $('#max_' + i).removeClass('hidden');
    maxed_song = i;
}

/*resizes the song's title and artist in the bottom player so it fits on one line
  if contents already fit on one line, keep the same size */
//params id of container to resize, id of thing we want resized
function resizeText(containerID, resizeID, startingsize )
{
    var size = startingsize.replace('px', '');
    //alert(size);
    
    var desired_width = $('#' + containerID).width();
    $('#resizer').html($('#' + resizeID).html()); //setting resizer to desired text
    $('#resizer').css("font-size", size);
    var actual_width = $('#resizer').width();
    //alert("desired: " + desired_width + ' - actual: ' + actual_width + ' - font-size: ' + size);
    
    while(desired_width <= actual_width+30) //+10 for saftey net
    {
        size--;
        $('#resizer').css("font-size", size);
        actual_width = $('#resizer').width();
    }

    $('#' + resizeID).css("font-size", (size + "px"));
    //alert(desired_width + ' - ' + actual_width + ' - ' + size);

}
//helper method that calls resizeText on all the items in the queue
function resizeQueue()
{
    //todo resize max-queue
    //alert('font size: ' + $('#min-queue').css('font-size'));
    resizeText("song-playlist" , "playlist-1", $('#min-queue').css('font-size'));
    resizeText("song-playlist" , "playlist-2", $('#min-queue').css('font-size'));
    resizeText("song-playlist" , "playlist-3", $('#min-queue').css('font-size'));
    resizeText("song-playlist" , "playlist-4", $('#min-queue').css('font-size'));
}
//initializes all the static content on the page (queue, filters...etc)
function initializeStaticContent()
{
    //alert('1');
    //hide all maximized songs except the first
    maxed_song = 1;
    $('#min_' + maxed_song).addClass('hidden');
    $('#max_' + maxed_song).removeClass('hidden');

    //highlight default filtering
    $('.filter#freshest').toggleClass('highlight-filter');
    $('.filter#all').toggleClass('highlight-filter');
    current_time_filter = 'freshest';
    current_genre_filter = 'all';
    current_artist_filter = '';
    current_user_filter = '';
    //alert('2');
    //load up first song
    loadSongInfoIndex(1); //load song-info
    //alert('3');
    var params = { allowScriptAccess: "always" }; //load song-swf
    swfobject.embedSWF("http://www.youtube.com/v/"+ current_song.ytcode +"?enablejsapi=1&playerapiid=ytp&version=3" +
                        "&hd=1&iv_load_policy=3&rel=0&showinfo=0",
                        "ytp", "275", "90", "8", null, null, params);


    //alert('4');
    $('#song-score').html($('#song-voting_1').html());

    //loading queue
    initializeQueue();
    //alert('5');
    generateQueue();
    //alert('6');

    resizeQueue();
    //alert('7');
    $('#playlist-next-index').val('5'); //the next song to pull info from
    //alert("end of initializeStaticContents");
    //alert('end');
}
function initializeQueue(){
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
    queue.push(new Song($('#ytcode_5').val(),
                        $('#title_5').val(),
                        $('#artist_5').val(),
                        $('#genre_5').val(),
                        $('#score_5').val(),
                        $('#ups_5').val(),
                        $('#downs_5').val(),
                        $('#user_5').val()));

    $('#playlist-1').html($('#title_2').val() + " - " +$('#artist_2').val());
    $('#playlist-2').html($('#title_3').val() + " - " +$('#artist_3').val());
    $('#playlist-3').html($('#title_4').val() + " - " +$('#artist_4').val());
}
function generateQueue(){
    //alert(($('#rankings-table').find('tr').length-1) / 2);
    for(var i=1; i <= $('#gen-queue').children().length; i++)
    {
        if(i <= $('#rankings-table').find('tr.min').length)
            $('#gen-queue-' + i).html($('#title_' + (i)).val() + " - " +$('#artist_' + (i)).val());
        else
            $('#gen-queue-' + i).html('');

    }

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

    loadSongInfoCurrentSong();
    //alert("end of loadSongInfoIndex");
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

    resizeText("song-info" , "song-info-titleartist", $('body').css('font-size'));
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
