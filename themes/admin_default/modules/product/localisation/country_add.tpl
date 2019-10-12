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
				<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
				<div class="pull-right">
					<button type="submit" form="form-country" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
					</button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a> 
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="panel-body">
				<form action="" method="post" enctype="multipart/form-data" id="form-country" class="form-horizontal">
			 
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general" style="padding-top: 20px;">
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-name">{LANGE.column_name}</label>
								<div class="col-sm-20">
									<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.column_name}" id="input-name" class="form-control input-sm" /> 
									<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-name">{LANGE.column_iso_code_2}</label>
								<div class="col-sm-20">
									<input type="text" name="iso_code_2" value="{DATA.iso_code_2}" placeholder="{LANGE.column_iso_code_2}" id="input-name" class="form-control input-sm" /> 
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-name">{LANGE.column_iso_code_3}</label>
								<div class="col-sm-20">
									<input type="text" name="iso_code_3" value="{DATA.iso_code_3}" placeholder="{LANGE.column_iso_code_3}" id="input-name" class="form-control input-sm" /> 
								</div>
							</div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="input-address-format"><span data-toggle="tooltip" data-html="true" title="{LANGE.help_address_format}">{LANGE.entry_address_format}</span></label>
							<div class="col-sm-20">
							  <textarea name="address_format" rows="5" placeholder="{DATA.country_entry_address_format}" id="input-address-format" class="form-control input-sm">{DATA.address_format}</textarea>
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label">{LANGE.entry_postcode_required}</label>
							<div class="col-sm-20">
								<!-- BEGIN: postcode_required -->
									<label class="radio-inline"> <input type="radio" name="postcode_required" value="{postcode_required_key}" {postcode_required_checked}> {postcode_required_name} </label>
								<!-- END: postcode_required -->
		 
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="input-status">{LANGE.entry_status}</label>
							<div class="col-sm-20">
							  <select name="status" id="input-status" class="form-control input-sm">
								<!-- BEGIN: status -->
								<option value="{status_key}" {status_checked}>{status_name}</option>
								<!-- END: status -->
							  </select>
							</div>
						  </div>
						</div>
					</div>
					<div align="center">
						<input class="btn btn-primary btn-sm" name="country_id" type="hidden" value="{DATA.country_id}">
						<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
						<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
						<a class="btn btn-primary btn-sm" href="{CANCEL}" title="{LANG.cancel}">{LANG.cancel}</a> 
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>
  

<!-- END: main -->