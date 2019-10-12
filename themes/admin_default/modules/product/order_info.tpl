<!-- BEGIN: main -->
<div id="productcontent">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> THÔNG TIN ĐƠN HÀNG</h3>
				<div class="pull-right">
				<a href="http://opencart4x.vn/admin/index.php?route=sale/order/invoice&amp;user_token=M2FDnwBEDYNsoyU7Y1cQKDsY6s8Bfa98&amp;order_id=5" target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="In hóa đơn"><i class="fa fa-print"></i></a> 
				<a href="http://opencart4x.vn/admin/index.php?route=sale/order/shipping&amp;user_token=M2FDnwBEDYNsoyU7Y1cQKDsY6s8Bfa98&amp;order_id=5" target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="In danh sách đặt hàng"><i class="fa fa-truck"></i></a> 
				<a href="http://opencart4x.vn/admin/index.php?route=sale/order/edit&amp;user_token=M2FDnwBEDYNsoyU7Y1cQKDsY6s8Bfa98&amp;order_id=5" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a> 
				<a href="http://opencart4x.vn/admin/index.php?route=sale/order&amp;user_token=M2FDnwBEDYNsoyU7Y1cQKDsY6s8Bfa98" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Hủy"><i class="fa fa-reply"></i></a></div>
				<div style="clear:both"></div>
			</div>
		</div>	
			<div class="row">
			  <div class="col-md-8">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Chi tiết đơn hàng</h3>
				  </div>
				  <table class="table">
					<tbody>
					<tr>
						<td style="width: 1%;"><button data-toggle="tooltip" title="Cửa hàng" class="btn btn-info btn-xs"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
						<td><a href="{DATA.store_url}" target="_blank">{DATA.store_name}</a></td>
					</tr>
					<tr>
						<td><button data-toggle="tooltip" title="Ngày tạo" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
						<td>{DATA.date_added}</td>
					</tr>
					<!-- BEGIN: payment_method -->
					<tr>
						<td><button data-toggle="tooltip" title="Hình thức thanh toán" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
						<td>{DATA.payment_method}</td>
					</tr>
					<!-- END: payment_method -->
					<!-- BEGIN: shipping_method -->
					<tr>
						<td><button data-toggle="tooltip" title="Hình thức vận chuyển" class="btn btn-info btn-xs"><i class="fa fa-truck fa-fw"></i></button></td>
						<td>{DATA.shipping_method}</td>
					</tr>
					<!-- END: shipping_method -->
					</tbody>
					
				  </table>
				</div>
			  </div>
			  <div class="col-md-8">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-user"></i> {LANGE.text_customer_detail}</h3>
				  </div>
				  <table class="table">
					<!-- BEGIN: user -->
					<tr>
					  <td style="width: 1%;"><button data-toggle="tooltip" title="Khách hàng" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
					  <td> <a href="{DATA.customer_url}" target="_blank">{DATA.last_name} {DATA.first_name}</a> </td>
					</tr>
					<!-- END: user -->
					<!-- BEGIN: guest -->
					<tr>
					  <td style="width: 1%;"><button data-toggle="tooltip" title="Khách hàng" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
					  <td>{DATA.last_name} {DATA.first_name}</td>
					</tr>
					<!-- END: guest -->
					<tr>
					  <td><button data-toggle="tooltip" title="Nhóm khách hàng" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
					  <td>{DATA.customer_group}</td>
					</tr>
					<tr>
					  <td><button data-toggle="tooltip" title="Địa chỉ E-Mail" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
					  <td><a href="mailto:{DATA.email}">{DATA.email}</a></td>
					</tr>
					<tr>
					  <td><button data-toggle="tooltip" title="Điện thoại" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
					  <td>{DATA.telephone}</td>
					</tr>
				  </table>
				</div>
			  </div>
			  <div class="col-md-8">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-cog"></i> Tùy chọn</h3>
				  </div>
				  <table class="table">
					<tbody>
					  <tr>
						<td>Hóa đơn</td>
						<td id="invoice" class="text-right"></td>
						<td style="width: 1%;" class="text-center"> <button id="button-invoice" data-loading-text="Đang nạp..." data-toggle="tooltip" title="Tạo ra" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>
						  </td>
					  </tr>
					  <tr>
						<td>Điểm thưởng</td>
						<td class="text-right">600</td>
						<td class="text-center"> <button id="button-reward-add" data-loading-text="Đang nạp..." data-toggle="tooltip" title="Thêm điểm thưởng" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button></td>
					  </tr>
					  <tr>
						<td>Đại lý  </td>
						<td class="text-right">0.00₫</td>
						<td class="text-center"> <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button></td>
					  </tr>
					</tbody>
				  </table>
				</div>
			  </div>
			</div>
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-info-circle"></i> <strong>Đơn hàng (#{DATA.invoice_prefix})</strong></h3>
			  </div>
			  <div class="panel-body">
				<table class="table table-bordered">
				  <thead>
					<tr>
						<td style="width: 50%;" class="text-left">Địa chỉ thanh toán</td>
						<td style="width: 50%;" class="text-left">Địa chỉ giao hàng</td>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td class="text-left">{DATA.payment_last_name} {DATA.payment_first_name}<br />{DATA.payment_company}<br />{DATA.payment_address_1}<br />{DATA.payment_address_2}<br />{DATA.payment_city}<br />{DATA.payment_zone}<br />{DATA.payment_country}</td>
						<td class="text-left">{DATA.shipping_last_name} {DATA.shipping_first_name}<br />{DATA.shipping_company}<br />{DATA.shipping_address_1}<br />{DATA.shipping_address_2}<br />{DATA.shipping_city}<br />{DATA.shipping_zone}<br />{DATA.shipping_country}</td>
					</tr>
				  </tbody>
				</table>
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <td class="text-left"><strong>SẢN PHẨM</strong></td>
					  <td class="text-left"><strong>MÃ SP</strong></td>
					  <td class="text-right"><strong>SỐ LƯỢNG</strong></td>
					  <td class="text-right"><strong>ĐƠN GIÁ</strong></td>
					  <td class="text-right"><strong>TỔNG TIỀN</strong></td>
					</tr>
				  </thead>
				  <tbody>
					<!-- BEGIN: product -->
					<tr>
						<td class="text-left"><a href="{PRODUCT.href}">{PRODUCT.name}</a> 
						<!-- BEGIN: option -->
						<br />
						&nbsp;<small> - {OPTION.name}: {OPTION.value}</small>
						<!-- END: option -->
						</td>
						<td class="text-left">{PRODUCT.model}</td>
						<td class="text-right">{PRODUCT.quantity}</td>
						<td class="text-right">{PRODUCT.price}</td>
						<td class="text-right">{PRODUCT.total}</td>
					</tr>
					<!-- END: product -->
					<!-- BEGIN: total -->
					<tr>
						<td colspan="4" class="text-right">{TOTAL.title}</td>
						<td class="text-right">{TOTAL.text}</td>
					</tr>
					<!-- END: total -->
					</tbody>
					
				</table>
				<!-- BEGIN: comment -->
				<table class="table table-bordered">
				  <thead>
					<tr>
					  <td>{LANGE.text_comment}</td>
					</tr>
				  </thead>
				  <tbody>
					<tr>
					  <td>{DATA.comment}</td>
					</tr>
				  </tbody>
				</table>
				<!-- END: comment -->
				 </div>
			</div>
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-comment-o"></i> Lịch sử đặt hàng</h3>
			  </div>
			  <div class="panel-body">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab-history" data-toggle="tab">Lịch sử</a></li>
				  <li><a href="#tab-additional" data-toggle="tab">Thông tin bổ sung</a></li>
						  </ul>
				<div class="tab-content">
				  <div class="tab-pane active" id="tab-history">
					<div id="history"></div>
					<br />
					<fieldset>
					  <legend>Thêm Lịch sử đặt hàng</legend>
					  <form class="form-horizontal">
						<div class="form-group">
						  <label class="col-sm-4 control-label" for="input-order-status">Tình trạng đơn đặt hàng</label>
						  <div class="col-sm-20">
							<select name="order_status_id" id="input-order-status" class="form-control">
								<!-- BEGIN: order_statuses -->
								<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
								<!-- END: order_statuses -->
							</select>
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-4 control-label" for="input-override"><span data-toggle="tooltip" title="Nếu đơn đặt hàng của khách hàng đang bị chặn không cho thay đổi trạng thái đơn đặt hàng do phần mở rộng chống gian lận cho phép ghi đè.">Ghi đè</span></label>
						  <div class="col-sm-20">
							<div class="checkbox">
							  <input type="checkbox" name="override" value="1" id="input-override" />
							</div>
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-4 control-label" for="input-notify">Thông báo cho khách hàng</label>
						  <div class="col-sm-20">
							<div class="checkbox">
							  <input type="checkbox" name="notify" value="1" id="input-notify" />
							</div>
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-4 control-label" for="input-comment">Ý kiến</label>
						  <div class="col-sm-20">
							<textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
						  </div>
						</div>
					  </form>
					</fieldset>
					<div class="text-right">
					  <button id="button-history" data-loading-text="Đang nạp..." class="btn btn-primary"><i class="fa fa-plus-circle"></i> Thêm lịch sử đơn đặt hàng</button>
					</div>
				  </div>
				  <div class="tab-pane" id="tab-additional"> 
					<div class="table-responsive">
					  <table class="table table-bordered">
						<thead>
						  <tr>
							<td colspan="2">Browser</td>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td>Địa chỉ IP</td>
							<td>{DATA.ip}</td>
						  </tr>
							<tr>
							  <td>User Agent</td>
							  <td>{DATA.user_agent}</td>
							</tr>
							<tr>
							  <td>Chấp nhận ngôn ngữ</td>
							  <td>{DATA.accept_language}</td>
							</tr>
						</tbody>
					  </table>
					</div>
				  </div>
				   </div>
			  </div>
			
		</div>
    </div>
  <script type="text/javascript"><!--
$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=createinvoiceno&token={TOKEN}&nocache=' + new Date().getTime(),
		dataType: 'json',
		beforeSend: function() {
			var loading = $('#button-invoice').attr('data-loading-text');
			var text = $('#button-invoice').attr('value');
			$('#button-invoice').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-invoice').attr('data-loading-text');
			var text = $('#button-invoice').attr('value');
			$('#button-invoice').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#productcontent > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['invoice_no']) {
				$('#invoice').html(json['invoice_no']);

				$('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-add', 'click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=addreward&token={TOKEN}&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			var loading = $('#button-reward-add').attr('data-loading-text');
			var text = $('#button-reward-add').attr('value');
			$('#button-reward-add').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-reward-add').attr('data-loading-text');
			var text = $('#button-reward-add').attr('value');
			$('#button-reward-add').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#productcontent > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#productcontent > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-add').replaceWith('<button id="button-reward-remove" data-toggle="tooltip" title="Xóa điểm thưởng" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-remove', 'click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=removereward&token={TOKEN}&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			var loading = $('#button-reward-remove').attr('data-loading-text');
			var text = $('#button-reward-remove').attr('value');
			$('#button-reward-remove').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-reward-remove').attr('data-loading-text');
			var text = $('#button-reward-remove').attr('value');
			$('#button-reward-remove').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#productcontent > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#productcontent > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-remove').replaceWith('<button id="button-reward-add" data-toggle="tooltip" title="Thêm điểm thưởng" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-add', 'click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=addcommission&token={TOKEN}&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-add').button('loading');
		},
		complete: function() {
			var loading = $('#button-commission-add').attr('data-loading-text');
			var text = $('#button-commission-add').attr('value');
			$('#button-commission-add').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#productcontent > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#productcontent > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-add').replaceWith('<button id="button-commission-remove" data-toggle="tooltip" title="Gỡ bỏ hoa hồng" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-remove', 'click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=removecommission&token={TOKEN}&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			var loading = $('#button-commission-remove').attr('data-loading-text');
			var text = $('#button-commission-remove').attr('value');
			$('#button-commission-remove').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-commission-remove').attr('data-loading-text');
			var text = $('#button-commission-remove').attr('value');
			$('#button-commission-remove').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#productcontent > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#productcontent > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-remove').replaceWith('<button id="button-commission-add" data-toggle="tooltip" title="Thêm hoa hồng" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime());

$('#button-history').on('click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&override=' + ($('input[name=\'override\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			var loading = $('#button-history').attr('data-loading-text');
			var text = $('#button-history').attr('value');
			$('#button-history').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-history').attr('data-loading-text');
			var text = $('#button-history').attr('value');
			$('#button-history').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			$('.alert-dismissible').remove();

			if (json['error']) {
				$('#history').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#history').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime());

				$('#history').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('textarea[name=\'comment\']').val('');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script> 
</div>
<!-- END: main -->