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
				<button id="button-delete" type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" >
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<div style="clear:both"></div>
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
								<td class="text-center"> <a href="{URL_NAME}" {NAME_ORDER} ><strong>{LANGE.column_name}</strong></a> </td>
								<td class="text-center"> <a href="{URL_CODE}" {CODE_ORDER}><strong>{LANGE.column_code}</strong></a> </td>
								<td class="text-center"> <a href="{URL_DISCOUNT}" {DISCOUNT_ORDER}><strong>{LANGE.column_discount}</strong></a> </td>
								<td class="text-center"> <a href="{URL_DATE_START}" {DATE_START_ORDER}><strong>{LANGE.column_date_start}</strong></a> </td>
								<td class="text-center"> <a href="{URL_DATE_END}" {DATE_END_ORDER}><strong>{LANGE.column_date_end}</strong></a> </td>
								<td class="text-center"> <a href="{URL_STATUS}" {STATUS_ORDER}><strong>{LANGE.column_status}</strong></a> </td>
								<td class="text-center"><strong>{LANGE.column_action}</strong></td>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: loop -->
							<tr id="group_{LOOP.coupon_id}">
								<td class="text-center">
									<input type="checkbox" name="selected[]" value="{LOOP.coupon_id}"> 
								</td>
								<td class="text-left">{LOOP.name}</td>
								<td class="text-center">{LOOP.code}</td>
								<td class="text-center">{LOOP.discount}</td>
								<td class="text-center">{LOOP.date_start}</td>
								<td class="text-center">{LOOP.date_end}</td>
								<td class="text-center">{LOOP.status}</td>
								<td class="text-center">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
									<a href="javascript:void(0);" onclick="delete_coupon('{LOOP.coupon_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
								</td>
							</tr>
							<!-- END: loop -->
						</tbody>
					</table>
				</div>
			</form>
			<!-- BEGIN: generate_page -->
			<div style="clear:both"></div>
			<div align="center">
				{GENERATE_PAGE}
			</div>
			<!-- END: generate_page -->
		</div>
	</div>
</div>
  

<script type="text/javascript">
function delete_coupon(coupon_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=coupon&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'coupon_id=' + coupon_id + '&token=' + token,
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
					alert(json['error']);
					//$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<i class="fa fa-times"></i></div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success">' + json['success'] + '<i class="fa fa-times"></i></div>');
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
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=coupon&action=delete&nocache=' + new Date().getTime(),
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
					alert(json['error']);
					//$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<i class="fa fa-times"></i></div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"> ' + json['success'] + ' <i class="fa fa-times"></i></div>');
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