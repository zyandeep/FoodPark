function callAjax() {
	$.ajax({
		url: 'script.php',
		type: "GET",
		success: function(result, status) {
			$('a#msg').html(result);
		},
		error: function(xhr,status,error) {
			console.log(error);	
		},
		complete: function() {
			setTimeout(callAjax, 4000);
		}
	});
}


$(document).ready(function() {
	setTimeout(callAjax, 2000);

	// Ask for confirmation
	
});