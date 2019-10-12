<!-- BEGIN: main -->

<!-- BEGIN: warning -->
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>{WARNING}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<!-- END: warning -->

<div id="content" class="col-sm-24">
    <h1>{LANGE.heading_title}</h1>
    <div class="panel-group" id="accordion">
        
		
		<div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">{LANGE.text_checkout_option}</h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-checkout-option">
            <div class="panel-body"></div>
          </div>
        </div>
		
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{LANGE.text_checkout_account}</h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-payment-address">
                <div class="panel-body"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{LANGE.text_checkout_shipping_address}</h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-shipping-address">
                <div class="panel-body"></div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{LANGE.text_checkout_shipping_method}</h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-shipping-method">
                <div class="panel-body"></div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{LANGE.text_checkout_payment_method}</h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-payment-method">
                <div class="panel-body"></div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">{LANGE.text_checkout_confirm}</h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-checkout-confirm">
                <div class="panel-body"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function addloading() 
{	
	$('#shops-loading').show();
	$('#shops-overlay').show();
}
</script>
<script type="text/javascript">
 
    $(document).on('change', 'input[name=\'account\']', function() {
        if ($('#collapse-payment-address').parent().find('.panel-heading .panel-title > *').is('a')) {
            if (this.value == 'register') {
                $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_account} <i class="fa fa-caret-down"></i></a>');
            } else {
                $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
            }
        } else {
            if (this.value == 'register') {
                $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_account}');
            } else {
                $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_address}');
            }
        }
    });

	
	<!-- BEGIN: script_guest -->
    $(document).ready(function() {
        $.ajax({
            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&login&nocache=' + new Date().getTime(),
            dataType: 'html',
            success: function(html) {
                $('#collapse-checkout-option .panel-body').html(html);

                $('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_option} <i class="fa fa-caret-down"></i></a>');

                $('a[href=\'#collapse-checkout-option\']').trigger('click');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
	<!-- END: script_guest -->
	
	<!-- BEGIN: script_user -->
    $(document).ready(function() {
		$.ajax({
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_address&nocache=' + new Date().getTime(),
            dataType: 'html',
			success: function(html) {
				$('#collapse-payment-address .panel-body').html(html);
				
				$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_account} <i class="fa fa-caret-down"></i></a>');

				$('a[href=\'#collapse-payment-address\']').trigger('click');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	<!-- END: script_user -->
	

    // Checkout
    $(document).delegate('#button-account', 'click', function() {
        $.ajax({
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&'+$('input[name=\'account\']:checked').val()+'&nocache=' + new Date().getTime(),
            dataType: 'html',
            beforeSend: function() {
                $('#button-account').button('loading');
            },
            complete: function() {
                $('#button-account').button('reset');
            },
            success: function(html) {
                $('.alert, .text-danger').remove();

                $('#collapse-payment-address .panel-body').html(html);

                if ($('input[name=\'account\']:checked').val() == 'register') {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_account} <i class="fa fa-caret-down"></i></a>');
                } else {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
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
            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&login&save&zone&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-checkout-option :input'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-login').button('loading');
            },
            complete: function() {
                $('#button-login').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#collapse-checkout-option .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

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
			 url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&register&save&nocache=' + new Date().getTime(),
			 type: 'post',
			 data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address textarea, #collapse-payment-address select'),
			 dataType: 'json',
			 beforeSend: function() {
				 $('#button-register').button('loading');
			 },
			 complete: function() {
				 $('#button-register').button('reset');
			 },
			 success: function(json) {
				 $('.alert, .text-danger').remove();
				 $('.form-group').removeClass('has-error');

				 if (json['redirect']) {
					 location = json['redirect'];
				 } else if (json['error']) {
					 if (json['error']['warning']) {
						 $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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

					 var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');

					 if (shipping_address) {
						 $.ajax({
							 url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_method&nocache=' + new Date().getTime(),
							 dataType: 'html',
							 success: function(html) {
								 // Add the shipping address
								 $.ajax({
									 url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_address&nocache=' + new Date().getTime(),
									 dataType: 'html',
									 success: function(html) {
										 $('#collapse-shipping-address .panel-body').html(html);

										 $('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');
									 },
									 error: function(xhr, ajaxOptions, thrownError) {
										 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									 }
								 });

								 $('#collapse-shipping-method .panel-body').html(html);

								 $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

								 $('a[href=\'#collapse-shipping-method\']').trigger('click');

								 $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('Step 4: Delivery Method');
								 $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('Step 5: Payment Method');
								 $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('Step 6: Confirm Order');
							 },
							 error: function(xhr, ajaxOptions, thrownError) {
								 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							 }
						 });
					 } else {
						 $.ajax({
							 url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_address&nocache=' + new Date().getTime(),
							 dataType: 'html',
							 success: function(html) {
								 $('#collapse-shipping-address .panel-body').html(html);

								 $('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

								 $('a[href=\'#collapse-shipping-address\']').trigger('click');

								 $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_shipping_method}');
								 $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
								 $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('LANGE.text_checkout_confirm');
							 },
							 error: function(xhr, ajaxOptions, thrownError) {
								 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							 }
						 });
					 }

					 $.ajax({
						 url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_address&nocache=' + new Date().getTime(),
						 dataType: 'html',
						 success: function(html) {
							 $('#collapse-payment-address .panel-body').html(html);

							 $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_payment_address} <i class="fa fa-caret-down"></i></a>');
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
            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_address&save&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-address').button('loading');
            },
            complete: function() {
                $('#button-payment-address').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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
                    $.ajax({
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_address&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-shipping-address .panel-body').html(html);

                            $('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-shipping-address\']').trigger('click');

                            $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_shipping_method}');
                            $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
                            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    $.ajax({
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_address&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-payment-address .panel-body').html(html);
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

    // Shipping Address         
    $(document).delegate('#button-shipping-address', 'click', function() {
        $.ajax({
            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_address&save&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-address').button('loading');
            },
            complete: function() {
                $('#button-shipping-address').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_method&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-shipping-method .panel-body').html(html);

                            $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-shipping-method\']').trigger('click');

                            $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
                            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');

                            $.ajax({
								url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_address&nocache=' + new Date().getTime(),
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
                    });

                    $.ajax({
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_address&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-payment-address .panel-body').html(html);
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

    // Guest
    $(document).delegate('#button-guest', 'click', function() {
        $.ajax({
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&guest&save&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-guest').button('loading');
            },
            complete: function() {
                $('#button-guest').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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

                    var shipping_address = $('#collapse-payment-address input[name=\'shipping_address\']:checked').prop('value');

                    if (shipping_address) {
                        $.ajax({
							url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_method&nocache=' + new Date().getTime(),
                            dataType: 'html',
                            success: function(html) {
                                // Add the shipping address
                                $.ajax({
									url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&guest_shipping&nocache=' + new Date().getTime(),
                                    dataType: 'html',
                                    success: function(html) {
                                        $('#collapse-shipping-address .panel-body').html(html);

                                        $('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                });

                                $('#collapse-shipping-method .panel-body').html(html);

                                $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_method} <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-shipping-method\']').trigger('click');

                                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
                                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    } else {
                        $.ajax({
							url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&guest_shipping&nocache=' + new Date().getTime(),
                            dataType: 'html',
                            success: function(html) {
                                $('#collapse-shipping-address .panel-body').html(html);

                                $('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_address} <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-shipping-address\']').trigger('click');

                                $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_shipping_method}');
                                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
                                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    }
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
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&guest_shipping&save&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-guest-shipping').button('loading');
            },
            complete: function() {
                $('#button-guest-shipping').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
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
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_method&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-shipping-method .panel-body').html(html);

                            $('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_shipping_method} <i class="fa fa-caret-down"></i>');

                            $('a[href=\'#collapse-shipping-method\']').trigger('click');

                            $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_payment_method}');
                            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');
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
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&shipping_method&save&nocache=' + new Date().getTime(),
            type: 'post',
            data: $('#collapse-shipping-method input[type=\'radio\']:checked, #collapse-shipping-method textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-method').button('loading');
            },
            complete: function() {
                $('#button-shipping-method').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-shipping-method .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {
                    $.ajax({
						url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_method&nocache=' + new Date().getTime(),
                        dataType: 'html',
                        success: function(html) {
                            $('#collapse-payment-method .panel-body').html(html);

                            $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_payment_method} <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-payment-method\']').trigger('click');

                            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{LANGE.text_checkout_confirm}');
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
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&payment_method&save&nocache=' + new Date().getTime(),                
            type: 'post',
            data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-method').button('loading');
            },
            complete: function() {
                $('#button-payment-method').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-payment-method .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {
                    $.ajax({
                        url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&confirm&nocache=' + new Date().getTime(),                
						dataType: 'html',
                        success: function(html) {
                            $('#collapse-checkout-confirm .panel-body').html(html);

                            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.text_checkout_confirm} <i class="fa fa-caret-down"></i></a>');

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