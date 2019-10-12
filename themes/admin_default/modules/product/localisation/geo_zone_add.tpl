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
				<button type="submit" form="form-coupon" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a> 
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
				<div class="tab-content">
					<div class="tab-pane active" id="tab-general" style="padding-top: 20px;">
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_name}</label>
							<div class="col-sm-20">
								<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.entry_name}" id="input-name" class="form-control input-sm" /> 
								<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_description}</label>
							<div class="col-sm-20">
								<input type="text" name="description" value="{DATA.description}" placeholder="{LANGE.entry_description}" id="input-name" class="form-control input-sm" /> 
							</div>
						</div>
						<table id="zone-to-geo-zone" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<td class="text-left">{LANGE.entry_country}</td>
									<td class="text-left">{LANGE.entry_zone}</td>
									<td></td>
								</tr>
							</thead>
							<tbody>	
							<!-- BEGIN: loop2 -->
								<tr id="zone-to-geo-zone-row{KEY}">
									<td class="text-left">
										<select name="zone_to_geo_zone[{KEY}][country_id]" id="country{KEY}" class="form-control input-sm" onchange="getZone(this, '{KEY}', '{ZONE_ID}');">
											<!-- BEGIN: loop_country -->
											<option value="{COUNTRY_ID}" {COUNTRY_SELECTED}>{COUNTRY_NAME}</option>
											<!-- END: loop_country -->
										</select>
									</td>
									<td class="text-left">
										<select name="zone_to_geo_zone[{KEY}][zone_id]" id="zone{KEY}" class="form-control input-sm">
											<option value="0">{LANGE.entry_all_zones}</option>
										</select>
									</td>
									<td class="text-left">
										<button type="button" onclick="var tip = $(this).attr('aria-describedby');$('#' + tip).remove(); $('#zone-to-geo-zone-row{KEY}').remove();" data-toggle="tooltip" class="btn btn-danger btn-sm" title="{LANG.delete}"><i class="fa fa-minus-circle"></i></button>
									</td>
								</tr>
							<!-- END: loop2 -->
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2"></td>
									<td class="text-left">
										<button type="button" onclick="addGeoZone();" data-toggle="tooltip" title="{LANGE.text_add}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div align="center">
					<input class="btn btn-primary btn-sm" name="geo_zone_id" type="hidden" value="{DATA.geo_zone_id}">
					<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
					<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
					<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a> 
				</div>
			</form>		
		</div>
	</div>
</div>
<script type="text/javascript">
var zone_to_geo_zone_row = {zone_to_geo_zone_row};
function addGeoZone() {
	html  = '<tr id="zone-to-geo-zone-row' + zone_to_geo_zone_row + '">';
	html += '  <td class="text-left">';
	html += '  <select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" id="country' + zone_to_geo_zone_row + '" class="form-control input-sm" onchange="getZone(this, \'' + zone_to_geo_zone_row + '\', \'0\');">';
		html += '<option value="0">{LANGE.entry_select_country}</option>';
		<!-- BEGIN: country -->	
		html += '<option value="{COUNTRY.key}">{COUNTRY.name}</option>';
		<!-- END: country -->
	html += '</select></td>';
	html += '  <td class="text-left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" id="zone' + zone_to_geo_zone_row + '" class="form-control input-sm"></select></td>';
	html += '  <td class="text-left"><button onclick="$(\'#zone-to-geo-zone-row' + zone_to_geo_zone_row + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#zone-to-geo-zone tbody').append(html);
 
	$('#zone' + zone_to_geo_zone_row).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=geo_zone&action=zone&country_id=' + $('#country' + zone_to_geo_zone_row).attr('value') + '&zone_id=0');
	
	zone_to_geo_zone_row++;
}

function getZone(element, index, zone_id) {
	$.ajax({
		url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=geo_zone&action=zone&country_id=' + element.value + '&token={TOKEN}&nocache=' + new Date().getTime(),
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'zone_to_geo_zone[' + index + '][country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			html = '<option value="0">{LANGE.entry_all_zones}</option>';
			
			if (json['zone'] && json['zone'] != '') {	
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == zone_id) {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			}

			$('select[name=\'zone_to_geo_zone[' + index + '][zone_id]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
$('select[name$=\'[country_id]\']').trigger('change');
</script>
<!-- END: main -->