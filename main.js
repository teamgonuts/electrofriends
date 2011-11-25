$(function() {

    /* Scrolling Title
    (function titleMarquee() {
        document.title = document.title.substring(1) + document.title.substring(0,1);
        setTimeout(titleMarquee, 200);
    })(); */

	//$(".submit").live("click", function()
	$(document).on('click', '.submit', function(e)
	{
		//Hacky Way to Get Index
		var temp = $(this).attr("id");
		temp = temp.split('_');
		var i = temp[1];

		//alert(i);
		var name = encodeURIComponent($("#cuser_"+i).val());
		//alert('name '+name);
		var comment = encodeURIComponent($("#comment_"+i).val());

		var ytcode = $("#ytcode_"+i).val();
		var dataString = 'name='+ name + '&comment=' + comment+ '&ytcode=' + ytcode + '&i=' + i;
		if(name=='' || comment=='')
		{
			alert('Please Give Valid Details');
		}
		else
		{
			//Disable Button
			$('#submit_'+i).attr("disabled", true);
			$('#submit_'+i).css("color", "gray");
			$('#submit_'+i).css("background-color", "lightgray");
			$('#cuser_'+i).attr("disabled", true);
			$('#cuser_'+i).css("color", "gray");
			$('#cuser_'+i).css("background-color", "lightgray");


			$.ajax({
			type: "POST",
			url: "ajax/commentajax.php",
			data: dataString,
			cache: false,
			success: function(html){
			//alert(html);
			$("ol#update_"+i).prepend(html);
			$("ol#update_"+i+" li:first").fadeIn("slow");

			}
			});
		}
		return false;
	});
	return false;
});


$(function()
{
	$(document).on('click', '.clickable', function(e)
	{
		//alert('click');

		//Figuring out where a user clicked
		var targ;
		if (!e) 
			e = window.event;
		if (e.target) 
			targ = e.target;
		else if (e.srcElement) 
			targ = e.srcElement;
		if (targ.nodeType == 3) // defeat Safari bug
			targ = targ.parentNode;
			
		//alert('after');
		//Hacky Way to Get Index
		var temp = $(this).attr("id");
		temp = temp.split('_');
		var i = temp[1];
        var dataString;
        var ytcode = $("#ytcode_"+i).val(); //special case, needed for share link

        var targetSong = $('#targetSong').val();
        
		if($(targ).attr("class") == "nonclickable")
        {
            //do nothing
        }
        else if ($(targ).attr("class") == "link")
        {
            if($(targ).attr('id') == 'title_link') //open the individual song page
                window.open('view.php?s=' + ytcode,'_blank');
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
		else //its a song
		{
			if(targetSong == i) //if I am the one that is open
			{
	            //minimize song
                //alert("about to min");
                minimizeSong(getDataString(i), i);
                $('title').text('T3kno - The Electronic Music Connection');
                //alert("minimized");
			}
			else
			{
                //minimize old target song and max new
                //max other first so only have to resize table once. 
                dataString = getDataString(i);
                maximizeSong(dataString, i);
                minimizeSong(getDataString(targetSong), targetSong);
			}
		}
		return false;
	});
});

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


$(function()
{
	//$('.upvote').live('click', function()
	$(document).on('click', '.upvote', function(e)
	{
		var i = $(this).attr("id");
		//alert('i = ' + i);
		
		var ytcode = $("#ytcode_"+i).val();
		var score = $("#score_"+i).val();
		//alert('score = ' + score);
		var ups = $("#ups_"+i).val();
		var downs = $("#downs_"+i).val();
		//alert(ytcode);
		var dataString = 'ytcode='+ytcode + '&score='+score+
						'&ups='+ups + '&downs='+downs+
						'&i='+i;
		//alert('dataString='+dataString);
		$.ajax({
			type: "POST",
			url: "ajax/voteUpAjax.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				//alert('success');
				$('#td4_'+i).html(html).fadeIn(1000);				
			},
			error:function(xhr, ajaxOptions, thrownError)
			{
				alert("Ajax fail");
			}
		});
		return false;
	});
	return false;
});


$(function()
{
	//$('.downvote').live('click', function()
	$(document).on('click', '.downvote', function(e)
	{
		var i = $(this).attr("id");
		//alert('i = ' + i);
		
		var ytcode = $("#ytcode_"+i).val();
		var score = $("#score_"+i).val();
		//alert('score = ' + score);
		var ups = $("#ups_"+i).val();
		var downs = $("#downs_"+i).val();
		//alert(ytcode);
		var dataString = 'ytcode='+ytcode + '&score='+score+
						'&ups='+ups + '&downs='+downs+
						'&i='+i;
		//alert('dataString='+dataString);
		$.ajax({
			type: "POST",
			url: "ajax/voteDownAjax.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				//alert('success');
				$('#td4_'+i).fadeIn(1000).html(html);				
			},
			error:function(xhr, ajaxOptions, thrownError)
			{
				alert("Ajax fail: \n" + xhr.statusText);
			}
		});
		return false;
	});
	return false;
});

$(document).on('hover', 'tr.song', function()
{
    $(this).children().toggleClass('highlightRow');
});

//attempt at good coding
$(document).on('click', '.showMore', showMoreSongs);

function showMoreSongs()
{
	var where = $('#where').val();
    var topOf = $('#topOf').val();
	var upperLimit = parseInt($('#upperLimit').val());
	var songsPerPage = parseInt($('#songsPerPage').val());

	var dataString = 'where='+ where + '&upperLimit='+upperLimit+
						'&songsPerPage='+songsPerPage + "&topOf="+topOf;
    $.ajax({
		type: "POST",
		url: "ajax/showMoreSongsAjax.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
			if(html.length > 0) //rows are returned
				$('.rankings').append(html);
			else //no rows are returned, disable buttons
			{
				$('.showMore').hide();
			}
			
			var songsAdded = $('.song').size() - upperLimit;
			if(songsAdded < songsPerPage) //if we added less than the amount of songs possible to add aka all the songs are shown
			{
				$('.showMore').hide();
			}
				
		},
		error:function(xhr, ajaxOptions, thrownError)
		{
			alert("Ajax fail: \n" + xhr.statusText);
		}
	});

	$('#upperLimit').val(upperLimit + songsPerPage);
}

$(document).on('click', '.showMoreComments', showMoreComments);
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

//generates the upload_box to upload song
$(document).on('click', '.uploadlink', quickUpload);
function quickUpload()
{
    //alert('test1');
    $("#upload_box").removeClass("upload_box_success");
    $.ajax({
		url: "upload.php",
		success: function(html)
		{
            $("#upload_box").addClass("upload_box");
            $("#upload_box").html(html);
		},
		error:function(xhr, ajaxOptions, thrownError)
		{
			alert("Ajax fail: \n" + xhr.statusText);
		}
	});
    //alert('test2');
}

//clicking the upload song button in upload_box
$(document).on('click', '#upload_song', uploadSong);
function uploadSong()
{
    var title = $('#upload_title').val();
    var artist = $('#upload_artist').val();
    var yturl = $('#upload_yturl').val();
    var user = $('#upload_user').val();
    var genre = $('#upload_genre').val();

    var dataString = 'title='+title+'&artist='+artist+
                     '&yturl='+yturl+'&user='+user+
                     '&genre='+genre;
	$.ajax({
		type: "POST",
		url: "ajax/uploadajax.php",
		data: dataString,
		cache: false,
		success: function(html)
		{

			$("#upload_box").html(html);
            $("#upload_box").removeClass("upload_box");
            $("#upload_box").addClass("upload_box_success");
		},
		error:function(xhr, ajaxOptions, thrownError)
		{
			alert("Ajax fail: \n" + xhr.statusText);
		}
	});
}

