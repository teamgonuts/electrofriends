$(function() {

	$(".submit").live("click", function()
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
			url: "commentajax.php",
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
});


$(function()
{
	$('.clickable').live('click', function()
	{
		//Figuring out where a user clicked
		var targ;
		if (!e) var e = window.event;
		if (e.target) targ = e.target;
		else if (e.srcElement) targ = e.srcElement;
		if (targ.nodeType == 3) // defeat Safari bug
			targ = targ.parentNode;

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
		//alert('Clicked ' + i);
		if($(targ).attr("class") == "share") //share button
		{
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

				dataString = 'user='+ user + '&ytcode=' + ytcode +
				'&title=' + title + '&artist=' + artist +
				'&genre=' + genre + '&score=' + score +
				'&ups=' + ups + '&downs=' + downs +
				'&id=' + id + '&upload_date=' + upload_date +
				'&i=' + i;

				$.ajax({
					type: "POST",
					url: "minSongAjax.php",
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
				dataString = 'user='+ user + '&ytcode=' + ytcode +
				'&title=' + title + '&artist=' + artist +
				'&genre=' + genre + '&score=' + score +
				'&ups=' + ups + '&downs=' + downs +
				'&id=' + id + '&upload_date=' + upload_date +
				'&i=' + i;

				$.ajax(
				{
					type: "POST",
					url: "maxSongAjax.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						$('#'+i).fadeIn(1000).html(html);
					}
				});

			}
		}
		return false;
	});
});

$(function()
{
	$('.upvote').live('click', function()
	{
		var i = $(this).attr("id");
		//alert('i = ' + i);
		
		var ytcode = $("#ytcode_"+i).val();
		var score = $("#score_"+i).val();
		//alert('score = ' + score);
		var ups = $("#ups_"+i).val();
		var downs = $("#downs_"+i).val();
		//alert(ytcode);
		dataString = 'ytcode='+ytcode + '&score='+score+
						'&ups='+ups + '&downs='+downs+
						'&i='+i;
		//alert('dataString='+dataString);
		$.ajax({
			type: "POST",
			url: "voteUpAjax.php",
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
});


$(function()
{
	$('.downvote').live('click', function()
	{
		var i = $(this).attr("id");
		//alert('i = ' + i);
		
		var ytcode = $("#ytcode_"+i).val();
		var score = $("#score_"+i).val();
		//alert('score = ' + score);
		var ups = $("#ups_"+i).val();
		var downs = $("#downs_"+i).val();
		//alert(ytcode);
		dataString = 'ytcode='+ytcode + '&score='+score+
						'&ups='+ups + '&downs='+downs+
						'&i='+i;
		//alert('dataString='+dataString);
		$.ajax({
			type: "POST",
			url: "voteDownAjax.php",
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
});
