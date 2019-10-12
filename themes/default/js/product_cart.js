var cart = {
	'add': function(product_id, token) {
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=cart&nocache=' + new Date().getTime(),
			type: 'post',
			data: 'action=add&product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1) + '&token=' + token,
			dataType: 'json',
			beforeSend: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
			},
			complete: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-shopping-cart" aria-hidden="true"></i>');
			},
			success: function(json) {

				$('.alert, .text-danger').remove();
				
				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#ProductContent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');
					
					$('#cart-total').html(json['total']);
					 
					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#product-cart > ul').load( nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=loadcart ul li' );
				}
				
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cart&nocache=' + new Date().getTime(),
			type: 'post',
			data: 'action=edit&key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
			},
			complete: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-shopping-cart" aria-hidden="true"></i>');
			},
			success: function(json) {
				//$('#text-loading').hide();
				//$('#shops-cart-order').show();

				$('#cart-total').html(json['total']);

				//if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
				//	location = 'index.php?route=checkout/cart';
				//} else {
					$('#product-cart > ul').load(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=loadcart ul li');
				//}
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cart&nocache=' + new Date().getTime(),
			type: 'post',
			data: 'action=remove&key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
			},
			complete: function() {
				$('#product-block-cart i').replaceWith('<i class="fa fa-shopping-cart" aria-hidden="true"></i>');
			},
			success: function(json) {
				// $('#text-loading').hide();
				// $('#shops-cart-order').show();
				
				$('#cart-total').html(json['total']);
 
				if (json['redirect']) 
				{
					location = json['redirect'];
				} 
				else 
				{
					$('.alert, .text-danger').remove();
					
					$('#cart-total').html(json['total']);
					
					$('tr[rel="'+key+'"]').remove();
					
					$('#product-cart > ul').load(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=loadcart ul li');
					
				}	 
			}
		});
	},
	'removecart': function(key) {
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				//$('#text-loading').show();
				//$('#shops-cart-order').hide();
			},
			success: function(json) {
				location.reload(); 
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#text-loading').show();
				$('#shops-cart-order').hide();
			},
			complete: function() {
				$('#text-loading').hide();
				$('#shops-cart-order').show();
			},
			success: function(json) {
				$('#cart-total').html(json['total']);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

var wishlist = {
	'add': function(product_id, token) {
 
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=wishlist&action=add&nocache=' + new Date().getTime(),
			type: 'post',
			data: { product_id : product_id, token: token},
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#ProductContent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');
				}

				if (json['info']) {
					$('#ProductContent').prepend('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<i class="fa fa-times"></i></div>');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	},
	'remove': function( product_id, token ) {

	}
}

var compare = {
	'add': function(product_id, token) {
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + product_mod + '&' + nv_fc_variable + '=compare&action=add&nocache=' + new Date().getTime(),
			type: 'post',
			data: { product_id : product_id, token: token},
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#ProductContent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			}
		});
	},
	'remove': function() {

	}
}
