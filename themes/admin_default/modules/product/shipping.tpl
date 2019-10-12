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
		 
			</div>
			<div style="clear:both"></div>
		</div> 
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left"><strong>{LANGE.column_name}</strong></td>
							<td class="text-center"><strong>{LANGE.column_status}</strong></td>
							<td class="text-center"><strong>{LANGE.column_sort_order}</strong></td>
							<td class="text-right"><strong>{LANGE.column_action}</strong></td>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: loop -->
						<tr>
							<td class="text-left">{LOOP.name}</td>
							<td class="text-center">{LOOP.status}</td>
							<td class="text-center">{LOOP.sort_order}</td>
							<td class="text-right">								
								<!-- BEGIN: install -->	
								<a href="{LOOP.install}" data-toggle="tooltip" class="btn btn-success btn-sm" title="{LANG.install}"><i class="fa fa-plus-circle"></i></a>
								<!-- END: install -->
								
								<!-- BEGIN: uninstall -->	
								<a onclick="confirm('{LANG.confirm_delete}') ? location.href='{LOOP.uninstall}' : false;" data-toggle="tooltip" class="btn btn-danger btn-sm" title="{LANG.uninstall}"><i class="fa fa-minus-circle"></i></a>
								<!-- END: uninstall -->
									
								<!-- BEGIN: install2 -->	
								<button type="button" class="btn btn-primary btn-sm" disabled="disabled"><i class="fa fa-pencil"></i></button>
								<!-- END: install2 -->
									
								<!-- BEGIN: uninstall2 -->	
								<a href="{LOOP.edit}" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.edit}"><i class="fa fa-pencil"></i></a>
								<!-- END: uninstall2 -->	
							</td>
						</tr>
						<!-- END: loop -->
					</tbody>
				</table>
			</div>
		</div>	
	</div>  
</div> 
  
 
<!-- END: main -->