<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: warning -->
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle"></i>{WARNING}<i class="fa fa-times"></i>
    </div>
	<!-- END: warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGE.text_add}</h3>
			<div class="pull-right">
				<button type="submit" form="form-coupon" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a> 
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
	 
				<div class="tab-content">
					<div class="tab-pane active" id="tab-general"  >
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANGE.column_name}</label>
							<div class="col-sm-20">
								<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.column_name}" id="input-name" class="form-control input-sm" /> 
								<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-code"><span data-toggle="tooltip" title="{LANGE.help_code}">{LANGE.column_code}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="code" value="{DATA.code}" placeholder="{LANGE.column_code}" id="input-code" class="form-control input-sm" /> 
								<!-- BEGIN: error_code --><div class="text-danger">{error_code}</div><!-- END: error_code -->
								<!-- BEGIN: error_exists --><div class="text-danger">{error_exists}</div><!-- END: error_exists -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-type"><span data-toggle="tooltip" title="{LANGE.help_type}">{LANGE.entry_type}</span>
							</label>
							<div class="col-sm-20">
								<select name="type" id="input-type" class="form-control input-sm">
									<!-- BEGIN: type -->
									<option value="{TYPE.key}" {TYPE.selected}>{TYPE.name}</option>
									<!-- END: type -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-discount">{LANGE.column_discount}</label>
							<div class="col-sm-20">
								<input type="text" name="discount" value="{DATA.discount}" placeholder="{LANGE.column_discount}" id="input-discount" class="form-control input-sm" /> </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-total"><span data-toggle="tooltip" title="{LANGE.help_total}">{LANGE.entry_total}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="total" value="{DATA.total}" placeholder="{LANGE.entry_total}" id="input-total" class="form-control input-sm" /> </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_logged}">{LANGE.entry_logged}</span>
							</label>
							<div class="col-sm-20">
								<!-- BEGIN: login -->
								<label class="radio-inline">
									<input type="radio" name="logged" value="{LOGIN.key}" {LOGIN.checked}/> {LOGIN.name} </label>
								<!-- END: login -->
			 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{LANGE.entry_shipping}</label>
							<div class="col-sm-20">
								<!-- BEGIN: shipping -->
								<label class="radio-inline">
									<input type="radio" name="shipping" value="{SHIPPING.key}" {SHIPPING.checked}/> {SHIPPING.name} </label>
								<!-- END: shipping -->
	 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-product"><span data-toggle="tooltip" title="{LANGE.help_product}">{LANGE.entry_product}</span>
							</label>
							<div class="col-sm-20 drop_product">
								<input type="text" name="product" value="{DATA.product}" placeholder="{LANGE.entry_product}" id="input-product" class="form-control input-sm" />
								<div id="coupon-product" class="well well-sm" style="height: 150px; overflow: auto;"> 
									<!-- BEGIN: product -->
									<div id="coupon-product{PRODUCT_ID}"><i class="fa fa-minus-circle"></i> 
										{PRODUCT_NAME} <input type="hidden" name="coupon_product[]" value="{PRODUCT_ID}">
									</div>
									<!-- END: product -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-category"><span data-toggle="tooltip" title="{LANGE.help_category}">{LANGE.entry_category}</span>
							</label>
							<div class="col-sm-20 drop_product">
								<input type="text" name="category" value="{DATA.category}" placeholder="{LANGE.entry_category}" id="input-category" class="form-control input-sm" />
								<div id="coupon-category" class="well well-sm" style="height: 150px; overflow: auto;"> 
									<!-- BEGIN: category -->
									<div id="coupon-category{CATEGORY_ID}"><i class="fa fa-minus-circle"></i> 
										{CATEGORY_NAME} <input type="hidden" name="coupon_category[]" value="{CATEGORY_ID}">
									</div>
									<!-- END: category -->
								</div>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-date-start">{LANGE.entry_date_start}</label>
							<div class="col-sm-3">
								<div class="form-inline">
									<div class="input-group">
										<input type="text"  id="date_start" name="date_start" value="{date_start}" placeholder="{LANGE.entry_date_start}" readonly required="required" class="form-control input-sm" style="width: 110px;display:inline-block" maxlength="10"/> 
										<span class="input-group-btn">
											<select class="form-control input-sm" name="phour" style="width: 70px;display: inline-block;">
												{phour}
											</select>
										</span>
										<span class="input-group-btn">
											<select class="form-control input-sm" name="pmin" style="width: 70px;display: inline-block;">
												{pmin}
											</select>
										</span>
										
									</div>
								 </div>
								<!-- BEGIN: error_date_start --><div class="text-danger">{error_date_start}</div><!-- END: error_date_start -->
								
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-date-end">{LANGE.entry_date_end}</label>
							<div class="col-sm-3">
								<div class="form-inline">
									<div class="input-group">
										<input type="text" id="date_end" name="date_end" value="{date_end}" placeholder="{LANGE.entry_date_end}" readonly required="required" class="form-control input-sm" style="width: 110px;display:inline-block" maxlength="10"/> 
									
										<span class="input-group-btn">
											<select class="form-control input-sm" name="ehour" style="width: 70px;display: inline-block;">
												{ehour}
											</select> 
										</span>
										<span class="input-group-btn">
											<select class="form-control input-sm" name="emin" style="width: 70px;display: inline-block;">
												{emin}
											</select>
										</span>
										
									</div>
								 </div>
				 
								<!-- BEGIN: error_date_end --><div class="text-danger">{error_date_end}</div><!-- END: error_date_end -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-uses-total"><span data-toggle="tooltip" title="{LANGE.help_uses_total}">{LANGE.entry_uses_total}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="uses_total" value="{DATA.uses_total}" placeholder="Uses Per Coupon" id="input-uses-total" class="form-control input-sm" /> </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="{LANGE.help_uses_total}">{LANGE.entry_uses_customer}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="uses_customer" value="{DATA.uses_customer}" placeholder="{LANGE.entry_uses_customer}" id="input-uses-customer" class="form-control input-sm" /> </div>
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
					<input class="btn btn-primary btn-sm" name="coupon_id" type="hidden" value="{DATA.coupon_id}">
					<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
					<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
					<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a> 
				</div>
			</form>
			
		</div>
	</div>
</div> 
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
$('#date_start,#date_end').datepicker({
	dateFormat : "dd/mm/yy",
	changeMonth : true,
	changeYear : true,
	showOtherMonths : true,
});
 
$('input[name="product"]').autofill({
	'source': function(request, response) {
		$.ajax({
			url: '{JSON_PRODUCT}&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name="product"]').val('');		
		$('#coupon-product' + item['value']).remove();	
		$('#coupon-product').append('<div id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_product[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#coupon-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

$('input[name="category"]').autofill({
	'source': function(request, response) {
		$.ajax({
			url: '{JSON_CATEGORY}&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name="category"]').val('');	
		$('#coupon-category' + item['value']).remove();
		$('#coupon-category').append('<div id="coupon-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#coupon-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
</script>
<!-- END: main -->