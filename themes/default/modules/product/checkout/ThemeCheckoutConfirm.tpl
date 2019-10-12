<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="table-responsive">
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
		  <td class="text-left"><a href="{PRODUCT.href}">{PRODUCT.name}</a> 
			<!-- BEGIN: option --><br />
			&nbsp;<small> - {OPTION.name}: {OPTION.value}</small> 
			<!-- END: option -->
			
			<!-- BEGIN: recurring -->
			<br><span class="label label-info">{LANGE.text_recurring_item}</span> <small>{PRODUCT.recurring}</small> 
			<!-- END: recurring -->
		</td>
		<td class="text-left">{PRODUCT.model}</td>
		<td class="text-right">{PRODUCT.quantity}</td>
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
		<!-- BEGIN: total -->
		<tr>
		  <td colspan="4" class="text-right"><strong>{TOTAL.title}:</strong></td>
		  <td class="text-right">{TOTAL.text}</td>
		</tr>
		<!-- END: total -->
	</tfoot>
    
  </table>
</div>
{DATA.payment}
<!-- END: data -->
<!-- BEGIN: redirect -->
<script type="text/javascript">
location = '{REDIRECT}';
</script> 
<!-- END: redirect -->
<!-- END: main -->
