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
		<div class="panel-body">
			<form action="#" method="post" enctype="multipart/form-data" id="form-customer-group">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="col-sm-0 text-center">
									<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
								</td>
								<td class="col-sm-4 text-center"><a href="{URL_WEIGHT}">{LANGE.column_sort_order}</a></td>
								<td class="col-sm-16 text-left"><a href="{URL_NAME}">{LANGE.column_name}</a> </td>
								<td class="col-sm-4 text-right"> <strong>{LANGE.column_action} </strong></td>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: loop -->
							<tr id="group_{LOOP.customer_group_id}">
								<td class="text-center">
									<input type="checkbox" name="selected[]" value="{LOOP.customer_group_id}">
								</td>
								<td class="text-center">
									 
									<select id="id_weight_{LOOP.customer_group_id}" data-action="weight" data-token="{LOOP.token}" data-id="{LOOP.customer_group_id}" class="form-control input-sm formajax">
										<!-- BEGIN: weight -->
										<option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.name}</option> 
										<!-- END: weight -->
									</select>
									 
								</td>
								<td class="text-left">{LOOP.name}</td>
								<td class="text-right">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm">
										<i class="fa fa-pencil"></i>
									</a>
									<a href="javascript:void(0);" onclick="delete_customer_group('{LOOP.customer_group_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm">
										<i class="fa fa-trash-o"></i>
									</a>
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
$('.formajax').on('change', function() {
	var action = $(this).attr('data-action');
	var token = $(this).attr('data-token');
	var customer_group_id = $(this).attr('data-id');
	var new_vid = $(this).val();
	var id = $(this).attr('id');
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer_group&nocache=' + new Date().getTime(),
		type: 'post',
		dataType: 'json',
		data: 'action=' + action + '&customer_group_id=' + customer_group_id + '&new_vid=' + new_vid + '&token='+token,
		beforeSend: function() {
			$('#'+id ).prop('disabled', true);
			$('.alert').remove();
		},	
		complete: function() {
			$('#'+id ).prop('disabled', false);
		},
		success: function(json) {
			if ( json['error'] ) alert( json['error'] );	
			if ( json['new_vid'] == 0 || json['new_vid'] == 1){
				$('#id_'+action+'_'+customer_group_id).val( json['new_vid'] );
			};	
			if ( json['link'] ) location.href = json['link'];
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function delete_customer_group(customer_group_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer_group&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'customer_group_id=' + customer_group_id + '&token=' + token,
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
			alert("{LANG.please_select_one}");
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=customer_group&action=delete&nocache=' + new Date().getTime(),
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