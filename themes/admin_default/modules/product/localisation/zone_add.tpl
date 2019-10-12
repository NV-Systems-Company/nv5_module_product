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
				<button type="submit" form="form-coupon" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a> 
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
		 
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
							<label class="col-sm-4 control-label" for="input-code">{LANGE.column_code}</label>
							<div class="col-sm-20">
								<input type="text" name="code" value="{DATA.code}" placeholder="{LANGE.column_code}" id="input-code" class="form-control input-sm" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-status">{LANGE.entry_country}</label>
							<div class="col-sm-20">
							  <select name="country_id" id="input-country" class="form-control input-sm">
								<!-- BEGIN: country -->
								<option value="{country_id}" {country_checked}>{country_name}</option>
								<!-- END: country -->
							  </select>
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
					<input class="btn btn-primary btn-sm" name="zone_id" type="hidden" value="{DATA.zone_id}">
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