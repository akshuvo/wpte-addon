jQuery(function($){
	$(document).ready(function($){


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
		});

	});

	$(window).load(function(){

		$('.wpte-product-wrap').each(function(){
			var selectedDiv = $(this).find('.wpte-qty select option');

			if (selectedDiv.length == 1 ) {

				var formData = $(this).find('form.variations_form').attr('data-product_variations');

				var formDataobj = JSON.parse(formData);
				var initPrice = formDataobj[0].display_regular_price;

				$(this).find('.woocommerce-variation-price').text( wpte_data.wc_currency+initPrice );

			}

		});

	});
});