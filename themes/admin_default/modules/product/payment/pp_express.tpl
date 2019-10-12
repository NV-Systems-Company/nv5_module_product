<!-- BEGIN: main -->
<div id="productcontent">
    <!-- BEGIN: warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i> 
    </div>
	<!-- END: warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGE.text_edit}</h3>
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-ppexpress" class="form-horizontal">
				<input type="hidden" name="save" value="1">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-api-details" data-toggle="tab">{LANGE.tab_general}</a></li>
					<li class=""><a href="#tab-general" data-toggle="tab">{LANGE.tab_api_details}</a></li>
					<li class=""><a href="#tab-status" data-toggle="tab">{LANGE.tab_order_status}</a></li>
					<li class=""><a href="#tab-customise" data-toggle="tab">{LANGE.tab_customise}</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab-api-details">
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="entry-username">{LANGE.entry_username}</label>
							<div class="col-sm-20">
								<input type="text" name="payment_pp_express_username" value="{DATA.payment_pp_express_username}" placeholder="{LANGE.entry_username}" id="entry-username" class="form-control input-sm">
								<!-- BEGIN: error_username --><div class="text-danger">{error_username}</div><!-- END: error_username -->
							</div>
						</div>
						<div class="form-group required">
								<label class="col-sm-4 control-label" for="entry-password">{LANGE.entry_password}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_pp_express_password" value="{DATA.payment_pp_express_password}" placeholder="{LANGE.entry_password}" id="entry-password" class="form-control input-sm">
										<!-- BEGIN: error_password --><div class="text-danger">{error_password}</div><!-- END: error_password -->
								
								</div>
						</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="entry-signature">{LANGE.entry_signature}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_pp_express_signature" value="{DATA.payment_pp_express_signature}" placeholder="{LANGE.entry_signature}" id="entry-signature" class="form-control input-sm">
									<!-- BEGIN: error_signature --><div class="text-danger">{error_signature}</div><!-- END: error_signature -->
								
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_ipn}">{LANGE.text_ipn}</span>
								</label>
								<div class="col-sm-20">
									<div class="input-group"> <span class="input-group-addon"><i class="fa fa-link"></i></span>
										<input type="text" value="" class="form-control input-sm">
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-general">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-live-demo">{LANGE.entry_test}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_test" id="input-live-demo" class="form-control input-sm">
										<!-- BEGIN: test -->
										<option value="{YESNO.key}" {YESNO.selected}>{YESNO.name}</option>
										<!-- END: test -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-debug">{LANGE.entry_debug}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_debug" id="input-debug" class="form-control input-sm">
										<!-- BEGIN: debug -->
										<option value="{YESNO.key}" {YESNO.selected}>{YESNO.name}</option>
										<!-- END: debug -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-currency"><span data-toggle="tooltip" title="{LANGE.help_currency}">{LANGE.entry_currency}</span>
								</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_currency" id="input-currency" class="form-control input-sm">
										<!-- BEGIN: currency -->
										<option value="{CURRENCY.key}" {CURRENCY.selected}>{CURRENCY.name}</option>
										<!-- END: currency -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-recurring-cancel">{LANGE.entry_recurring_cancellation}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_recurring_cancel_status" id="input-recurring-cancel" class="form-control input-sm">
										<!-- BEGIN: recurring_cancel_status -->
										<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
										<!-- END: recurring_cancel_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-method">{LANGE.entry_method}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_method" id="input-method" class="form-control input-sm">
										<!-- BEGIN: method -->
										<option value="{METHOD.key}" {METHOD.selected}>{METHOD.name}</option>
										<!-- END: method -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-total"><span data-toggle="tooltip" title="{LANGE.help_total}">{LANGE.entry_total}</span>
								</label>
								<div class="col-sm-20">
									<input type="text" name="payment_pp_express_total" value="{DATA.payment_pp_express_total}" placeholder="{LANGE.entry_total}" id="input-total" class="form-control input-sm">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-sort-order">{LANGE.entry_sort_order}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_pp_express_sort_order" value="{DATA.payment_pp_express_sort_order}" placeholder="{LANGE.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-geo-zone">{LANGE.entry_geo_zone}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_geo_zone_id" id="input-geo-zone" class="form-control input-sm">
										<option value="0">{LANGE.entry_all_zone}</option>
										<!-- BEGIN: geo_zone -->
										<option value="{GEOZONE.key}" {GEOZONE.selected}>{GEOZONE.name}</option>
										<!-- END: geo_zone -->
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-status">{LANGE.entry_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_status" id="input-status" class="form-control input-sm">
										<!-- BEGIN: payment_status -->
										<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
										<!-- END: payment_status -->
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-status">
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_canceled_reversal_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_canceled_reversal_status_id" class="form-control input-sm">
										<!-- BEGIN: canceled_reversal_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: canceled_reversal_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_completed_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_completed_status_id" class="form-control input-sm">
										<!-- BEGIN: completed_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: completed_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_denied_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_denied_status_id" class="form-control input-sm">
										<!-- BEGIN: denied_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: denied_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_expired_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_expired_status_id" class="form-control input-sm">
										<!-- BEGIN: expired_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: expired_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_failed_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_failed_status_id" class="form-control input-sm">
										<!-- BEGIN: failed_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: failed_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_pending_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_pending_status_id" class="form-control input-sm">
										<!-- BEGIN: pending_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: pending_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_processed_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_processed_status_id" class="form-control input-sm">
										<!-- BEGIN: processed_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: processed_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_refunded_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_refunded_status_id" class="form-control input-sm">
										<!-- BEGIN: refunded_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: refunded_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_reversed_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_reversed_status_id" class="form-control input-sm">
										<!-- BEGIN: reversed_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: reversed_status -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{LANGE.entry_voided_status}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_voided_status_id" class="form-control input-sm">
										<!-- BEGIN: voided_status -->
										<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
										<!-- END: voided_status -->
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-customise">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-notes">{LANGE.entry_allow_notes}</label>
								<div class="col-sm-20">
									<select name="payment_pp_express_allow_note" id="input-notes" class="form-control input-sm">
										<!-- BEGIN: allow_note -->
										<option value="{YESNO.key}" {YESNO.selected}>{YESNO.name}</option>
										<!-- END: allow_note -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-page-color"><span data-toggle="tooltip" title="{LANGE.help_colour}">{LANGE.entry_page_colour}</span>
								</label>
								<div class="col-sm-20">
									<input type="text" name="payment_pp_express_page_colour" value="{DATA.payment_pp_express_page_colour}" placeholder="{LANGE.entry_page_colour}" id="input-page-color" class="form-control input-sm">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-image"><span data-toggle="tooltip" title="{LANGE.help_logo}">{LANGE.entry_logo}</span>
								</label>
								<div class="col-sm-20"><a  href="javascript:void(0);" id="thumb-image0" rel="0" data-toggle="image" class="img-thumbnail"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/no_image.png" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/no_image.png" style="max-width: 100px;"></a>
									<input type="hidden" name="payment_pp_express_logo" value="{DATA.payment_pp_express_logo}" id="input-image0">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
        </div> 
</div>
  

<script type="text/javascript">
 
	function Refresh()
	{
		var img = $('#input-image0').val();	
		var oldimage = $('#thumb-image0 img').attr('src');
		if( img != '' && oldimage != img)
		{
			$('#thumb-image0 img').attr('src', img);	
			$('.popover').remove();
		}	
	}
	setInterval(Refresh, 1000);
 
	function select_image( area )
	{
		var path = "{UPLOAD_PATH}";
		var currentpath = "{UPLOAD_CURRENT}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	}
        
</script> 
<!-- END: main -->