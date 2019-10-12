<!-- BEGIN: main -->
{AddMenu}
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" /> 
<div id="productcontent">
<div class="container-fluid">
<!-- BEGIN: success -->
	<div class="alert alert-success">
		<i class="fa fa-check-circle"></i> {SUCCESS}
		<i class="fa fa-times"></i>
    </div>
<!-- END: success -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.text_list}</h3> 
		 <div class="pull-right">
			<!-- <a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a> -->
			<button type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" id="button-delete">
				<i class="fa fa-trash-o"></i>
			</button> 
		</div>
		<div style="clear:both"></div>
	</div>
	
	<div class="panel-body">
		<div class="well">
			<div class="row">	
				<form action="{NV_BASE_ADMINURL}index.php" method="get">
				<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
				<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label" for="input-product">{LANGE.column_product}</label>
						<input type="text" name="filter_product" value="{DATA.filter_product}" placeholder="{LANGE.column_product}" id="input-product" class="form-control input-sm">
					</div>
					<div class="form-group">
						<label class="control-label" for="input-customer-name">{LANGE.column_customer_name}</label>
						<input type="text" name="filter_customer_name" value="{DATA.filter_customer_name}" placeholder="{LANGE.column_customer_name}" id="input-customer-name" class="form-control input-sm">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label" for="input-status">{LANGE.column_status}</label>
						<select name="filter_status" id="input-status" class="form-control input-sm">
							<option value="*">   --------  </option>
							<!-- BEGIN: filter_status -->
							<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
							<!-- END: filter_status -->
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-date-added">{LANGE.column_date_added}</label>
						 
							<input type="text" name="filter_date_added" value="{DATA.filter_date_added}" placeholder="{LANGE.column_date_added}" id="input-date-added" class="form-control input-sm">
 
					</div>
					<input type="hidden" name ="checkss" value="{TOKEN}" />
 					<button type="submit" id="button-filter" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> {LANG.search}</button>
				</div>
				</form>
			</div>
		</div>
	
		<form action="" method="post" enctype="multipart/form-data" id="form-review">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td style="width: 1px;" class="text-center">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
							</td>
							<td class="col-md-2 text-left" ><a href="{URL_PRODUCT}" class="{SORT_PRODUCT}">{LANGE.column_product}</a> </td>
							<td class="col-md-2 text-left"><a href="{URL_CUSTOMER_NAME}" class="{SORT_CUSTOMER_NAME}" >{LANGE.column_customer_name}</a> </td>
							<td class="col-md-10 text-left"><a href="{URL_QUESTION}" class="{SORT_QUESTION}">{LANGE.column_question}</a> </td>
 							<td class="col-md-2 text-center"><a href="{URL_NUM_ANSWER}" class="{SORT_NUM_ANSWER}">{LANGE.column_num_answer}</a> </td>
  							<td class="col-md-2 text-center"><a href="{URL_STATUS}" class="{SORT_STATUS}">{LANGE.column_status}</a> </td>
  							<td class="col-md-2 text-center"><a href="{URL_DATE_ADDED}" class="{SORT_DATE_ADDED}">{LANGE.column_date_added}</a> </td>
 							<td class="col-md-4 text-center"> <strong>{LANGE.column_action} </strong></td>
						</tr>
					</thead>
					<tbody>
						 <!-- BEGIN: loop --> 
						<tr id="group_{LOOP.faq_id}">
							<td class="text-center" class="col-md-2">
								<input type="checkbox" name="selected[]" value="{LOOP.faq_id}"> 
							</td>
							<td class="text-left">{LOOP.name}</td>
							<td class="text-left">{LOOP.customer_name}</td>
							<td class="text-left">{LOOP.question}</td>
							<td class="text-center">{LOOP.num_answer}</td>
							<td class="text-center">{LOOP.status}</td>
							<td class="text-center">{LOOP.date_added}</td>
 							<td class="text-center">
 								<a href="{LOOP.view_answer}" data-toggle="tooltip" title="{LANGE.column_view_answer}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a> 
								 
								<a href="{LOOP.answer}" data-toggle="tooltip" title="{LANG.answer}" class="btn btn-success btn-sm"><i class="fa fa-share"></i></a> 
								
								<a href="{LOOP.edit_question}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a> 
 
								<a href="javascript:void(0);" onclick="delete_faq('{LOOP.faq_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
 
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
		<div class="row">
			<div class="col-sm-24 text-left">
			
			<div style="clear:both"></div>
			{GENERATE_PAGE}
			
			</div>
 		</div>
		<!-- END: generate_page -->
	</div>
</div>
</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/content.js"></script>
  
 
<script type="text/javascript">
$("#input-date-added").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImageOnly : false
}); 
function delete_faq(faq_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=review&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'faq_id=' + faq_id + '&token=' + token,
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
			alert("{LANG.please_select_one}");
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=review&action=delete&nocache=' + new Date().getTime(),
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