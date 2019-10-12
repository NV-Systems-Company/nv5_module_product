<!-- BEGIN: main -->
<div id="productcontent">
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.text_order_detail}</h3>
                <div class="pull-right">
                    <!-- <a href="#sale/order/invoice&amp;order_id=5" target="_blank" data-toggle="tooltip" class="btn btn-info btn-sm"  title="Print Invoice"><i class="fa fa-print"></i></a>  -->
					<!-- <a href="#sale/order/shipping&amp;order_id=5" target="_blank" data-toggle="tooltip" class="btn btn-info btn-sm"  title="Print Shipping List"><i class="fa fa-truck"></i></a> --> 
					<a href="{ORDER_EDIT}" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.edit}"><i class="fa fa-pencil"></i></a> 
					<a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>

            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-order" data-toggle="tab">{LANGE.text_order_detail}</a>
                    </li>
                    <li class=""><a href="#tab-payment" data-toggle="tab">{LANGE.text_payment_detail}</a>
                    </li>
                    <li class=""><a href="#tab-shipping" data-toggle="tab">{LANGE.text_shipping_detail}</a>
                    </li>
                    <li class=""><a href="#tab-product" data-toggle="tab">{LANGE.text_products_detail}</a>
                    </li>
                    <li class=""><a href="#tab-history" data-toggle="tab">{LANGE.text_history_detail}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-order">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Order ID:</td>
                                    <td>#{DATA.order_id}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_invoice_no}:</td>
                                    <td> {DATA.invoice_prefix} </td>
                                </tr>
								<tr>
                                    <td>{LANGE.text_store_name}:</td>
                                    <td> {DATA.store_name} </td>
                                </tr>
								<tr>
                                    <td>{LANGE.text_store_url}:</td>
                                    <td> <a href="{DATA.store_url}" target="_blank">{DATA.store_url}</td>
                                </tr>
								
								<!-- BEGIN: customer_user -->
                                <tr>
                                    <td>{LANGE.text_customer}:</td>
                                    <td><a href="{DATA.customer_url}" target="_blank">{DATA.first_name} {DATA.last_name}</a>
                                    </td>
                                </tr>
                                <!-- END: customer_user -->
								
								<!-- BEGIN: customer_guest -->
								<tr>
                                    <td>{LANGE.text_customer}:</td>
                                    <td>{DATA.first_name} {DATA.last_name}</td>
                                </tr>
								<!-- END: customer_guest -->
								
                                <tr>
                                    <td>{LANGE.text_customer_group}:</td>
                                    <td>{DATA.customer_group}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_email}:</td>
                                    <td><a href="mailto:{DATA.email}">{DATA.email}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_telephone}:</td>
                                    <td>{DATA.telephone}</td>
                                </tr>
                                <!-- BEGIN: fax -->
								<tr>
                                    <td>{LANGE.text_fax}:</td>
                                    <td>{DATA.fax}</td>
                                </tr>
								<!-- END: fax -->
                                <tr>
                                    <td>{LANGE.text_total}:</td>
                                    <td>{DATA.total}</td>
                                </tr>
								<!-- BEGIN: order_status -->
                                <tr>
                                    <td>{LANGE.text_order_status}:</td>
                                    <td id="order-status">{DATA.order_status}</td>
                                </tr>
								<!-- END: order_status -->
 
								<!-- BEGIN: reward -->
                                <tr>
									<td>{LANGE.text_reward}</td>
									<td> {DATA.reward}
									  <!-- BEGIN: add -->
									  <button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> {LANGE.text_reward_add}</button>
									  <!-- END: add -->
									  <!-- BEGIN: del -->
									  <button id="button-reward-remove" data-loading-text="loading" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> {LANGE.text_reward_delete}</button>
									   <!-- END: del -->
									</td>
								</tr>
								<!-- END: reward -->
                                <tr>
                                    <td>{LANGE.text_ip}:</td>
                                    <td>{DATA.ip}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_date_added}:</td>
                                    <td>{DATA.date_added}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_date_modified}:</td>
                                    <td>{DATA.date_modified}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab-payment">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>{LANGE.text_first_name}:</td>
                                    <td>{DATA.payment_first_name}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_last_name}:</td>
                                    <td>{DATA.payment_last_name}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_company}:</td>
                                    <td>{DATA.payment_company}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_address_1}:</td>
                                    <td>{DATA.payment_address_1}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_address_2}:</td>
                                    <td>{DATA.payment_address_2}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_city}:</td>
                                    <td>{DATA.payment_city}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_postcode}:</td>
                                    <td>{DATA.payment_postcode}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_zone}:</td>
                                    <td>{DATA.payment_zone}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_zone_code}:</td>
                                    <td>{DATA.payment_zone_code}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_country}:</td>
                                    <td>{DATA.payment_country}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_payment_method}:</td>
                                    <td>{DATA.payment_method}</td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab-shipping">
						<table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>{LANGE.text_first_name}:</td>
                                    <td>{DATA.shipping_first_name}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_last_name}:</td>
                                    <td>{DATA.shipping_last_name}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_company}:</td>
                                    <td>{DATA.shipping_company}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_address_1}:</td>
                                    <td>{DATA.shipping_address_1}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_address_2}:</td>
                                    <td>{DATA.shipping_address_2}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_city}:</td>
                                    <td>{DATA.shipping_city}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_postcode}:</td>
                                    <td>{DATA.shipping_postcode}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_zone}:</td>
                                    <td>{DATA.shipping_zone}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_zone_code}:</td>
                                    <td>{DATA.shipping_zone_code}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_country}:</td>
                                    <td>{DATA.shipping_country}</td>
                                </tr>
                                <tr>
                                    <td>{LANGE.text_shipping_method}:</td>
                                    <td>{DATA.shipping_method}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab-product">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td class="text-left">{LANGE.column_product}</td>
                                    <td class="text-left">{LANGE.column_model}</td>
                                    <td class="text-right">{LANGE.column_quantity}</td>
                                    <td class="text-right">{LANGE.column_price}</td>
                                    <td class="text-right">{LANGE.column_total}</td>
                                </tr>
                            </thead>
                            <tbody>
								<!-- BEGIN: product -->
                                <tr>
                                    <td class="text-left"><a href="{PRODUCT.href}">{PRODUCT.name}</a>
                                        <!-- BEGIN: color -->
										<br /> - <small>Color: {COLOR.name}</small>
										<!-- END: color -->
										<!-- BEGIN: option -->
										<br /> - <small>{OPTION.name} {OPTION.value}</small>
										<!-- END: option -->	
						 
						 
										<!-- BEGIN: display_group -->
										<p>
											<!-- BEGIN: group -->
											<span style="margin-right: 10px"><span class="text-muted">{group}</span></span>
											<!-- END: group -->
										</p>
										<!-- END: display_group -->
                                    </td>
                                    <td class="text-left">{PRODUCT.model}</td>
                                    <td class="text-right">{PRODUCT.quantity}</td>
                                    <td class="text-right">{PRODUCT.price}</td>
                                    <td class="text-right">{PRODUCT.total}</td>
                                </tr>
								<!-- END: product -->
								<!-- BEGIN: total -->
                                <tr>
                                    <td colspan="4" class="text-right">{TOTAL.title}:</td>
                                    <td class="text-right">{TOTAL.text}</td>
                                </tr>
								<!-- END: total -->
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab-history">
                        <div id="history">
 
                        </div>
                        <br>
                        <fieldset>
                            <legend>{LANGE.text_history}</legend>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-order-status">{LANGE.entry_order_status}</label>
                                    <div class="col-sm-20">
                                        <select name="order_status_id" id="input-order-status" class="form-control input-sm">
                                             <!-- BEGIN: order_statuses -->
											<option value="{ORDER_STATUS.order_status_id}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
											<!-- END: order_statuses -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-notify">{LANGE.entry_notify}</label>
                                    <div class="col-sm-20">
                                        <input type="checkbox" name="notify" value="1" id="input-notify">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-comment">{LANGE.entry_comment}</label>
                                    <div class="col-sm-20">
                                        <textarea name="comment" rows="8" id="input-comment" class="form-control input-sm"></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="text-right">
                                <button id="button-history" data-loading-text="{LANG.button_loading}..." class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANGE.entry_history_add}</button>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
 
    $(document).delegate('#button-reward-add', 'click', function() {
        $.ajax({
            url: 'index.php?route=sale/order/addreward&token=&order_id=5',
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward-add').button('loading');
            },
            complete: function() {
                $('#button-reward-add').button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> Remove Reward Points</button>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $(document).delegate('#button-reward-remove', 'click', function() {
        $.ajax({
            url: 'index.php?route=sale/order/removereward&token=&order_id=5',
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward-remove').button('loading');
            },
            complete: function() {
                $('#button-reward-remove').button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> Add Reward Points</button>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

 
   // $('#history').delegate('.pagination a', 'click', function(e) {
     //   e.preventDefault();

    //    $('#history').load(this.href);
   // });

    $('#history').load('{ORDER_HISTORY}');

    $('#button-history').on('click', function() {
        if (typeof verifyStatusChange == 'function') {
            if (verifyStatusChange() == false) {
                return false;
            } else {
                addOrderInfo();
            }
        } else {
            addOrderInfo();
        }

        $.ajax({
            url: '{add_history}',
            type: 'post',
            dataType: 'json',
            data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
            beforeSend: function() {
                //$('#button-history').button('loading');
            },
            complete: function() {
                //$('#button-history').button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');
                }

                if (json['success']) {
                    $('#history').load('{ORDER_HISTORY}');

                    $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <i class="fa fa-times"></i></div>');

                    $('textarea[name=\'comment\']').val('');

                    $('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    function changeStatus() {
        var status_id = $('select[name="order_status_id"]').val();

        $('#openbay-info').remove();

        $.ajax({
            url: '{order_history}&status_id=' + status_id,
            dataType: 'html',
            success: function(html) {
                $('#history').after(html);
            }
        });
    }

    function addOrderInfo() {
        var status_id = $('select[name="order_status_id"]').val();

        $.ajax({
            url: '{order_history}&status_id=' + status_id,
            type: 'post',
            dataType: 'html',
            data: $(".openbay-data").serialize()
        });
    }

    $(document).ready(function() {
      // changeStatus();
    });

    $('select[name="order_status_id"]').change(function() {
       // changeStatus();
    });
</script>
<script type="text/javascript"> 
$(document).ready(function() {
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});
});
</script>
<!-- END: main -->