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
}); });