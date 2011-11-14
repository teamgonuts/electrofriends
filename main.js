$(function() {

	//$(".submit").live("click", function()
	$(document).on('click', '.submit', function(e)
	{
		//Hacky Way to Get Index
		var temp = $(this).attr("id");
		temp = temp.split('_');
		var i = temp[1];
		//alert(i);
		var name = $("#cuser_"+i).val();
		//alert('name '+name);
		var comment = $("#comment_"+i).val();

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
		var status = $('#status_'+i).val();

		//stats
		var user = $("#user_"+i).val();
		var ytcode = $("#ytcode_"+i).val();
		var title = $("#title_"+i).val();
		var artist = $("#artist_"+i).val();
		var genre = $("#genre_"+i).val();
		var score = $("#score_"+i).val();
		var ups = $("#ups_"+i).val();
		var downs = $("#downs_"+i).val();
		var id = $("#id_"+i).val();
		var upload_date = $("#upload_date_"+i).val();
		if($(targ).attr("class") == "share") //share button
		{
			//alert("share");
			$(targ).hide();
			$('#shareURL_'+i).html('<input type="text" style="width:300px;" value="http://t3kno.dewpixel.net/view.php?s='+ytcode+'"/>');
		}
		else if($(targ).attr("type") == "text") //if it is the url
		{ //do nothing 
		}
		else
		{
			if(status == "max") //if maximized
			{
				//alert("max");

				var dataString = 'user='+ user + '&ytcode=' + ytcode +
				'&title=' + title + '&artist=' + artist +
				'&genre=' + genre + '&score=' + score +
				'&ups=' + ups + '&downs=' + downs +
				'&id=' + id + '&upload_date=' + upload_date +
				'&i=' + i;

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
			else
			{
				//alert("min");

				//Maximizing New Song
				var dataString = 'user='+ user + '&ytcode=' + ytcode +
				'&title=' + title + '&artist=' + artist +
				'&genre=' + genre + '&score=' + score +
				'&ups=' + ups + '&downs=' + downs +
				'&id=' + id + '&upload_date=' + upload_date +
				'&i=' + i;

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
		}
		return false;
	});
});

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

//attempt at good coding
$(document).on('click', '.showMore', showMoreSongs);

function showMoreSongs()
{
	var where = $('#where').val();
	var upperLimit = parseInt($('#upperLimit').val());
	var songsPerPage = parseInt($('#songsPerPage').val());

	var dataString = 'where='+ where + '&upperLimit='+upperLimit+
						'&songsPerPage='+songsPerPage;
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