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
				<button type="button" id="button-send" data-toggle="tooltip" title="{LANG.send}" class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i></button>
				<a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" id="button-delete">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
			<div style="clear:both"></div>
			</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-voucher">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td style="width: 1px;" class="text-center">
									<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
								</td>
								<td class="text-center"> <a href="{URL_CODE}">{LANGE.column_code}</a> </td>
								<td class="text-center"> <a href="{URL_FROMNAME}">{LANGE.column_name}</a> </td>
								<td class="text-center"> <a href="{URL_TONAME}">{LANGE.column_to}</a> </td>
								<td class="text-center"> <a href="{URL_AMOUNT}">{LANGE.column_amount}</a> </td>
								<td class="text-center"> <a href="{URL_THEME}">{LANGE.column_theme}</a> </td>
								<td class="text-center"> <a href="{STATUS}">{LANGE.column_status}</a> </td>
								<td class="text-center"> <a href="{DATE_ADD}">{LANGE.column_date_added}</a> </td>
								<td class="text-center">{LANGE.column_action}</td>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: loop -->
							<tr id="group_{LOOP.voucher_id}">
								<td class="text-center">
									<input type="checkbox" name="selected[]" value="{LOOP.voucher_id}"> 
								</td>
								<td class="text-center">{LOOP.code}</td>
								<td class="text-center">{LOOP.from_name}</td>
								<td class="text-center">{LOOP.to_name}</td>
								<td class="text-center">{LOOP.amount}</td>
								<td class="text-center">{LOOP.theme}</td>
								<td class="text-center"><input type="checkbox" class="formajax" value="1" data-token="{LOOP.token}" {LOOP.status_checked}></td>
								<td class="text-center">{LOOP.date_added}</td>
								<td class="text-center">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
									<a href="javascript:void(0);" onclick="delete_voucher('{LOOP.voucher_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>									
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
function delete_voucher(voucher_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voucher&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'voucher_id=' + voucher_id + '&token=' + token,
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
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voucher&action=delete&nocache=' + new Date().getTime(),
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
 
$('#button-send').on('click', function() {

	var list = [];
	$('input[name="selected[]"]:checked').each(function() {
		list.push($(this).val());
	});
	if (list.length < 1) {
		alert('{LANG.please_select_one}');
		return false;
	}
 
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voucher&action=sendmail&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		data: 'list=' + list + '&token={TOKEN}',
		beforeSend: function() {
			$('#button-send i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$('#button-send').prop('disabled', true);
		},	
		complete: function() {
			$('#button-send i').replaceWith('<i class="fa fa-envelope"></i>');
			$('#button-send').prop('disabled', false);
		},
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				alert(json['error']);
				//$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<i class="fa fa-times"></i></div>');
			}
			
			if (json['success']) {
				$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<i class="fa fa-times"></i></div>');
			}		
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
</script>
<!-- END: main -->