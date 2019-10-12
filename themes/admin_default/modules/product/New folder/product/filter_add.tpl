<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}
        <i class="fa fa-times"></i>
        <br>
    </div>
    <!-- END: error_warning -->
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
					<input type="hidden" name="filter_group_id" value="{DATA.filter_group_id}" />
                    <input name="save" type="hidden" value="1" />
					<div class="form-group required">
                        <label class="col-sm-4 control-label">{LANGE.entry_name}</label>
                        <div class="col-sm-20">
							<!-- BEGIN: looplang -->
                            <div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
                                 <input type="text" name="filter_group_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm">
							</div>         
                            <!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
							<!-- END: looplang -->
                        </div>
                    </div>
					<div class="form-group">
						<label class="col-sm-4 control-label">{LANGE.entry_sort_order}</label>
						<div class="col-sm-20">
							<input type="text" name="sort_order" value="{DATA.sort_order}" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm">
						</div>
					</div>
					<div class="table-responsive">
					<table id="filter" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left required">{LANGE.entry_name}</td>
								<td class="text-right" style="min-width: 100px;">{LANGE.entry_sort_order}</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: filter -->
							<tr id="filter-row{FILTER.key}">
								<td class="text-left">
									<input type="hidden" name="filter[{FILTER.key}][filter_id]" value="{FILTER.filter_id}">
									<!-- BEGIN: languages -->
									<div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}"></span>
										<input type="text" name="filter[{FILTER.key}][filter_description][{LANG_ID}][name]" value="{NAME}" placeholder="{LANGE.entry_name}" class="form-control input-sm"> 
									</div>
									<!-- END: languages -->
								</td>
								<td class="text-right">
									<input type="text" name="filter[{FILTER.key}][sort_order]" value="{SORT_ORDER}" placeholder="{LANGE.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</td>
								<td class="text-left">
									<button type="button" onclick="$('#filter-row1').remove();" data-toggle="tooltip" title="{LANGE.remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
									</button>
								</td>
							</tr>
							<!-- END: filter -->
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td class="text-left"><a onclick="addFilterRow();" data-toggle="tooltip"  class="btn btn-primary btn-sm" title="{LANGE.text_add}"><i class="fa fa-plus-circle"></i></a>
								</td>
							</tr>
						</tfoot>
					</table>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
  

<script type="text/javascript">
var filter_row = {filter_row};

function addFilterRow() {
    html = '<tr id="filter-row' + filter_row + '">';
    html += '  <td class="text-left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	<!-- BEGIN: languages -->
	html += '  <div class="input-group">';
    html += '    <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span><input type="text" name="filter[' + filter_row + '][filter_description][{LANG_ID}][name]" value="" placeholder="{LANGE.entry_name}" class="form-control input-sm" />';
    html += '  </div>';
    <!-- END: languages -->
    html += '  </td>';
    html += '  <td class="text-right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" value="" placeholder="{LANGE.entry_sort_order}" id="input-sort-order" class="form-control input-sm" /></td>';
    html += '  <td class="text-left"><button type="button" onclick="$(\'#filter-row' + filter_row + '\').remove();" data-toggle="tooltip" title="{LANG.remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#filter tbody').append(html);

    filter_row++;
}
</script>

<!-- END: main -->