<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <div class="container-fluid">
        <div class="panel panel-default">
			<div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGE.text_list}</h3>
                <div class="pull-right">
					<!-- <a href="{ADD_NEW}" data-toggle="tooltip" data-placement="top" class="btn btn-success btn-sm" title="{LANG.add_new}"><i class="fa fa-plus"></i></a> -->
					<button type="button" data-toggle="tooltip" data-placement="top"  class="btn btn-danger btn-sm" id="button-delete" title="{LANG.delete}">
						<i class="fa fa-trash-o"></i>
					</button>
				</div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
				<form action="" method="post" enctype="multipart/form-data" id="form-store">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center">
										<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
									</td>
									<td class="text-left"><strong>{LANGE.column_name}</strong></td>
									<td class="text-left"><strong>{LANGE.column_url}</strong></td>
									<td class="text-right"><strong>{LANGE.column_action}</strong></td>
								</tr>
							</thead>
							<tbody>
								<!-- BEGIN: loop -->
								<tr>
									<td class="text-center">
										<input type="checkbox" name="selected[]" value="0">
									</td>
									<td class="text-left">{LOOP.name}
									</td>
									<td class="text-left">{LOOP.url}</td>
									<td class="text-right"><a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
								<!-- END: loop -->
								<!-- BEGIN: no_results -->
								<tr>
								  <td class="text-center" colspan="4">{LANG.text_no_results}</td>
								</tr>
								<!-- END: no_results -->
							</tbody>
						</table>
					</div>
				</form>
			</div>
        </div>
    </div>
</div>
  

  
 
<!-- BEGIN: main -->