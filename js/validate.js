$(document).ready(function(){

	$("form.cancel").submit(function(e) {

		if (confirm("Delete the order?") === false) {
			// Preventing form submission
			e.preventDefault();  
		} 
	});
});