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

		    //console.log('Qty:', thisVal);
		    jQuery(window).trigger('load');

		});

		$( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
		    jQuery('.custom-qty').trigger('change');
		    jQuery(window).trigger('load');

		    setTimeout(function(){
		    	jQuery('.custom-qty').trigger('change');
				jQuery(window).trigger('load');
			},100);
		} );


		$(document).on('wpte_single_cart_variation',function(){

			$('.wpte-product-wrap').each(function(){

				var varPrice = $(this).find('.single_variation .price').text();
				varPrice = parseFloat( varPrice.replace("$", "") );

				//var inputQty = $(this).find('input.qty').val();
				var inputQty = $(this).find('select.custom-qty').val();



				var optionDiv = $(this).find('.wpte-qty select#pack option');

				var formData = $(this).find('form.variations_form').attr('data-product_variations');

				var formDataobj = JSON.parse(formData);

				if (optionDiv.length == 1 ) {

					var initPrice = formDataobj[0].display_regular_price;

					$(this).find('.single_variation_price .woocommerce-variation-price').text( wpte_data.wc_currency+initPrice );

					//console.log('has single variation');

				} else {
					var varTotal = (inputQty*varPrice);
					$(this).find('.single_variation_price .woocommerce-variation-price').text( wpte_data.wc_currency+varTotal.toFixed(2) );
				}

				//console.log( inputQty, varPrice );
				//console.log( formDataobj );

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