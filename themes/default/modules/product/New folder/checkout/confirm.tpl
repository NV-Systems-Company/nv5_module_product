<!-- BEGIN: main -->
<!-- BEGIN: warning -->
<div class="alert alert-danger">
	<i class="fa fa-exclamation-circle"></i> {warning}
	<button type="button" class="close" data-dismiss="alert">×</button>
</div>
<!-- END: warning -->

<!-- BEGIN: data -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <td class="text-left">{LANGE.column_name}</td>
            <td class="text-left">{LANGE.column_model}</td>
            <td class="text-right">{LANGE.column_quantity}</td>
            <td class="text-right">{LANGE.column_price}</td>
            <td class="text-right">{LANGE.column_total}</td>
        </tr>
    </thead>
    <tbody>

        <!-- BEGIN: product -->
		<tr>
			<td class="text-left">
				<a title="{PRODUCT.name}" href="{PRODUCT.link}">{PRODUCT.name}</a>
				<!-- BEGIN: option -->
				<br /> - <small>{OPTION.name} {OPTION.value}</small>
				<!-- END: option -->
			</td>
			<td class="text-left">{PRODUCT.model}</td>
			<td class="text-left">{PRODUCT.quantity}</td>
			<td class="text-right">{PRODUCT.price}</td>
			<td class="text-right">{PRODUCT.total}</td>
		</tr>	
		<!-- END: product -->
		<!-- BEGIN: voucher -->
		<tr>
		  <td class="text-left">{VOUCHER.description}</td>
		  <td class="text-left"></td>
		  <td class="text-right">1</td>
		  <td class="text-right">{VOUCHER.amount}</td>
		  <td class="text-right">{VOUCHER.amount}</td>
		</tr>
		<!-- END: voucher -->
    </tbody>
    <tfoot>
        <!-- BEGIN: looptotal -->
		<tr>
			<td colspan="4" class="text-right"><strong>{TOTAL.title}:</strong></td>
			<td class="text-right">
			{TOTAL.text}
			</td>
		</tr>
		<!-- END: looptotal -->
    </tfoot>
</table>
<div class="buttons">
    <div class="pull-right">
        <a onclick="addloading()" href="{DATA.payment}" class="btn btn-primary">{LANG.button_continue}</a>
    </div>
</div>

<!-- END: data -->

<!-- BEGIN: redirect -->
<script type="text/javascript"> 
//location = '{DATA.redirect}';
</script>
<!-- END: redirect -->
<!-- END: main -->