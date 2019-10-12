<!-- BEGIN: main -->
<div id="productcontent">
<div class="container-fluid">
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANG.tags_list}</h3> 
		 <div class="pull-right">
			<a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
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
							<td style="width: 1px;" class="text-center">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
							</td>
							<td class="text-center" style="width:80px" >{LANG.tags_number}</td>
							<td class="text-left"><a href="{URL_ALIAS}">{LANG.tags_alias}</a> </td>
							<td class="text-left"><a href="{URL_KEYWORDS}">{LANG.tags_keywords}</a>
							<!-- BEGIN: incomplete -->
							<em class="text-danger fa fa-lg fa-warning tags-tip" data-toggle="tooltip" data-placement="top" title="{LANG.tags_no_description}">&nbsp;</em>
							<!-- END: incomplete -->
							</td>
							<td class="text-center"><a href="{URL_KEYWORDS}">{LANG.tags_numlinks}</a> </td>
							<td class="text-right"> <strong>{LANG.action} </strong></td>
						</tr>
					</thead>
					<tbody>
						 <!-- BEGIN: loop --> 
						<tr id="group_{LOOP.tags_id}">
							<td class="text-center">
								<input type="checkbox" name="selected[]" value="{LOOP.tags_id}"> 
							</td>
							<td class="text-center">{LOOP.number}</td>
							<td class="text-left">{LOOP.alias}</td>
							<td class="text-left">{LOOP.keywords}</td>
							<td class="text-center">{LOOP.numpro}</td>
							<td class="text-right">
								<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
								&nbsp;&nbsp;
								<a href="javascript:void(0);" onclick="delete_tags('{LOOP.tags_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
							</td>
						</tr>
						 <!-- END: loop -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- BEGIN: generate_page -->
		<div class="row">
			<div class="col-sm-6 text-left">
			
			<div style="clear:both"></div>
			{GENERATE_PAGE}
			
			</div>
			<div class="col-sm-6 text-right">{LANG.showing} {from_page} {LANG.to} {to_page} {LANG.of} {num_items} ({page} {LANG.pages})</div>
		</div>
		<!-- END: generate_page -->
	</div>
</div>
</div>
</div>
<script type="text/javascript">
function nv_chang_weight(tags_id) {
	var nv_timer = nv_settimeout_disable('change_weight_' + tags_id, 5000);
	var new_weight = $('#change_weight_' + tags_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&action=weight&nocache=' + new Date().getTime(), 'tags_id=' + tags_id + '&new_weight=' + new_weight, function(res) {
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
function delete_tags(tags_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'tags_id=' + tags_id + '&token=' + token,
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
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&action=delete&nocache=' + new Date().getTime(),
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