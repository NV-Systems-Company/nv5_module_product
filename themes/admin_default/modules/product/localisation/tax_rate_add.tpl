<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: warning -->
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle"></i> {error_warning} <i class="fa fa-times"></i>
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
			<form action="" method="post" enctype="multipart/form-data" id="form-tax-rate" class="form-horizontal">
				<input type="hidden" name="save" value="1">
				<input type="hidden" name="tax_rate_id" value="{DATA.tax_rate_id}">
				<div class="form-group required">
					<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_name}</label>
					<div class="col-sm-20">
						<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.entry_name}" id="input-name" class="form-control input-sm">
						<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
					</div>
				</div>
				<div class="form-group required">
					<label class="col-sm-4 control-label" for="input-rate">{LANGE.entry_rate}</label>
					<div class="col-sm-20">
						
						<input type="text" name="rate" value="{DATA.rate}" placeholder="{LANGE.entry_rate}" id="input-rate" class="form-control input-sm">
						<!-- BEGIN: error_rate --><div class="text-danger">{error_rate}</div><!-- END: error_rate -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="input-type">{LANGE.entry_type}</label>
					<div class="col-sm-20">
						<select name="type" id="input-type" class="form-control input-sm">
							<!-- BEGIN: type -->
							<option value="{TYPE_KEY}" {TYPE_SELECTED}>{TYPE_VALUE}</option>
							<!-- END: type -->
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{LANGE.entry_customer_group}</label>
					<div class="col-sm-20">
						<div class="checkbox">
							<!-- BEGIN: customer_group -->
							<label>
								<input type="checkbox" name="tax_rate_customer_group[]" value="{GROUP.customer_group_id}" {GROUP.checked}> {GROUP.name}
							</label>
							<!-- END: customer_group -->
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="input-geo-zone">{LANGE.entry_geo_zone}</label>
					<div class="col-sm-20">
						<select name="geo_zone_id" id="input-geo-zone" class="form-control input-sm">
							<!-- BEGIN: geo_zone -->
							<option value="{GEOZONES.geo_zone_id}" {GEOZONES.selected} >{GEOZONES.name}</option>
							<!-- END: geo_zone -->						
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END: main -->