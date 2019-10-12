<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
<div class="container-fluid">
 
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
		<form action="" method="post" enctype="multipart/form-data" id="form-units">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td style="width: 1px;" class="text-center">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
							</td>
							<td class="text-center" style="width:80px" ><a href="{URL_WEIGHT}">{LANGE.column_sort_order}</a></td>
 							<td class="text-left"><a href="{URL_NAME}">{LANGE.column_name}</a> </td>
 							<td class="text-left"><a href="{URL_NAME}">{LANGE.column_description}</a> </td>
 							<td class="text-right"> <strong>{LANGE.column_action} </strong></td>
						</tr>
					</thead>
					<tbody>
						 <!-- BEGIN: loop --> 
						<tr id="group_{LOOP.units_id}">
							<td class="text-center">
								<input type="checkbox" name="selected[]" value="{LOOP.units_id}"> 
							</td>
							<td class="text-center">
								<select id="change_weight_{LOOP.units_id}" onchange="nv_change_units('{LOOP.units_id}','weight');" class="form-control input-sm">
								<!-- BEGIN: weight -->
								<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
								<!-- END: weight -->
								</select>
							</td>
 							<td class="text-left"><strong>{LOOP.name}</strong></td>
 							<td class="text-left"><strong>{LOOP.description}</strong></td>
 
							 
							<td class="text-right">
								<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
								&nbsp;&nbsp;
								<a href="javascript:void(0);" onclick="delete_units('{LOOP.units_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
							
							
							</td>
						</tr>
						 <!-- END: loop -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- BEGIN: generate_page -->
		<div>
			<div class="col-sm-12 text-left">
			
			<div style="clear:both"></div>
			{GENERATE_PAGE}
			
			</div>
 		</div>
		<!-- END: generate_page -->
	</div>
</div>
</div>
</div>
<script type="text/javascript">
function nv_change_units(units_id, mod) {
	var nv_timer = nv_settimeout_disable('change_'+mod+'_' + units_id, 5000);
	var new_vid = $('#change_'+mod+'_' + units_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=units&action='+mod+'&nocache=' + new Date().getTime(), 'units_id=' + units_id + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split("_");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			clearTimeout(nv_timer);
		} else {
			window.location.href = window.location.href;
		}
	});
	return;
}
function delete_units(units_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=units&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'units_id=' + units_id + '&token=' + token,
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
			alert({LANG.please_select_one});
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=units&action=delete&nocache=' + new Date().getTime(),
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

$(document).ready(function() {

	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});
});
</script>
<!-- END: main -->