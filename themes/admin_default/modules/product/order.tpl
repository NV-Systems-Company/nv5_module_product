<!-- BEGIN: main -->
{AddMenu} 
<div id="productcontent">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANG.order_list}</h3> 
			<div class="pull-right">
				<button type="submit" id="button-shipping" form="form-order" formaction="sale/order/shipping" data-toggle="tooltip" class="btn btn-info btn-sm" disabled="" title="Print Shipping List"><i class="fa fa-truck"></i></button>
				<button type="submit" id="button-invoice" form="form-order" formaction="sale/order/invoice" data-toggle="tooltip" title="" class="btn btn-info btn-sm" disabled="" data-original-title="Print Invoice"><i class="fa fa-print"></i></button>
				<a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
				<!-- <button type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" id="button-delete">
					<i class="fa fa-trash-o"></i>
				</button> -->
			</div>
			<div style="clear:both"></div>
		</div>		
		
		<div class="panel-body">
			<div class="row">
				<div id="filter-order" class="col-md-6 col-md-push-18 col-sm-24 hidden-sm hidden-xs">
					<div class="panel panel-default">
						<div class="panel-heading">
						  <h3 class="panel-title"><i class="fa fa-filter"></i> Bộ lọc</h3>
						</div>
						<div class="panel-body">
							<form  action="{NV_BASE_ADMINURL}index.php" method="get">
								<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
								<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
							 
								<div class="form-group">
									<label class="control-label" for="input-order-id">{LANG.order_id}</label>
									<input type="text" name="filter_order_id" value="{DATA.filter_order_id}" placeholder="{LANG.order_id}" id="input-order-id" class="form-control input-sm">
								</div>
								<div class="form-group">
									<label class="control-label" for="input-customer">{LANG.customer}</label>
									<input type="text" name="filter_customer" value="{DATA.filter_customer}" placeholder="{LANG.customer}" id="input-customer" class="form-control input-sm" autocomplete="off">
									<ul class="dropdown-menu" style="top: 131px; left: 15px; display: none;">
										 
									</ul>
								</div>
					 
							 
								<div class="form-group">
									<label class="control-label" for="input-order-status">{LANG.order_status}</label>
									<select name="filter_order_status" id="input-order-status" class="form-control input-sm">
										<option value="*"></option>
			 
										<!-- BEGIN: order_status -->
										<option value="{valuekey}" {key_selected}>{status_title}</option>
										<!-- END: order_status -->
									</select>
								</div>
								<div class="form-group">
									<label class="control-label" for="input-total">{LANG.order_total}</label>
									<input type="text" name="filter_total" value="{DATA.filter_total}" placeholder="{LANG.order_total}" id="input-total" class="form-control input-sm">
								</div>
							 
								<div class="form-group">
									<label class="control-label" for="input-date-added" >{LANG.order_date_added}</label>
									
										<input type="text" name="filter_date_added" value="{DATA.filter_date_added}" placeholder="{LANG.order_date_added}" id="input-date-added" class="form-control input-sm"  > 
									
								</div>
								<div class="form-group">
									<label class="control-label" for="input-date-modified" >{LANG.order_date_modified}</label>
									
										<input type="text" name="filter_date_modified" value="{DATA.filter_date_modified}" placeholder="{LANG.order_date_modified}" id="input-date-modified" class="form-control input-sm"  > 
									
								</div>
								<div class="form-group text-center">
									<button type="submit" id="button-filter" class="btn btn-primary btn-sm text-center"><i class="fa fa-search"></i> {LANG.filter}</button>
					 
								</div>
			 
								
							</form>
							
						</div>
					</div>
				</div>
				<div class="col-md-18 col-md-pull-6 col-sm-24">
					<form action="" method="post" enctype="multipart/form-data" id="form-order-status">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<td style="width: 1px;" class="text-center">
											<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
										</td>
										<td class="text-center"><a href="{URL_ID}">{LANG.order_invoice_prefix}</a> </td>
										<td class="text-left"><a href="{URL_NAME}">{LANG.customer}</a> </td>
										<td class="text-center"><a href="{URL_STATUS}">{LANG.order_status}</a> </td>
										<td class="text-right"><a href="{URL_TOTAL}">{LANG.order_total}</a> </td>
										<td class="text-center"><a href="{URL_DATE_ADDED}">{LANG.order_date_added}</a> </td>
										<td class="text-center"><a href="{URL_DATE_MODIFIED}">{LANG.order_date_modified}</a> </td>
										<td class="text-right"> <strong>{LANG.action} </strong></td>
									</tr>
								</thead>
								<tbody>
									 <!-- BEGIN: loop --> 
									<tr id="group_{LOOP.order_id}">
										<td class="text-center">
											<input type="checkbox" name="selected[]" value="{LOOP.order_id}"> 
										</td>
										<td class="text-center">{LOOP.invoice_prefix}</td>
										<td class="text-left">{LOOP.customer}</td>
										<td class="text-center">{LOOP.status}</td>
										<td class="text-right">{LOOP.total}</td>
										<td class="text-center">{LOOP.date_added}</td>
										<td class="text-center">{LOOP.date_modified}</td>
										<td class="text-right">
											<a href="{LOOP.order_info}" data-toggle="tooltip" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
											<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" onclick="delete_order('{LOOP.order_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
										
										
										</td>
									</tr>
									 <!-- END: loop -->
									 <!-- BEGIN: No_results -->
									 <tr>
									  <td class="text-center" colspan="8">{LANG.no_results} !</td>
									</tr>
									 <!-- END: No_results -->
									 
								</tbody>
							</table>
						</div>
					</form>
					<!-- BEGIN: generate_page -->
					<div class="text-center clearfix">
						{GENERATE_PAGE}
					</div>
					<!-- END: generate_page -->
				</div>
				
 
			</div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
$("#input-date-added, #input-date-modified").datepicker({
	dateFormat : "dd/mm/yy",
	changeMonth : true,
	changeYear : true,
	showOtherMonths : true,
	buttonImageOnly : false
}); 
function delete_order(order_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'order_id=' + order_id + '&token=' + token,
			beforeSend: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					 $.each(json['id'], function(i, id) {
						$('#group_' + id ).remove();
					});
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('#button-delete').on('click', function() {
	if(confirm('{LANG.confirm}')) 
	{
		var listid = [];
		$('input[name="selected[]"]:checked').each(function() {
			listid.push($(this).val());
		});
		if (listid.length < 1) {
			alert('{LANG.please_select_one}');
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'listid=' + listid + '&token={TOKEN}',
			beforeSend: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				$('.alert').remove();
				
				
				
				if (json['error']) {
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					 $.each(json['id'], function(i, id) {
						$('#group_' + id ).remove();
					});
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}	
});
</script>
<!-- END: main -->