<!-- BEGIN: main -->
<div id="productcontent">
	<div class="container-fluid">
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
                <td><a href="http://opencart4x.vn/" target="_blank">Your Store</a></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="Ngày tạo" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                <td>09/05/2018</td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="Phương thức thanh toán" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
                <td>Cash On Delivery</td>
              </tr>
                        <tr>
              <td><button data-toggle="tooltip" title="Phương thức vận chuyển" class="btn btn-info btn-xs"><i class="fa fa-truck fa-fw"></i></button></td>
              <td>Free Shipping</td>
            </tr>
                          </tbody>
            
          </table>
        </div>
      </div>
      <div class="col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Thông tin khách hàng</h3>
          </div>
          <table class="table">
            <tr>
              <td style="width: 1%;"><button data-toggle="tooltip" title="Khách hàng" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
              <td> <a href="http://opencart4x.vn/admin/index.php?route=customer/customer/edit&amp;user_token=HXw1KflTEjAUv2gVVJSUVnVjj2ecCeFG&amp;customer_id=12" target="_blank">Đặng Hải Văn</a> </td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="Nhóm khách hàng" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
              <td>Default</td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="Địa chỉ E-Mail" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
              <td><a href="mailto:danghaivan@gmail.com">danghaivan@gmail.com</a></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="Điện thoại" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
              <td>0982912930</td>
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
                <td style="width: 1%;" class="text-center">                  <button id="button-invoice" data-loading-text="Đang nạp..." data-toggle="tooltip" title="Tạo ra" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>
                  </td>
              </tr>
              <tr>
                <td>Điểm thưởng</td>
                <td class="text-right">600</td>
                <td class="text-center">                                    <button id="button-reward-add" data-loading-text="Đang nạp..." data-toggle="tooltip" title="Thêm điểm thưởng" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                                    </td>
              </tr>
              <tr>
                <td>Đại lý
                  </td>
                <td class="text-right">0.00₫</td>
                <td class="text-center">                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> Đơn hàng (#5)</h3>
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
              <td class="text-left">Đặng Hải Văn<br />Phong Linh<br />Quảng Thanh - Thủy Nguyên - Hải Phòng<br />Quảng Thanh - Thủy Nguyên - Hải Phòng<br />Hải Phòng<br />Hai Phong<br />Viet Nam</td>
                            <td class="text-left">Đặng Hải Văn<br />Phong Linh<br />Quảng Thanh - Thủy Nguyên - Hải Phòng<br />Quảng Thanh - Thủy Nguyên - Hải Phòng<br />Hải Phòng<br />Hai Phong<br />Viet Nam</td>
               </tr>
          </tbody>
        </table>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left">Sản phẩm</td>
              <td class="text-left">Model</td>
              <td class="text-right">Số lượng</td>
              <td class="text-right">Đơn giá</td>
              <td class="text-right">Tổng</td>
            </tr>
          </thead>
          <tbody>
          
                    <tr>
            <td class="text-left"><a href="http://opencart4x.vn/admin/index.php?route=catalog/product/edit&amp;user_token=HXw1KflTEjAUv2gVVJSUVnVjj2ecCeFG&amp;product_id=43">MacBook</a> </td>
            <td class="text-left">Product 16</td>
            <td class="text-right">1</td>
            <td class="text-right">500.00₫</td>
            <td class="text-right">500.00₫</td>
          </tr>
                                        <tr>
            <td colspan="4" class="text-right">Sub-Total</td>
            <td class="text-right">500.00₫</td>
          </tr>
                    <tr>
            <td colspan="4" class="text-right">Free Shipping</td>
            <td class="text-right">0.00₫</td>
          </tr>
                    <tr>
            <td colspan="4" class="text-right">Total</td>
            <td class="text-right">500.00₫</td>
          </tr>
                      </tbody>
          
        </table>
         </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-comment-o"></i> Lịch sử đặt hàng</h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-history" data-toggle="tab">Lịch sử</a></li>
          <li><a href="#tab-additional" data-toggle="tab">Thêm vào</a></li>
                  </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend>Thêm Lịch sử đặt hàng</legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status">Tình trạng đơn đặt hàng</label>
                  <div class="col-sm-10">
                    <select name="order_status_id" id="input-order-status" class="form-control">
                      
                      
                                                                  
                      
                      <option value="7">Canceled</option>
                      
                      
                                                                                        
                      
                      <option value="9">Canceled Reversal</option>
                      
                      
                                                                                        
                      
                      <option value="13">Chargeback</option>
                      
                      
                                                                                        
                      
                      <option value="5">Complete</option>
                      
                      
                                                                                        
                      
                      <option value="8">Denied</option>
                      
                      
                                                                                        
                      
                      <option value="14">Expired</option>
                      
                      
                                                                                        
                      
                      <option value="10">Failed</option>
                      
                      
                                                                                        
                      
                      <option value="1" selected="selected">Pending</option>
                      
                      
                                                                                        
                      
                      <option value="15">Processed</option>
                      
                      
                                                                                        
                      
                      <option value="2">Processing</option>
                      
                      
                                                                                        
                      
                      <option value="11">Refunded</option>
                      
                      
                                                                                        
                      
                      <option value="12">Reversed</option>
                      
                      
                                                                                        
                      
                      <option value="3">Shipped</option>
                      
                      
                                                                                        
                      
                      <option value="16">Voided</option>
                      
                      
                                                                
                    
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-override"><span data-toggle="tooltip" title="Nếu đơn đặt hàng của khách hàng đang bị chặn không cho thay đổi trạng thái đơn đặt hàng do phần mở rộng chống gian lận cho phép ghi đè.">Ghi đè</span></label>
                  <div class="col-sm-10">
                    <div class="checkbox">
                      <input type="checkbox" name="override" value="1" id="input-override" />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-notify">Thông báo cho khách hàng</label>
                  <div class="col-sm-10">
                    <div class="checkbox">
                      <input type="checkbox" name="notify" value="1" id="input-notify" />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment">Ý kiến</label>
                  <div class="col-sm-10">
                    <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
              </form>
            </fieldset>
            <div class="text-right">
              <button id="button-history" data-loading-text="Đang nạp..." class="btn btn-primary"><i class="fa fa-plus-circle"></i> Thêm lịch sử đơn đặt hàng</button>
            </div>
          </div>
          <div class="tab-pane" id="tab-additional">                                     <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td colspan="2">Browser</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Địa chỉ IP</td>
                    <td>127.0.0.1</td>
                  </tr>
                                <tr>
                  <td>User Agent</td>
                  <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36</td>
                </tr>
                <tr>
                  <td>Chấp nhận ngôn ngữ</td>
                  <td>en-US,en;q=0.9,vi;q=0.8,ro;q=0.7</td>
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

$('#history').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime());

$('#button-history').on('click', function() {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime(),
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
				$('#history').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_info&action=history&order_id={DATA.order_id}&token={TOKEN}&nocache=' + new Date().getTime());

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