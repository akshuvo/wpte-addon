jQuery(function($){
	$(document).ready(function($){
		console.log("Hello");

		$(document).on('click', '.wpte-plus', function() {
			var selectDiv = $(this).closest('.wpte-qty').find('select');
			var selectedDiv = $(this).closest('.wpte-qty').find('select > option:selected').next();

		    if (selectedDiv.length > 0) {
			    selectDiv.val( selectedDiv.val() ).change();
			}
		})

		$(document).on('click', '.wpte-minus', function() {
		    var selectDiv = $(this).closest('.wpte-qty').find('select');
			var selectedDiv = $(this).closest('.wpte-qty').find('select > option:selected').prev();

			if (selectedDiv.length > 0) {
			    selectDiv.val( selectedDiv.val() ).change();
			}
		})
	});
});