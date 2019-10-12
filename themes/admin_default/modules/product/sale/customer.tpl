<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: success -->
	<div class="alert alert-success">
		<i class="fa fa-check-circle"></i> {SUCCESS}<i class="fa fa-times"></i>
    </div>
	<!-- END: success -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.text_list}</h3> 
			 <div class="pull-right">
				<a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" id="button-delete">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<div style="clear:both"></div>
		</div>
		
		<div class="well">
			<div class="row">
				<form  action="{NV_BASE_ADMINURL}index.php" method="get">
				<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
				<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="input-name">{LANGE.column_name}</label>
						<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.column_name}" id="input-name" class="form-control input-sm" autocomplete="off">
						<ul class="dropdown-menu" style="display: none;"></ul>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-email">{LANGE.column_email}</label>
						<input type="text" name="email" value="{DATA.email}" placeholder="{LANGE.column_email}" id="input-email" class="form-control input-sm" autocomplete="off">
						<ul class="dropdown-menu"></ul>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="input-customer-group">{LANGE.column_customer_group}</label>
						<select name="customer_group_id" id="input-customer-group" class="form-control input-sm">
							<option value="">  ------  </option>
							<!-- BEGIN: customer_group -->
							<option value="{customer_group_id}" {group_selected}>{customer_group}</option>
							<!-- END: customer_group -->
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-status">{LANGE.column_status}</label>
						<select name="status" id="input-status" class="form-control input-sm">
							<option value="">  ------  </option>
							<!-- BEGIN: status -->
							<option value="{valuekey}" {key_selected}>{status_title}</option>
							<!-- END: status -->
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="input-approved">{LANGE.column_approval}</label>
						<select name="approved" id="input-approved" class="form-control input-sm">
							<option value="">  -----  </option>
							<!-- BEGIN: approved -->
							<option value="{key1}" {approval_selected}>{approved}</option>
							<!-- END: approved -->
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-ip">{LANGE.column_ip}</label>
						<input type="text" name="ip" value="{DATA.ip}" placeholder="{LANGE.column_ip}" id="input-ip" class="form-control input-sm"> </div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="input-date-added" style="display: block;">{LANGE.column_date_added}</label>
						<div class="input-group date" style="display: inline-flex;">
							<input type="text" name="date_added" value="{DATA.date_added}" placeholder="{LANGE.column_date_added}" data-format="YYYY-MM-DD" id="input-date-added" class="form-control input-sm" style="display: inline-block"> 
						</div>
					</div>
					<div class="form-group">
						<button type="submit" id="button-filter" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> {LANG.search}</button>
					</div>
				</div>
				</form>
			</div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-coupon">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td style="width: 1px;" class="text-center">
									<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
								</td>
	 
								<td class="text-left"><a href="{URL_NAME}">{LANGE.column_name}</a> </td>
								<td class="text-left"><a href="{URL_EMAIL}">{LANGE.column_email}</a> </td>
								<td class="text-left"><a href="{URL_CUSTOMER_GROUP}">{LANGE.column_customer_group}</a> </td>
								<td class="text-center"><a href="{URL_ACTIVE}">{LANGE.column_status}</a> </td>
								<td class="text-left"><a href="{URL_IP}">{LANGE.column_ip}</a> </td>
								<td class="text-center"><a href="{URL_DATE_ADDED}">{LANGE.column_date_added}</a> </td>
								<td class="text-right"> <strong>{LANGE.column_action} </strong></td>
							</tr>
						</thead>
						<tbody>
							 <!-- BEGIN: loop --> 
							<tr id="group_{LOOP.userid}">
								<td class="text-center">
									<input type="checkbox" name="selected[]" value="{LOOP.userid}"> 
								</td>
								<td class="text-left">{LOOP.name}</td>
								<td class="text-left">{LOOP.email}</td>
								<td class="text-left">{LOOP.customer_group}</td>
								<td class="text-center">{LOOP.status}</td>
								<td class="text-left">{LOOP.last_ip}</td>
								<td class="text-center">{LOOP.regdate}</td>
								<td class="text-right">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
									<a href="javascript:void(0);" onclick="delete_customer('{LOOP.userid}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
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
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$("#input-date-added").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "{NV_ASSETS_DIR}/images/calendar.gif",
		buttonImageOnly : true
}); 
function delete_customer(userid, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'userid=' + userid + '&token=' + token,
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
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<i class="fa fa-times"></i></div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');
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
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<i class="fa fa-times"></i></div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');
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