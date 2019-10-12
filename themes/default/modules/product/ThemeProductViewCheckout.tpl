<!-- BEGIN: main -->
<div id="ProductContent">
	<div class="row">
		<div id="content" class="col-sm-24">
			<h1>THANH TOÁN ĐƠN HÀNG</h1>
			<div class="panel-group" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle" aria-expanded="true">{DATA.text_checkout_option} <i class="fa fa-caret-down"></i></a></h4>
					</div>
					<div class="panel-collapse collapse" id="collapse-checkout-option" aria-expanded="true" style="">
						<div class="panel-body">
						</div>
					</div>
				</div>
				<!-- BEGIN: is_user -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">{DATA.text_checkout_account}</h4>
					</div>
					<div class="panel-collapse collapse" id="collapse-payment-address">
						<div class="panel-body"></div>
					</div>
				</div>
				<!-- END: is_user -->
				<!-- BEGIN: is_guest -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">{DATA.text_checkout_payment_address}</h4>
					</div>
					<div class="panel-collapse collapse" id="collapse-payment-address">
						<div class="panel-body"></div>
					</div>
				</div>
				<!-- END: is_guest -->
				
				<!-- BEGIN: shipping_required_1 -->
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h4 class="panel-title">{DATA.text_checkout_shipping_address}</h4>
				  </div>
				  <div class="panel-collapse collapse" id="collapse-shipping-address">
					<div class="panel-body"></div>
				  </div>
				</div>
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h4 class="panel-title">{DATA.text_checkout_shipping_method}</h4>
				  </div>
				  <div class="panel-collapse collapse" id="collapse-shipping-method">
					<div class="panel-body"></div>
				  </div>
				</div> 
				<!-- END: shipping_required_1 -->
				
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">{DATA.text_checkout_payment_method}</h4>
					</div>
					<div class="panel-collapse collapse" id="collapse-payment-method">
						<div class="panel-body"></div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">{DATA.text_checkout_confirm}</h4>
					</div>
					<div class="panel-collapse collapse" id="collapse-checkout-confirm">
						<div class="panel-body"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).on('change', 'input[name=\'account\']', function() {
	if ($('#collapse-payment-address').parent().find('.panel-heading .panel-title > *').is('a')) {
		if (this.value == 'register') {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_account} <i class="fa fa-caret-down"></i></a>');
		} else {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
		}
	} else {
		if (this.value == 'register') {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_account}');
		} else {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_address}');
		}
	}
});

<!-- BEGIN: is_guest_script -->
$(document).ready(function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=login&nocache=' + new Date().getTime(),
        dataType: 'html',
        success: function(html) {
           $('#collapse-checkout-option .panel-body').html(html);

			$('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_option} <i class="fa fa-caret-down"></i></a>');

			$('a[href=\'#collapse-checkout-option\']').trigger('click');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
<!-- END: is_guest_script -->
<!-- BEGIN: is_user_script -->
$(document).ready(function() {
    $.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&nocache=' + new Date().getTime(),
		dataType: 'html',
        success: function(html) {
            $('#collapse-payment-address .panel-body').html(html);

			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');

			$('a[href=\'#collapse-payment-address\']').trigger('click');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
<!-- END: is_user_script -->

 
$(document).delegate('#button-account', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method='+ $('input[name=\'account\']:checked').val() +'&nocache=' + new Date().getTime(),
		dataType: 'html',
        beforeSend: function() {
			var loading = $('#button-account').attr('data-loading-text');
			var text = $('#button-account').attr('value');
			$('#button-account').attr('value', loading).attr('data-loading-text', text);
		},
        complete: function() {
			var loading = $('#button-account').attr('data-loading-text');
			var text = $('#button-account').attr('value');
			$('#button-account').attr('value', loading).attr('data-loading-text', text);
        },
        success: function(html) {
            $('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            $('#collapse-payment-address .panel-body').html(html);

			if ($('input[name=\'account\']:checked').val() == 'register') {
				$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_account} <i class="fa fa-caret-down"></i></a>');
			} else {
				$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
			}

			$('a[href=\'#collapse-payment-address\']').trigger('click');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Login
$(document).delegate('#button-login', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=login&save=1&nocache=' + new Date().getTime(),
		type: 'post',
        data: $('#collapse-checkout-option :input'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-login').attr('data-loading-text');
			var text = $('#button-login').attr('value');
			$('#button-login').attr('value', loading).attr('data-loading-text', text);
		},
        complete: function() {
			var loading = $('#button-login').attr('data-loading-text');
			var text = $('#button-login').attr('value');
			$('#button-login').attr('value', loading).attr('data-loading-text', text);
        },
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                $('#collapse-checkout-option .panel-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('input[name=\'email\']').parent().addClass('has-error');
				$('input[name=\'password\']').parent().addClass('has-error');
		   }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Register
$(document).delegate('#button-register', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=register&action=save&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address textarea, #collapse-payment-address select'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-register').attr('data-loading-text');
			var text = $('#button-register').attr('value');
			$('#button-register').attr('value', loading).attr('data-loading-text', text);
 
		},
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                var loading = $('#button-register').attr('data-loading-text');
				var text = $('#button-register').attr('value');
				$('#button-register').attr('value', loading).attr('data-loading-text', text);

                if (json['error']['warning']) {
                    $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
            } else {
               <!-- BEGIN: shipping_required_2 -->
                var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');

                if (shipping_address) {
                    $.ajax({
                        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_method&nocache=' + new Date().getTime(),		
						dataType: 'html',
                        success: function(html) {
							// Add the shipping address
                            $.ajax({
                                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_address&nocache=' + new Date().getTime(),		
								dataType: 'html',
                                success: function(html) {
                                    $('#collapse-shipping-address .panel-body').html(html);

									$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });

							$('#collapse-shipping-method .panel-body').html(html);

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

   							$('a[href=\'#collapse-shipping-method\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_shipping_method}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                } else {
                    $.ajax({
                        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_address&nocache=' + new Date().getTime(),		
						dataType: 'html',
                        success: function(html) {
                            $('#collapse-shipping-address .panel-body').html(html);

							$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-address\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_shipping_method}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
                <!-- END: shipping_required_2 -->
				<!-- BEGIN: no_shipping_required_2 -->
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_method&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    success: function(html) {
                        $('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_method} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
                <!-- END: no_shipping_required_2 -->

                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    complete: function() {
						var loading = $('#button-register').attr('data-loading-text');
						var text = $('#button-register').attr('value');
						$('#button-register').attr('value', loading).attr('data-loading-text', text);
                    },
                    success: function(html) {
                        $('#collapse-payment-address .panel-body').html(html);

						$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Payment Address
$(document).delegate('#button-payment-address', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&save=1&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-payment-address').attr('data-loading-text');
			var text = $('#button-payment-address').attr('value');
			$('#button-payment-address').attr('value', loading).attr('data-loading-text', text);
		},
        complete: function() {
			var loading = $('#button-payment-address').attr('data-loading-text');
			var text = $('#button-payment-address').attr('value');
			$('#button-payment-address').attr('value', loading).attr('data-loading-text', text);
        },
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');
            } else {
                <!-- BEGIN: shipping_required_3 -->
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_address&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    success: function(html) {
                        $('#collapse-shipping-address .panel-body').html(html);

						$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-shipping-address\']').trigger('click');

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_shipping_method}');
						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }).done(function() {
					$.ajax({
						url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&nocache=' + new Date().getTime(),		
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
                <!-- END: shipping_required_3 -->
				<!-- BEGIN: no_shipping_required_3 -->
                $.ajax({
					url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_method&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    success: function(html) {
                        $('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Phương thức thanh toán <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('Bước 4: Xác nhận thanh toán');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }).done(function() {
					$.ajax({
						url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&nocache=' + new Date().getTime(),		
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});				
				});
                <!-- END: no_shipping_required_3 -->
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Shipping Address
$(document).delegate('#button-shipping-address', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_address&save=1&nocache=' + new Date().getTime(),
        type: 'post',
        data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-shipping-address').attr('data-loading-text');
			var text = $('#button-shipping-address').attr('value');
			$('#button-shipping-address').attr('value', loading).attr('data-loading-text', text);
	    },
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                var loading = $('#button-shipping-address').attr('data-loading-text');
				var text = $('#button-shipping-address').attr('value');
				$('#button-shipping-address').attr('value', loading).attr('data-loading-text', text);

                if (json['error']['warning']) {
                    $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');
            } else {
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_method&nocache=' + new Date().getTime(),
					dataType: 'html',
                    complete: function() {
                        var loading = $('#button-shipping-address').attr('data-loading-text');
						var text = $('#button-shipping-address').attr('value');
						$('#button-shipping-address').attr('value', loading).attr('data-loading-text', text);
                    },
                    success: function(html) {
                        $('#collapse-shipping-method .panel-body').html(html);

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-shipping-method\']').trigger('click');

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
						
                        $.ajax({
                            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_address&nocache=' + new Date().getTime(),
							dataType: 'html',
                            success: function(html) {
                                $('#collapse-shipping-address .panel-body').html(html);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }).done(function() {
					$.ajax({
						url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_address&nocache=' + new Date().getTime(),
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
 
// Guest
$(document).delegate('#button-guest', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=guest&save=1&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-guest').attr('data-loading-text');
			var text = $('#button-guest').attr('value');
			$('#button-guest').attr('value', loading).attr('data-loading-text', text);
	    },
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                var loading = $('#button-guest').attr('data-loading-text');
				var text = $('#button-guest').attr('value');
				$('#button-guest').attr('value', loading).attr('data-loading-text', text);

                if (json['error']['warning']) {
                    $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
            } else {
                <!-- BEGIN: shipping_required_4 -->
                var shipping_address = $('#collapse-payment-address input[name=\'shipping_address\']:checked').prop('value');

                if (shipping_address) {
                    $.ajax({
                        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_method&nocache=' + new Date().getTime(),		
						dataType: 'html',
                        complete: function() {
                           var loading = $('#button-guest').attr('data-loading-text');
							var text = $('#button-guest').attr('value');
							$('#button-guest').attr('value', loading).attr('data-loading-text', text);
                        },
                        success: function(html) {
							// Add the shipping address
                            $.ajax({
                                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=guest_shipping&nocache=' + new Date().getTime(),		
								dataType: 'html',
                                success: function(html) {
                                    $('#collapse-shipping-address .panel-body').html(html);

									$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });

						    $('#collapse-shipping-method .panel-body').html(html);

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-method\']').trigger('click');
								
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
							
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                } else {
                    $.ajax({
                        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=guest_shipping&nocache=' + new Date().getTime(),		
						dataType: 'html',
                        complete: function() {
                            var loading = $('#button-guest').attr('data-loading-text');
							var text = $('#button-guest').attr('value');
							$('#button-guest').attr('value', loading).attr('data-loading-text', text);
                        },
                        success: function(html) {
                            $('#collapse-shipping-address .panel-body').html(html);

							$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-address\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_shipping_method}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
                <!-- END: shipping_required_4 -->
				<!-- BEGIN: no_shipping_required_4 -->
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_method&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    complete: function() {
                        var loading = $('#button-guest').attr('data-loading-text');
						var text = $('#button-guest').attr('value');
						$('#button-guest').attr('value', loading).attr('data-loading-text', text);
                    },
                    success: function(html) {
                        $('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_method}<i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
                <!-- END: no_shipping_required_4 -->
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Guest Shipping
$(document).delegate('#button-guest-shipping', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=guest_shipping&save=1&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-guest-shipping').attr('data-loading-text');
			var text = $('#button-guest-shipping').attr('value');
			$('#button-guest-shipping').attr('value', loading).attr('data-loading-text', text);
		},
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
				var loading = $('#button-guest-shipping').attr('data-loading-text');
				var text = $('#button-guest-shipping').attr('value');
				$('#button-guest-shipping').attr('value', loading).attr('data-loading-text', text);

                if (json['error']['warning']) {
                    $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
            } else {
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_method&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    complete: function() {
 
						var loading = $('#button-guest-shipping').attr('data-loading-text');
						var text = $('#button-guest-shipping').attr('value');
						$('#button-guest-shipping').attr('value', loading).attr('data-loading-text', text);
						
                    },
                    success: function(html) {
                        $('#collapse-shipping-method .panel-body').html(html);

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Phương thức vận chuyển <i class="fa fa-caret-down"></i>');

						$('a[href=\'#collapse-shipping-method\']').trigger('click');

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_payment_method}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).delegate('#button-shipping-method', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=shipping_method&save=1&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-shipping-method input[type=\'radio\']:checked, #collapse-shipping-method textarea'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-shipping-method').attr('data-loading-text');
			var text = $('#button-shipping-method').attr('value');
			$('#button-shipping-method').attr('value', loading).attr('data-loading-text', text);
		},
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
				var loading = $('#button-shipping-method').attr('data-loading-text');
				var text = $('#button-shipping-method').attr('value');
				$('#button-shipping-method').attr('value', loading).attr('data-loading-text', text);

                if (json['error']['warning']) {
                    $('#collapse-shipping-method .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            } else {
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_method&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    complete: function() {
						var loading = $('#button-shipping-method').attr('data-loading-text');
						var text = $('#button-shipping-method').attr('value');
						$('#button-shipping-method').attr('value', loading).attr('data-loading-text', text);
                    },
                    success: function(html) {
                        $('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_payment_method} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{DATA.text_checkout_confirm}');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).delegate('#button-payment-method', 'click', function() {
    $.ajax({
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=payment_method&save=1&nocache=' + new Date().getTime(),		
		type: 'post',
        data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea'),
        dataType: 'json',
        beforeSend: function() {
			var loading = $('#button-payment-method').attr('data-loading-text');
			var text = $('#button-payment-method').attr('value');
			$('#button-payment-method').attr('value', loading).attr('data-loading-text', text);
		},
        success: function(json) {
            $('.alert-dismissible, .text-danger').remove();

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
               var loading = $('#button-payment-method').attr('data-loading-text');
				var text = $('#button-payment-method').attr('value');
				$('#button-payment-method').attr('value', loading).attr('data-loading-text', text);
                
                if (json['error']['warning']) {
                    $('#collapse-payment-method .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            } else {
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=confirm&nocache=' + new Date().getTime(),		
					dataType: 'html',
                    complete: function() {
                        var loading = $('#button-payment-method').attr('data-loading-text');
						var text = $('#button-payment-method').attr('value');
						$('#button-payment-method').attr('value', loading).attr('data-loading-text', text);
                    },
                    success: function(html) {
                        $('#collapse-checkout-confirm .panel-body').html(html);

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{DATA.text_checkout_confirm} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-checkout-confirm\']').trigger('click');
					},
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
</script>

<!-- END: main -->
