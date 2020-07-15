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

		$(document).on('change', '.custom-qty', function() {

			var thisVal = $(this).val();

		    $(this).closest('.single_variation_wrap').find('input.qty').val(parseInt(thisVal));

		    console.log('Qty:', thisVal);

		});

		$(document).on('wpte_single_cart_variation',function(){

			$('.wpte-product-wrap').each(function(){
				var selectedDiv = $(this).find('.wpte-qty select#pack option');

				if (selectedDiv.length == 1 ) {

					var formData = $(this).find('form.variations_form').attr('data-product_variations');

					var formDataobj = JSON.parse(formData);
					var initPrice = formDataobj[0].display_regular_price;

					$(this).find('.woocommerce-variation-price').text( wpte_data.wc_currency+initPrice );

					console.log('has single variation');

				}

			});

		});

	});

	$(window).load(function(){

		jQuery(document).trigger('wpte_single_cart_variation');

		setTimeout(function(){
			jQuery(document).trigger('wpte_single_cart_variation');
		},300);


	});
});