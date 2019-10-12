<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
<div class="container-fluid">
	<!-- BEGIN: groupnav -->
	<div class="divbor1" style="margin-bottom: 10px">
		<!-- BEGIN: loop -->
		{GROUP_NAV}
		<!-- END: loop -->
	</div>
	<!-- END: groupnav -->
<div class="panel panel-default">
	
	<div class="panel-heading">
		<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANG.group_list}</h3> 
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
							<td class="text-center" style="width:80px" ><a href="{URL_WEIGHT}">{LANG.weight}</a></td>
							<td class="text-left"><a href="{URL_NAME}">{LANG.group_name}</a> </td>
							<td class="text-right"> <strong>{LANG.group_inhome} </strong></td>
							<td class="text-right"> <strong>{LANG.viewcat_page} </strong></td>
							<td class="text-right"> <strong>{LANG.group_in_order} </strong></td>
 							<td class="text-right"> <strong>{LANG.action} </strong></td>
						</tr>
					</thead>
					<tbody>
						 <!-- BEGIN: loop --> 
						<tr id="group_{LOOP.group_id}">
							<td class="text-center">
								<input type="checkbox" name="selected[]" value="{LOOP.group_id}"> 
							</td>
							<td class="text-center">
								<select id="id_weight_{LOOP.group_id}" onchange="nv_change_group('{LOOP.group_id}','weight');" class="form-control input-sm">
								<!-- BEGIN: weight -->
								<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
								<!-- END: weight -->
								</select>
							</td>
							<td class="text-left"><a href="{LOOP.link}"> <strong>{LOOP.name}</strong> </a> {LOOP.numsubcat}</td>
							<td class="text-center">
								<select class="form-control input-sm" id="id_inhome_{LOOP.group_id}" onchange="nv_change_group('{LOOP.group_id}','inhome');">
									<!-- BEGIN: inhome -->
									<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
									<!-- END: inhome -->
								</select>
							</td>
							
							<td align="left">
								<select class="form-control input-sm" id="id_viewgroup_{LOOP.group_id}" onchange="nv_change_group('{LOOP.group_id}','viewgroup');">
									<!-- BEGIN: viewgroup -->
									<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
									<!-- END: viewgroup -->
								</select>
							</td>
 
							<td class="text-center">
									<select class="form-control input-sm" id="id_in_order_{LOOP.group_id}" onchange="nv_change_group('{LOOP.group_id}','in_order');">
										<!-- BEGIN: in_order -->
										<option value="{INORDER.key}"{INORDER.selected}>{INORDER.title}</option>
										<!-- END: in_order -->
									</select>
							</td>
							<td class="text-right">
								<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
								&nbsp;&nbsp;
								<a href="javascript:void(0);" onclick="delete_group('{LOOP.group_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
							
							
							</td>
						</tr>
						 <!-- END: loop -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- BEGIN: generate_page -->
		<div class="row">
			<div style="clear:both"></div>
			<div class="col-sm-12 text-left">
			{GENERATE_PAGE}
			</div>
 		</div>
		<!-- END: generate_page -->
	</div>
	<div id="group-delete-area">&nbsp;</div>
</div>
</div>
</div>
  

<script type="text/javascript">
function nv_change_group(group_id, mod) {
	var nv_timer = nv_settimeout_disable('id_'+mod+'_' + group_id, 5000);
	var new_vid = $('#id_'+mod+'_' + group_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=group&action='+mod+'&nocache=' + new Date().getTime(), 'group_id=' + group_id + '&new_vid=' + new_vid, function(res) {
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
 
$(document).ready(function() {

	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});
});
</script>
<!-- END: main -->