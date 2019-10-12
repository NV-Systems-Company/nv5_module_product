<!-- BEGIN: main -->
<table class="table table-bordered">
	<thead>
		<tr>
			<td class="text-left">{LANGE.column_date_added}</td>
			<td class="text-left">{LANGE.column_comment}</td>
			<td class="text-left">{LANGE.column_status}</td>
			<td class="text-left">{LANGE.column_notify}</td>
		</tr>
	</thead>
	<!-- BEGIN: data -->
	<tbody>
	<!-- BEGIN: history -->
	<tr>
		<td class="text-left">{HISTORY.date_added}</td>
		<td class="text-left">{HISTORY.comment}</td>
		<td class="text-left">{HISTORY.status}</td>
		<td class="text-left">{HISTORY.notify}</td>
    </tr>
	<!-- END: history -->
	</tbody>
	<!-- END: data -->
	<!-- BEGIN: no_results -->
	<tr>
		<td class="text-center" colspan="4">{LANGE.text_no_results}</td>
	</tr>
	<!-- END: no_results -->
</table>
<!-- BEGIN: generate_page -->
<div class="row">	
	<div class="col-sm-24 text-center">
	{GENERATE_PAGE}
	</div>
</div>
<!-- END: generate_page -->

<!-- END: main -->