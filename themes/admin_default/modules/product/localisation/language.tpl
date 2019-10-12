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
			<form action="#" method="post" enctype="multipart/form-data" id="form-language">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td style="width: 1px;" class="text-center">
									<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
								</td>
								<td class="text-left"> <a href="{URL_NAME}">{LANGE.column_name}</a> </td>
								<td class="text-right">{LANGE.column_action}</td>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: loop --> 
							<tr id="group_{LOOP.language_id}">
								<td class="text-center">
									<input type="checkbox" name="selected[]" value="{LOOP.language_id}"> 
								</td>
								<td class="text-left">{LOOP.name} <!-- BEGIN: default --><b>({LANG.default})</b>  <!-- END: default --></td>
								<td class="text-right">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
							<!-- END: loop -->
						</tbody>
					</table>
				</div>
			</form>
			<!-- BEGIN: generate_page -->
			<div align="center">
				{GENERATE_PAGE}	
			</div>
			<!-- END: generate_page -->
		</div>
	</div>
</div>
<!-- END: main -->