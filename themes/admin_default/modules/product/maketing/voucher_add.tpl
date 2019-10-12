<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: warning -->
	<div class="alert alert-danger">	 
		<i class="fa fa-exclamation-circle"></i> {WARNING} <i class="fa fa-times"></i> 	 
	</div>
	<!-- END: warning -->
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
							<label class="col-sm-4 control-label" for="input-code"><span data-toggle="tooltip" title="{LANGE.help_code}">{LANGE.entry_code}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="code" value="{DATA.code}" placeholder="{LANGE.entry_code}" id="input-code" class="form-control input-sm" /> 
								<!-- BEGIN: error_code --><div class="text-danger">{error_code}</div><!-- END: error_code -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_from_name}</label>
							<div class="col-sm-20">
								<input type="text" name="from_name" value="{DATA.from_name}" placeholder="{LANGE.entry_from_name}" id="input-name" class="form-control input-sm" /> 
								<!-- BEGIN: error_from_name --><div class="text-danger">{error_from_name}</div><!-- END: error_from_name -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_from_email}</label>
							<div class="col-sm-20">
								<input type="text" name="from_email" value="{DATA.from_email}" placeholder="{LANGE.entry_from_email}" id="input-from-email" class="form-control input-sm">
								<!-- BEGIN: error_from_email --><div class="text-danger">{error_from_email}</div><!-- END: error_from_email -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_to_name}</label>
							<div class="col-sm-20">
								<input type="text" name="to_name" value="{DATA.to_name}" placeholder="{LANGE.entry_to_name}" id="input-to-name" class="form-control input-sm">
								<!-- BEGIN: error_to_name --><div class="text-danger">{error_to_name}</div><!-- END: error_to_name -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_to_email}</label>
							<div class="col-sm-20">
								<input type="text" name="to_email" value="{DATA.to_email}" placeholder="{LANGE.entry_to_email}" id="input-to-email" class="form-control input-sm">
								<!-- BEGIN: error_to_email --><div class="text-danger">{error_to_email}</div><!-- END: error_to_email -->
								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-theme">{LANGE.entry_theme}</label>
							<div class="col-sm-20">
								<select name="voucher_theme_id" id="input-theme" class="form-control input-sm">
									<!-- BEGIN: voucher_theme -->
									<option value="{VOUCHER_THEME.key}" {VOUCHER_THEME.selected}>{VOUCHER_THEME.name}</option>
									<!-- END: voucher_theme -->
								</select>
							</div>
						</div>
						 
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-message">
								{LANGE.entry_message}
							</label>
							<div class="col-sm-20">
								<textarea name="message" rows="5" placeholder="{LANGE.entry_message}" id="input-message" class="form-control input-sm">{DATA.message}</textarea>
								<!-- BEGIN: error_message --><div class="text-danger">{error_message}</div><!-- END: error_message -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-amount"> 
								{LANGE.entry_amount}
							</label>
							<div class="col-sm-20">
								<input type="text" name="amount" value="{DATA.amount}" placeholder="{LANGE.entry_amount}" id="input-amount" class="form-control input-sm">
							</div>
						</div>
 
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-status">{LANGE.entry_status}</label>
							<div class="col-sm-20">
								<select name="status" id="input-status" class="form-control input-sm">
									<!-- BEGIN: status -->
									<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
									<!-- END: status -->
								</select>
							</div>
						</div>
					</div>
				</div>
				<div align="center">
					<input class="btn btn-primary btn-sm" name="voucher_id" type="hidden" value="{DATA.voucher_id}">
					<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
					<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
					<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a> 
				</div>
			</form>
		</div>
	</div>
</div> 
<!-- END: main -->