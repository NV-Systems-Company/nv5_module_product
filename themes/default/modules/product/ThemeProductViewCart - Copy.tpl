<!-- BEGIN: main -->
<div id="ProductContent">
    <h1>Giỏ hàng của bạn</h1>
    <form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="update">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center"><strong>{LANGE.column_image}</strong></td>
                        <td class="text-left"><strong>{LANGE.column_name}</strong></td>
                        <td class="text-left"><strong>{LANGE.column_model}</strong></td>
                        <td class="text-left"><strong>{LANGE.column_quantity}</strong></td>
                        <td class="text-right"><strong>{LANGE.column_price}</strong></td>
                        <td class="text-right"><strong>{LANGE.column_total}</strong></td>
                    </tr>
                </thead>
                <tbody>
					<!-- BEGIN: product -->
                    <tr>
                        <td class="text-center">
                            <a href="{PRODUCT.link}"><img src="{PRODUCT.thumb}" alt="{PRODUCT.name}" title="{PRODUCT.name}" class="img-thumbnail" style="max-width:100px"></a>
                        </td>
                        <td class="text-left"><a href="{PRODUCT.link}">{PRODUCT.name}</a>
                            <!-- BEGIN: out_of_stock -->
							<span class="text-danger">***</span>
							<!-- END: out_of_stock -->
							<!-- BEGIN: option -->
							<br>
                            <small>{OPTION.name}: {OPTION.value}</small>
							<!-- END: option -->
                        </td>
                        <td class="text-left">{PRODUCT.model}</td>
                        <td class="text-left">
                            <div class="input-group btn-block" style="max-width: 200px;"> 
                                <input type="text" name="quantity[{PRODUCT.cart_id}]" value="{PRODUCT.quantity}" size="1" class="form-control">
                                <span class="input-group-btn">
									<button type="submit" data-toggle="tooltip" class="btn btn-primary" title="Cập nhật"><i class="fa fa-refresh"></i></button>
									<button type="button" data-toggle="tooltip" class="btn btn-danger" onclick="cart.remove('{PRODUCT.cart_id}');" title="Xóa"><i class="fa fa-times-circle"></i></button>
								</span>
							</div>
                        </td>
                        <td class="text-right">{PRODUCT.price}</td>
                        <td class="text-right">{PRODUCT.total}</td>
                    </tr>
					<!-- END: product -->
					<!-- BEGIN: voucher -->
					<tr>
						<td></td>
						<td class="text-left">{VOUCHER.description}</td>
						<td class="text-left"></td>
						<td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
							<input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
							<span class="input-group-btn">
							<button type="button" data-toggle="tooltip" title="{LANG.button_remove}" class="btn btn-danger" onclick="voucher.remove('{VOUCHER.key}');"><i class="fa fa-times-circle"></i></button>
							</span></div></td>
						<td class="text-right">{VOUCHER.amount}</td>
						<td class="text-right">{VOUCHER.amount}</td>
					</tr>
					<!-- END: voucher -->
                </tbody>
            </table>
        </div>
    </form>
    <h2>{LANGE.text_next}</h2>
    <p>{LANGE.text_next_choice}</p>
    <!-- BEGIN: module -->
	<div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">Sử dụng mã giảm giá <i class="fa fa-caret-down"></i></a></h4>
            </div>
            <div id="collapse-coupon" class="panel-collapse collapse">
                <div class="panel-body">
                    <label class="col-sm-5 control-label" for="input-coupon">Nhập mã giảm giá tại đây</label>
                    <div class="input-group">
                        <input type="text" name="coupon" value="" placeholder="Nhập mã giảm giá tại đây" id="input-coupon" class="form-control" />
                        <span class="input-group-btn">
							<input type="button" value="Sử dụng mã giảm này" id="button-coupon" data-loading-text="Đang tải..." class="btn btn-primary">
						</span>
					</div>
                    <script type="text/javascript">
                        $('#button-coupon').on('click', function() {
                            $.ajax({
                                //url: 'index.php?route=extension/total/coupon/coupon',
                                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cart&action=coupon&nocache=' + new Date().getTime(),
                                type: 'post',
                                data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
                                dataType: 'json',
                                beforeSend: function() {
									var loading = $('#button-coupon').attr('data-loading-text');
									var text = $('#button-coupon').attr('value');
                                    $('#button-coupon').attr('value', loading).attr('data-loading-text', text);
                                },
                                complete: function() {
									var loading = $('#button-coupon').attr('data-loading-text');
									var text = $('#button-coupon').attr('value');
                                    $('#button-coupon').attr('value', loading).attr('data-loading-text', text);
                                },
                                success: function(json) {
                                    $('.alert').remove();
                                    if (json['error']) {
                                        $('#ProductContent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                        $('html, body').animate({
                                            scrollTop: 0
                                        }, 'slow');
                                    }
                                    if (json['redirect']) {
                                        //location = json['redirect'];
                                    }
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Sử dụng Voucher <i class="fa fa-caret-down"></i></a></h4>
            </div>
            <div id="collapse-voucher" class="panel-collapse collapse">
                <div class="panel-body">
                    <label class="col-sm-5 control-label" for="input-voucher">Nhập mã voucher</label>
                    <div class="input-group">
                        <input type="text" name="voucher" value="" placeholder="Nhập mã voucher tại đây" id="input-voucher" class="form-control">
                        <span class="input-group-btn">
							<input type="submit" value="Sử dụng voucher" id="button-voucher" data-loading-text="Đang tải..." class="btn btn-primary">
						</span> 
					</div>
                    <script type="text/javascript">
                       
                        $('#button-voucher').on('click', function() {
                            $.ajax({
                                //url: 'index.php?route=extension/total/voucher/voucher',
                                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cart&action=voucher&nocache=' + new Date().getTime(),
                                type: 'post',
                                data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
                                dataType: 'json',
                                beforeSend: function() {
                                    var loading = $('#button-voucher').attr('data-loading-text');
									var text = $('#button-voucher').attr('value');
                                    $('#button-voucher').attr('value', loading).attr('data-loading-text', text);
                                },
                                complete: function() {
                                    var loading = $('#button-voucher').attr('data-loading-text');
									var text = $('#button-voucher').attr('value');
                                    $('#button-voucher').attr('value', loading).attr('data-loading-text', text);
                                },
                                success: function(json) {
                                    $('.alert').remove();
                                    if (json['error']) {
                                        $('#ProductContent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                        $('html, body').animate({
                                            scrollTop: 0
                                        }, 'slow');
                                    }
                                    if (json['redirect']) {
                                        location.href = json['redirect'];
                                    }
                                }
                            });
                        });
                         
                    </script>
                </div>
            </div>
        </div>
    
		{MODULE}
	
	</div>
	<!-- BEGIN: module -->
    <br>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-16">
            <table class="table table-bordered">
                <tbody>
					<!-- BEGIN: loop_total -->
                    <tr>
                        <td class="text-right"><strong>{TOTAL.title}:</strong></td>
                        <td class="text-right">{TOTAL.value}</td>
                    </tr>
                     <!-- END: loop_total -->
                </tbody>
            </table>
        </div>
    </div>
	<!-- END: total -->
    <div class="buttons clearfix">
        <div class="pull-left"><a href="{DATA.continue}" class="btn btn-default btn-sm">{LANGE.entry_continue}</a></div>
        <div class="pull-right"><a href="{LANG.checkout}" class="btn btn-primary  btn-sm">{LANGE.entry_checkout}</a></div>
    </div>
</div> 
<!-- END: main -->
