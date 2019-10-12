<!-- BEGIN: main -->
<div class="block clearfix">
	<table class="rows2" style="margin-bottom:2px">
		<tr>
			<td>
			<table>
				<tr>
					<td width="130px"> {LANG.order_name}: </td>
					<td><strong>{DATA.order_name}</strong></td>
				</tr>
				<tr>
					<td> {LANG.order_email}: </td>
					<td> {DATA.order_email} </td>
				</tr>
				<tr>
					<td> {LANG.order_phone}: </td>
					<td> {DATA.order_phone} </td>
				</tr>
				<tr>
					<td valign="top"> {LANG.order_address}: </td>
					<td valign="top"> {DATA.order_address} </td>
				</tr>
				<tr>
					<td> {LANG.order_date}: </td>
					<td> {dateup} {LANG.order_moment} {moment} </td>
				</tr>
			</table></td>
			<td width="100px" valign="top" align="center">
			<div>
				{LANG.order_code}
				<br>
				<span class="text_date">{DATA.order_code}</span>
				<br>
				<span class="payment">{DATA.transaction_name}</span>
				<a href="{url_print}" title="" id="click_print" class="btn_view" style="margin-top:5px"><span>{LANG.order_print}</span></a>
			</div></td>
		</tr>
	</table>
	<table class="rows">
		<tr class="bgtop">
			<td class="image" width="80" align="center">{LANG.cart_images}</td>
			<td class="prd" >{LANG.cart_products}</td>
			<td class="amount" align="center" width="150">{LANG.cart_numbers}</td>
			<td style="width:100px" align="right">{LANG.cart_price}</td>
			<td style="width:100px" align="right">{LANG.price_total}</td>
 
		</tr>
		<!-- BEGIN: product -->
		<tr id="{id}" {bg}>
			<td align="center">
				<!-- BEGIN: thumb -->
				<a href="{PRODUCT.link}"><img src="{PRODUCT.thumb}" alt="{PRODUCT.name}" title="{PRODUCT.name}" class="img-thumbnail" /></a>
				<!-- END: thumb -->
			</td>
			<td class="prd">
				<a title="{PRODUCT.name}" href="{PRODUCT.link}">{PRODUCT.name}</a>
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
			<td class="amount" align="center">
				
				<div class="input-group btn-block money" style="max-width: 200px;">
					{PRODUCT.quantity}
				</div> 
			</td>
			<td class="money" align="right">
				{PRODUCT.price}
			</td>
			<td class="money" align="right">
				{PRODUCT.total}
			</td>
 
		</tr>
		<!-- END: product -->
		</tbody>
	</table>
	<div class="cart-total">
		<table class="table table-bordered">
			<tbody>
				<!-- BEGIN: looptotal -->
				<tr>
				  <td class="text-right"><strong>{TOTAL.title}:</strong></td>
				  <td class="text-right">
					{TOTAL.text}
				  </td>
				</tr>
				<!-- END: looptotal -->
				 
				
			</tbody>
		</table>
		
	</div>
	<div style="clear:both"></div>
	<!-- BEGIN: actpay -->
	<div style="margin-top:4px">
		<!-- BEGIN: payment -->
		<div style="padding:5px;  text-align:center; margin-bottom:2px">
			<!-- BEGIN: paymentloop -->
			<span style="padding:5px; border:1px solid #4a4843; text-align:center; margin-right:2px; display:inline-block"><a title="{DATA_PAYMENT.name}" href="{DATA_PAYMENT.url}"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}" /></a>
				<br/>
				{DATA_PAYMENT.name} </span>
			<!-- END: paymentloop -->
		</div>
		<!-- END: payment -->
		<div style="padding:5px; border:1px solid #4a4843">
			{intro_pay}
		</div>
	</div>
	<!-- END: actpay -->
</div>
<form action="" method="post">
	<input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
</form>
<script type="text/javascript">
	$(function() {
		$('#click_print').click(function(event) {
			var href = $(this).attr("href");
			event.preventDefault();
			nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
	});
</script>
<!-- END: main -->