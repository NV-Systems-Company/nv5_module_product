<!-- BEGIN: main -->
<div class="row">
<!-- BEGIN: success -->
	<div class="alert alert-success">
		<i class="fa fa-check-circle"></i> {SUCCESS}
		<button type="button" class="close" data-dismiss="alert">×</button>
    </div>
	<!-- END: success --> 
<!-- BEGIN: data --> 
<div id="content" class="col-sm-24">
	<!-- BEGIN: error_warning -->
	 <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {ERROR}
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	  </div>
	<!-- END: error_warning -->
    <!-- <h1>Shopping Cart &nbsp;(5.00kg) </h1> -->
    <form action="{LINK_CART}" method="post" enctype="multipart/form-data" id="fpro">
	<input type="hidden" value="1" name="update"/>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center">{LANGE.column_image}</td>
                        <td class="text-left">{LANGE.column_name}</td>
                        <td class="text-left">{LANGE.column_model}</td>
                        <td class="text-left">{LANGE.column_quantity}</td>
                        <td class="text-right">{LANGE.column_price}</td>
                        <td class="text-right">{LANGE.column_total}</td>
                    </tr>
                </thead>
				
                <tbody id="{id}" {bg}>
					<!-- BEGIN: product -->
                    <tr rel="{PRODUCT.key}">
                        <td class="text-center">
                            <!-- BEGIN: thumb -->
							<a href="{PRODUCT.link}"><img src="{PRODUCT.thumb}" alt="{PRODUCT.name}" title="{PRODUCT.name}" class="img-thumbnail" /></a>
							<!-- END: thumb -->
                        </td>
                        <td class="text-left">
							<a title="{PRODUCT.name}" href="{PRODUCT.link}">{PRODUCT.name}</a>
							<!-- BEGIN: option -->
							<br /> - <small>{OPTION.name} {OPTION.value}</small>
							<!-- END: option -->	
						</td>
                        <td class="text-left">{PRODUCT.model}</td>
                        <td class="text-left">
                            <div class="input-group btn-block" style="max-width: 200px;">
                                <input type="text" name="quantity[{PRODUCT.key}]" value="{PRODUCT.quantity}" class="form-control input-sm">
                                <span class="input-group-btn">
								<button type="submit" data-toggle="tooltip" title="{LANGE.entry_update}" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
								<button type="button" data-toggle="tooltip" title="{LANGE.entry_remove}" class="btn btn-danger" onclick="cart.remove('{PRODUCT.key}');"><i class="fa fa-times-circle"></i></button>
								</span>
                            </div>
                        </td>
                        <td class="text-right">{PRODUCT.price}</td>
                        <td class="text-right">{PRODUCT.total}</td>
                    </tr>
					<!-- END: product -->
                </tbody>
				
            </table>
        </div>
    </form>
    <h2>{LANGE.text_next}</h2>
    <p>{LANGE.text_next_choice}</p>
    <div class="panel-group" id="accordion">
        
		<!-- yêu cầu coupon -->
		{COUPON}
		<!-- yêu cầu coupon -->
		
		<!-- yêu cầu voucher -->
		{VOUCHER}
		<!-- yêu cầu voucher -->
		
		<!-- yêu cầu shipping -->
		{SHIPPING}
		<!-- yêu cầu shipping -->
		
    </div>
    <br />
    <div class="row">
        <div class="col-sm-8 col-sm-offset-16">
            <table class="table table-bordered">
				<tbody>
					<!-- BEGIN: looptotal -->
					<tr>
					  <td class="text-right"><strong>{TOTAL.title}</strong></td>
					  <td class="text-right">
						{TOTAL.text}
					  </td>
					</tr>
					<!-- END: looptotal -->
				</tbody>
			</table>
        </div>
    </div>
    <div class="buttons">
        <div class="pull-left"><a href="{HOME_LINK}" class="btn btn-default fixcontinue">{LANGE.entry_continue}</a>
        </div>
        <div class="pull-right"><a href="{CHECKOUT_LINK}" class="btn btn-primary fixcheckout">{LANGE.entry_checkout}</a>
        </div>
    </div>
</div> 
 

<script type="text/javascript">
	var urload = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
	$("#total").load(urload + '&t=2');
	$(function() {
		$("a.remove_cart").click(function() {
			var href = $(this).attr("href");
			$.ajax({
				type : "GET",
				url : href,
				data : '',
				success : function(data) {
					if (data != '') {
						$("#" + data).html('');
						$("#cart_" + nv_module_name).load(urload);
						$("#total").load(urload + '&t=2');
					}
				}
			});
			return false;
		});
		
		$('#button-coupons').click(function() {
			var coupon = $('#input-coupon').val();
			if( coupon == '' )
			{
				alert('{LANG.select_coupon}');
			}else
			{
				$.post(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(), 'check_coupon=1&code='+coupon, function(res) {
					var r_split = res.split("_");
					if (r_split[0] == 'OK') {
						window.location.href = strHref;
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
			return false;
		});
	});
 	
</script>
<div class="clear"></div>
<!-- END: data -->

<!-- BEGIN: empty -->
<div id="content" class="col-sm-24"> 
	<h1>Shopping Cart</h1>
	<p>{LANG.cart_empty}</p>
	<div class="buttons">
        <div class="pull-right"><a href="{HOME_LINK}" class="btn btn-primary">Continue</a></div>
	</div>
</div>
<!-- END: empty -->

</div>

<!-- END: main -->