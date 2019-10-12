<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
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
			<form action="" method="post" enctype="multipart/form-data" id="form-currency" class="form-horizontal">
				<div class="tab-content">
					<div class="tab-pane active" id="tab-general" >
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-title">{LANGE.entry_title}</label>
							<div class="col-sm-20">
								<input type="text" name="title" value="{DATA.title}" placeholder="{LANGE.entry_title}" id="input-title" class="form-control input-sm" /> 
								<!-- BEGIN: currency_error_title --><div class="text-danger">{currency_error_title}</div><!-- END: currency_error_title -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-code">
							<span data-toggle="tooltip" data-html="true" data-trigger="click" title="{LANGE.help_code}">{LANGE.entry_code}</span>
							</label>
							<div class="col-sm-20">
								<!-- <input type="text" name="code" value="{DATA.code}" placeholder="{LANG.currency_code}" id="input-code" class="form-control input-sm" /> --> 
								<select class="form-control input-sm" name="code">
									<!-- BEGIN: currency -->
									<option value="{DATAMONEY.value}"{DATAMONEY.selected}>{DATAMONEY.title}</option>
									<!-- END: currency -->
								</select>
								<!-- BEGIN: currency_error_code --><div class="text-danger">{currency_error_code}</div><!-- END: currency_error_code -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-symbol_left">{LANGE.entry_symbol_left}:</label>
							<div class="col-sm-20">
								<input type="text" name="symbol_left" value="{DATA.symbol_left}" placeholder="{LANGE.entry_symbol_left}" id="input-symbol_left" class="form-control input-sm" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-symbol_right">{LANGE.entry_symbol_right}:</label>
							<div class="col-sm-20">
								<input type="text" name="symbol_right" value="{DATA.symbol_right}" placeholder="{LANGE.entry_symbol_right}" id="input-symbol_right" class="form-control input-sm" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-decimal_place">{LANGE.entry_decimal_place}</label>
							<div class="col-sm-20">
								<input type="text" name="decimal_place" value="{DATA.decimal_place}" placeholder="{LANGE.entry_decimal_place}" id="input-decimal_place" class="form-control input-sm" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-value">
							<span data-toggle="tooltip" data-html="true" title="{LANGE.help_value}">{LANGE.entry_value}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="value" value="{DATA.value}" placeholder="{LANGE.entry_value}" id="input-value" class="form-control input-sm" /> 
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
					<input class="btn btn-primary btn-sm" name="currency_id" type="hidden" value="{DATA.currency_id}">
					<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
					<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
					<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a> 
				</div>
			</form>		
		</div>
	</div>
</div>
  

<!-- END: main -->