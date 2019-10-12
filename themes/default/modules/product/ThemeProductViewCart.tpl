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
	<!-- BEGIN: extension -->
    <h2>{LANGE.text_next}</h2>
    <p>{LANGE.text_next_choice}</p>
	<div class="panel-group" id="accordion">        
		<!-- BEGIN: extension_loop --> {EXTENSION} <!-- END: extension_loop -->
	</div>
    <br>
	<!-- END: extension -->
	<!-- BEGIN: total -->
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
        <div class="pull-right"><a href="{DATA.checkout}" class="btn btn-primary  btn-sm">{LANGE.entry_checkout}</a></div>
    </div>
</div> 
<!-- END: main -->
