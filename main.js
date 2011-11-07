$(function() {
$(".submit").click(function() 
{
	var name = $("#name").val();
	var comment = $("#comment").val();
	var ytcode = $("#ytcode").val(); 
	var dataString = 'name='+ name + '&comment=' + comment+ '&ytcode=' + ytcode;
	if(name=='' || comment=='')
	{
		alert('Please Give Valid Details');
	}
	else
	{
		//Disable Button
		$('.submit').attr("disabled", true);
		$('.submit').css("color", "gray");
		$('.submit').css("background-color", "lightgray");
		$('#name').attr("disabled", true);
		$('#name').css("color", "gray");
		$('#name').css("background-color", "lightgray");
		
		$("#flash").show();
		$("#flash").fadeIn(400).html('Loading Comment...');
		$.ajax({
			type: "POST",
			url: "commentajax.php",
			data: dataString,
			cache: false,
			success: function(html){
			$("ol#update").prepend(html);
			$("ol#update li:first").fadeIn("slow");
			$("#flash").hide();
			}
		});
	}return false;
}); 

$(".song_min").click(function() 
{
	//REALLY hacky way to get i
	var html = $(this).html();
	var temp = html.split('</pre>');
	var ii = temp[0].split('<pre>');
	var i = ii[1];
	
	var user = $("#user_min"+i).val();
	var ytcode = $("#ytcode_min"+i).val(); 
	var title = $("#title_min"+i).val(); 
	var artist = $("#artist_min"+i).val(); 
	var genre = $("#genre_min"+i).val(); 
	var score = $("#score_min"+i).val(); 
	var ups = $("#ups_min"+i).val(); 
	var downs = $("#downs_min"+i).val();
	var id = $("#id_min"+i).val(); 
	var upload_date = $("#upload_date_min+i").val(); 
	var dataString = 'user='+ user + '&ytcode=' + ytcode +
				     '&title=' + title + '&artist=' + artist + 
				     '&genre=' + genre + '&score=' + score + 
				     '&ups=' + ups + '&downs=' + downs + 
				     '&id=' + id + '&upload_date=' + upload_date + 
					 '&i=' + i;

	
	$("#song_load").show();
	$("#song_load").fadeIn(400).html('<tr><td>Loading Song...</td></tr>');
	$.ajax({
		type: "POST",
		url: "maxSongAjax.php",
		data: dataString,
		cache: false,
		success: function(html){
		var temp = '#song_min' + i;
		$(temp).fadeIn(1000).html(html);
		$("#song_load").hide();
		}
	});
	
	
	return false;
}); 

});