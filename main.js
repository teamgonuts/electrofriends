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
	}return false;
}); 


$("tr").delegate("td", "click", function() //wtf why does this work...seriously
//$('#table').on('click', 'td.clickable', function() 
{
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
						 
		$.ajax({
			type: "POST",
			url: "maxSongAjax.php",
			data: dataString,
			cache: false,
			success: function(html){
			$('#'+i).fadeIn(1000).html(html);
			}
		});
		
	}
	/*
	//Minimizing Old Song
	var o_ytcode = $("#ytcode_max").val();
	var o_title = $("#title_max").val();
	var o_artist = $("#artist_max").val();
	var o_genre = $("#genre_max").val();
	var o_score = $("#score_max").val();
	var o_ups = $("#ups_max").val();
	var o_downs = $("#downs_max").val();
	var o_id = $("#id_max").val();
	var o_i = $("#i_max").val();
	var o_user = $("#user_max").val();
	var o_upload_date = $("#upload_date_max").val();
	var dataString =   'o_title=' + o_title + '&o_artist=' + o_artist +
					 '&o_genre=' + o_genre + '&o_score=' + o_score +
					 '&o_ups=' + o_ups + '&o_downs=' + o_downs +
					 '&o_id=' + o_id + '&o_i=' + o_i +
					 '&o_ytcode=' + o_ytcode + '&o_user=' + o_user +
					 '&o_upload_date=' + upload_date;
	$("#song_load").show();
	$("#song_load").fadeIn(400).html('<tr><td>Closing Song...</td></tr>');

	$.ajax({
		type: "POST",
		url: "minSongAjax.php",
		data: dataString,
		cache: false,
		success: function(html){
		alert($('.song_max').html());
		alert(html);
		$('.song_max').fadeIn(1000).html(html);
		$("#song_load").hide();
		$('.song_max').attr("id" , "song_min"+o_id); //setting new class of tr to max
		$('.song_max').attr("class" , "song_min"); //setting new class of tr to max
		}
	});
	*/
	

	
	return false;
}); 

});