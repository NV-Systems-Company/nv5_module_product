<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">

<div class="container-fluid">
 <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}
        <i class="fa fa-times"></i>
        <br>
    </div>
<!-- END: error_warning -->
<div class="panel panel-default">
	
	<div class="panel-heading">
		<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.heading_title}</h3> 
		 <div class="pull-right">
			<a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" title="{LANG.add_new}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
			<button type="button" data-toggle="tooltip" data-placement="top" title="{LANG.delete}" class="btn btn-danger btn-sm" id="button-delete">
				<i class="fa fa-trash-o"></i>
			</button>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="panel-body">
		<form action="#" method="post" enctype="multipart/form-data" id="form-coupon">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td style="width: 1px;" class="text-center">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
							</td>
							<td class="text-left"><a href="{URL_TITLE}"> <strong>{LANGE.column_title}</strong></a> </td>
							<td class="text-center"><a href="{URL_STATUS}"> <strong>{LANGE.column_status} </strong></a></td>
							<td class="text-center"><a href="{URL_SORT_ORDER}"> <strong>{LANGE.column_sort_order} </strong></td>
 							<td class="text-right"> <strong>{LANGE.column_action} </strong></td>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: loop --> 
						<tr id="group_{LOOP.information_id}">
							<td class="text-center">
								<input type="checkbox" name="selected[]" value="{LOOP.information_id}"> 
							</td>
							<td class="text-left"><a href="{LOOP.link}"><strong>{LOOP.title}</strong></a></td> 
							<td class="text-center">{LOOP.status}</td> 
							<td class="text-center">{LOOP.sort_order}</td> 
							<td class="text-right">
								<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
								&nbsp;&nbsp;
								<a href="javascript:void(0);" onclick="delete_information('{LOOP.information_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
							</td>
						</tr>
						<!-- END: loop -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- BEGIN: generate_page -->
		<div class="row">
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
 
function delete_information(information_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=information&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'information_id=' + information_id + '&token=' + token,
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
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=information&action=delete&nocache=' + new Date().getTime(),
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
	$('body').on('click', '.close', function( ){ 
		var sclass = $(this).attr('data-dismiss');
		$('.'+ sclass ).remove();
	});
});
</script>
<!-- END: main -->