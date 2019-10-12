<!-- BEGIN: main -->
 {AddMenu}
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING} <i class="fa fa-times"></i>  
    </div>
    <!-- END: error_warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
				<input type="hidden" name="option_id" value="{DATA.option_id}" />
				<input name="save" type="hidden" value="1" />
				<div class="tab-content">
					<div class="form-group required">
						<label class="col-sm-4 control-label">{LANGE.entry_name}</label>
						<div class="col-sm-20" style="padding-left: 0;">
							<!-- BEGIN: looplang -->
							<div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
								<input type="text" name="option_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm">
							</div>
							<!-- BEGIN: option_error_name -->
							<div class="text-danger">{option_error_name}</div>
							<!-- END: option_error_name -->
							<!-- END: looplang -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-type">{LANGE.entry_type}</label>
						<div class="col-sm-20">
							<select name="type" id="input-type" class="form-control input-sm">
							<!-- BEGIN: optgroup -->
								<optgroup label="{label}">
									<!-- BEGIN: option --> 
									<option value="{key}" {selected}>{type}</option>
									<!-- END: option --> 
								</optgroup>
								<!-- END: optgroup --> 
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-sort-order">{LANGE.entry_sort_order}</label>
						<div class="col-sm-20">
							<input type="text" name="sort_order" value="{DATA.sort_order}" placeholder="{LANGE.entry_sort_order}" id="input-sort-order" class="form-control input-sm" />
						</div>
					</div>
					<div class="table-responsive">
						<table id="option-value" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<td class="text-left required"><strong>{LANGE.entry_optione_value_name}</strong></td>
									<td class="text-left"><strong>{LANGE.entry_image}</strong></td>
									<td class="text-right"><strong>{LANGE.entry_sort_order}</strong></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								<!-- BEGIN: option_value -->
								<tr id="option-value-row{valuekey}" class="option-value" rel="{valuekey}">
									<td class="text-left">
										<input type="hidden" name="option_value[{valuekey}][option_value_id]" value="{LOOP.option_value_id}">
										<!-- BEGIN: looplang5 -->
										<div class="input-group"> <span class="input-group-addon"><img src="{LANG_TITLE.image}" title="{LANG_TITLE.name}"></span>
											<input type="text" name="option_value[{valuekey}][option_value_description][{LANG_KEY}][name]" value="{NAME}" placeholder="Option Value Name" class="form-control input-sm">
										</div>
										<!-- BEGIN: option_value_error_name -->
										<div class="text-danger">{option_value_error_name}</div>
										<!-- END: option_value_error_name -->
										<!-- END: looplang5 -->
									</td>
									<td class="text-left">
										<a href="javascript:void(0);" id="thumb-image{valuekey}" rel="{valuekey}" data-toggle="image" class="img-thumbnail"><img src="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" style="max-width: 100px;">
										</a>
										<input type="hidden" name="option_value[{valuekey}][image]" value="{LOOP.image}" id="input-image{valuekey}">
									</td>
									<td class="text-right" style="min-width: 70px;">
										<input type="text" name="option_value[{valuekey}][sort_order]" value="{LOOP.sort_order}" placeholder="{LANG.entry_sort_order}" class="form-control input-sm">
									</td>
									<td class="text-left">
										<button type="button" onclick="$('#option-value-row{valuekey}').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
										</button>
									</td>
								</tr>
								<!-- END: option_value -->
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"></td>
									<td class="text-left">
										<button type="button" onclick="addOptionValue();" data-toggle="tooltip" title="{LANGE.entry_add_optione_value}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div align="center">
						<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
						<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a>
					</div>
				</div>
			</form>
		</div> 
	</div>
</div>  
<script type="text/javascript">
$('select[name=\'type\']').on('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
});

$('select[name=\'type\']').trigger('change');

var option_value_row = {option_value_row};

function addOptionValue() {
	html = '<tr id="option-value-row' + option_value_row + '" class="option-value" rel="' + option_value_row + '">';
	html += '  <td class="text-left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
 
	<!-- BEGIN: looplang1 -->
	html += '    <div class="input-group">';
	html += '      <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span><input type="text" name="option_value[' + option_value_row + '][option_value_description][{LANG_ID}][name]" value="" placeholder="{LANGE.entry_optione_value_name}" class="form-control input-sm" />';
	html += '    </div>';
	<!-- END: looplang1 -->
			
	html += '  </td>';
	html += '  <td class="text-left"><a href="javascript:void(0);" id="thumb-image' + option_value_row + '" rel="' + option_value_row + '" data-toggle="image" class="img-thumbnail"><img src="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" style="max-width: 100px;"/></a>';
	html += '	<input type="hidden" name="option_value[' + option_value_row + '][image]" value="" id="input-image' + option_value_row + '" /></td>';
	html += '  <td class="text-right" style="min-width: 70px;"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
			
	$('#option-value tbody').append(html);

	option_value_row++;
}
function select_image( area )
{
	var path = "{UPLOAD_PATH}";
	var currentpath = "{UPLOAD_CURRENT}";
	var type = "image";
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
}
function Refresh()
{
	$('.option-value').each(function(){
		var i = $(this).attr('rel');	
		var img = $('#input-image'+i+'').val();	
		var oldimage = $('#thumb-image'+i+' img').attr('src');
		if( img != '' && oldimage != img)
		{
			$('#thumb-image'+i+' img').attr('src', img);	
			$('.popover').remove();
		}
				
	});	
}

setInterval(Refresh, 1000);
		
$(document).ready(function() {
	$('#language a:first').tab('show');			
});	     
</script>
<!-- END: main -->