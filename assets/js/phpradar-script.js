(function ($) {
    $(document).ready(function () {
		//AMOUNT ADD BY PREDEFINED AMOUNT BAR
		$('body').on('click','.phpradar-donation-section .phpradar-default-fee-add',function(){
			var ele = $(this);
			var amt = ele.data('value');
            if(isNaN(amt)===true){
                $(".phpradar-error").html("Please enter valid value.").show();
                return false;
            }
			if(ele.attr('data-selected')=='true'){
				amt = 0;
			}
			$( 'body' ).trigger( 'update_checkout' );
			$(document.body).trigger("wc_update_cart");
			var _phpradar_nonce = $('.phpradar-donation-section ._phpradar_nonce').val();
            $.ajax({
                url: phpradar.ajax_url,
                type: "POST",
                data:{_phpradar_nonce:_phpradar_nonce,amount:amt,action:'update_fee'},
                success: function (result) {
					$('.phpradar-donation-section .phpradar-default-fee-add').removeClass('fee-button-added');
					
					$('.phpradar-donation-section .phpradar-default-fee-add').attr('data-selected',false);
					if(amt){
						$(ele).attr('data-selected',true);
						$(ele).addClass('fee-button-added');
						$('.phpradar-donation-section .donation-amount').val(amt);
					} else {
						$('.phpradar-donation-section .donation-amount').val('');
					}
					$( 'body' ).trigger( 'update_checkout' );
					$(document.body).trigger("wc_update_cart");
                    return false;
                }
            });
		});
		//AMOUNT ADD BY INPUT AMOUNT
		$('body').on('click','.phpradar-donation-section .phpradar-fee-add',function(){
            var amt=$('.phpradar-donation-section .donation-amount').val();
			var _phpradar_nonce = $('.phpradar-donation-section ._phpradar_nonce').val();
            if(isNaN(amt)===true){
				$(".phpradar-error").fadeIn('fast', function() {
				  $(this).html("please enter valid value.").css('visibility','visible');
					$(this).fadeOut(10000, function() {
						$(this).css({"display":"block","visibility": "hidden"});  // <-- Style Overwrite 
					});
				});
                return false;
            }
			$( 'body' ).trigger( 'update_checkout' );
			$(document.body).trigger("wc_update_cart");
            $.ajax({
                url: phpradar.ajax_url,
                type: "POST",
                data:{_phpradar_nonce:_phpradar_nonce,amount:amt,action:'update_fee'},
                success: function (result) {
					$('.phpradar-donation-section .phpradar-default-fee-add').removeClass('fee-button-added');
					$('.phpradar-donation-section .phpradar-default-fee-add[data-value='+amt+']').addClass('fee-button-added');
					$( 'body' ).trigger( 'update_checkout' );
					$(document.body).trigger("wc_update_cart");
                    return false;
                }
            });
        });
		
		//AMOUNT REMOVE
		$('body').on('click','.phpradar-donation-section .phpradar-fee-remove',function(){
			$( 'body' ).trigger( 'update_checkout' );
			$(document.body).trigger("wc_update_cart");
            var amt=0;
			var _phpradar_nonce = $('.phpradar-donation-section ._phpradar_nonce').val();
            $.ajax({
                url: phpradar.ajax_url,
                type: "POST",
                data:{_phpradar_nonce:_phpradar_nonce,amount:0,action:'update_fee'},
                success: function (result) {
					$('.phpradar-donation-section .phpradar-default-fee-add').removeClass('fee-button-added');
					$('.phpradar-donation-section .donation-amount').val('');
					$( 'body' ).trigger( 'update_checkout' );
					$(document.body).trigger("wc_update_cart");
                    return false;
                }
            });
        });
    });
})(jQuery);