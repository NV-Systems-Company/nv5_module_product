<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: warning -->
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i>
    </div>
	<!-- END: warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.text_add}</h3> 
			 <div class="pull-right">
				<button type="submit" form="form-tax-rate" data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i></button>
				<a href="{BACK}" data-toggle="tooltip" data-placement="top" title="{LANG.back}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
				
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-tax-class">
				<input type="hidden" name="save" value="1">
				<input type="hidden" name="tax_class_id" value="{DATA.tax_class_id}">
				<div class="form-group required">
					<label class="col-sm-4 control-label" for="input-title">{LANGE.entry_title}</label>
					<div class="col-sm-20">
						<input type="text" name="title" value="{DATA.title}" placeholder="{LANGE.entry_title}" id="input-title" class="form-control input-sm">
						<!-- BEGIN: error_title --><div class="text-danger">{error_title}</div><!-- END: error_title -->
					</div>
				</div>
				<div class="form-group required">
					<label class="col-sm-4 control-label" for="input-description">{LANGE.entry_description}</label>
					<div class="col-sm-20">
						<input type="text" name="description" value="{DATA.description}" placeholder="{LANGE.entry_description}" id="input-description" class="form-control input-sm">
						<!-- BEGIN: error_description --><div class="text-danger">{error_description}</div><!-- END: error_description -->
					</div>
				</div>
				<table id="tax-rule" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left"><strong>{LANGE.entry_rate}</strong></td>
							<td class="text-left"><strong>{LANGE.entry_based}</strong></td>
							<td class="text-left"><strong>{LANGE.entry_priority}</strong></td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: tax_rule -->
						<tr id="tax-rule-row{TAXRULE.key}">
							<td class="text-left">
								<select name="tax_rule[{TAXRULE.key}][tax_rate_id]" class="form-control input-sm">
									<!-- BEGIN: tax_rate -->
									<option value="{TAXRATE.tax_rate_id}" {TAXRATE.selected}>{TAXRATE.name}</option>
									<!-- END: tax_rate -->
								</select>
							</td>
							<td class="text-left">
								<select name="tax_rule[{TAXRULE.key}][based]" class="form-control input-sm">
									<!-- BEGIN: based -->
									<option value="{BASED.based_id}" {BASED.selected}>{BASED.name}</option>
									<!-- END: based -->
								</select>
							</td>
							<td class="text-left">
								<input type="text" name="tax_rule[{TAXRULE.key}][priority]" value="{TAXRULE.priority}" placeholder="{LANGE.entry_priority}" class="form-control input-sm">
							</td>
							<td class="text-left">
								<button type="button" onclick="$('#tax-rule-row{TAXRULE.key}').remove();" data-toggle="tooltip" title="{LANG.remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
								</button>
							</td>
						</tr>
						<!-- END: tax_rule -->
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<td class="text-left">
								<button type="button" onclick="addRule();" data-toggle="tooltip"  class="btn btn-primary btn-sm"  title="{LANGE.text_add_rule}"><i class="fa fa-plus-circle"></i>
								</button>
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
    var tax_rule_row = {tax_rule_row};

    function addRule() {
        html = '<tr id="tax-rule-row' + tax_rule_row + '">';
        html += '  <td class="text-left"><select name="tax_rule[' + tax_rule_row + '][tax_rate_id]" class="form-control input-sm">';
        <!-- BEGIN: tax_rate -->
	    html += '    <option value="{TAXRATE.tax_rate_id}">{TAXRATE.name}</option>';
        <!-- END: tax_rate -->
        html += '  </select></td>';
        html += '  <td class="text-left"><select name="tax_rule[' + tax_rule_row + '][based]" class="form-control input-sm">';
        <!-- BEGIN: based -->
        html += '    <option value="{BASED.based_id}">{BASED.name}</option>';
        <!-- END: based -->
        html += '  </select></td>';
        html += '  <td class="text-left"><input type="text" name="tax_rule[' + tax_rule_row + '][priority]" value="" placeholder="{LANGE.entry_priority}" class="form-control input-sm" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#tax-rule-row' + tax_rule_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#tax-rule tbody').append(html);

        tax_rule_row++;
    }
</script>
<!-- END: main -->