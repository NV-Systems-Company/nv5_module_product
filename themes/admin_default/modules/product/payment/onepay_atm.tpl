<!-- BEGIN: main -->
<div id="productcontent">
    <!-- BEGIN: warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i>
    </div>
    <!-- END: warning -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGP.heading_title}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-onepay-atm" class="form-horizontal">
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_url_paygate}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_onepay_atm_url_paygate" value="{DATA.payment_onepay_atm_url_paygate}" placeholder="{LANGP.entry_url_paygate}" class="form-control input-sm">    
								 <!-- BEGIN: error_url_paygate --><div class="text-danger">{error_url_paygate} </div><!-- END: error_url_paygate -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_merchant_id}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_onepay_atm_merchant_id" value="{DATA.payment_onepay_atm_merchant_id}" placeholder="{LANGP.entry_merchant_id}" class="form-control input-sm">    
								 <!-- BEGIN: error_merchant_id --><div class="text-danger">{error_merchant_id} </div><!-- END: error_merchant_id -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_access_code}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_onepay_atm_access_code" value="{DATA.payment_onepay_atm_access_code}" placeholder="{LANGP.entry_access_code}" class="form-control input-sm">    
								 <!-- BEGIN: error_access_code --><div class="text-danger">{error_access_code} </div><!-- END: error_access_code -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_hash_code}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_onepay_atm_hash_code" value="{DATA.payment_onepay_atm_hash_code}" placeholder="{LANGP.entry_hash_code}" class="form-control input-sm">    
								 <!-- BEGIN: error_hash_code --><div class="text-danger">{error_hash_code} </div><!-- END: error_hash_code -->
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGP.entry_help_completed_status}">{LANGP.entry_completed_status}</span></label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_onepay_atm_completed_status_id" class="form-control input-sm">
								   <!-- BEGIN: completed_status -->
									<option value="{COMPLETED_STATUS.key}" {COMPLETED_STATUS.selected}>{COMPLETED_STATUS.name}</option>
									<!-- END: completed_status -->
                                 </select>
								 <!-- BEGIN: error_completed_status --><div class="text-danger">{error_completed_status} </div><!-- END: error_completed_status -->
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGP.entry_help_failed_status}">{LANGP.entry_failed_status}</span></label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_onepay_atm_failed_status_id" class="form-control input-sm">
								   <!-- BEGIN: failed_status -->
									<option value="{FAILED_STATUS.key}" {FAILED_STATUS.selected}>{FAILED_STATUS.name}</option>
									<!-- END: failed_status -->
                                 </select>
								 <!-- BEGIN: error_failed_status --><div class="text-danger">{error_failed_status} </div><!-- END: error_failed_status -->
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGP.entry_help_pending_status}">{LANGP.entry_pending_status}</span></label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_onepay_atm_pending_status_id" class="form-control input-sm">
								   <!-- BEGIN: pending_status -->
									<option value="{PENDING_STATUS.key}" {PENDING_STATUS.selected}>{PENDING_STATUS.name}</option>
									<!-- END: pending_status -->
                                 </select>
								 <!-- BEGIN: error_pending_status --><div class="text-danger">{error_pending_status} </div><!-- END: error_pending_status -->
                            </div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_geo_zone}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_onepay_atm_geo_zone_id"  class="form-control input-sm">
									<option value="0">{LANGP.entry_all_zone}</option>
										<!-- BEGIN: geo_zone -->
										<option value="{GEOZONE.key}" {GEOZONE.selected}>{GEOZONE.name}</option>
										<!-- END: geo_zone -->
                                </select>
                             </div>
                        </div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-sort-order">{LANGP.entry_sort_order}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_onepay_atm_sort_order" value="{DATA.payment_onepay_atm_sort_order}" placeholder="{LANGP.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</div>
						</div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_status}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<select name="payment_onepay_atm_status" class="form-control input-sm">
									<!-- BEGIN: payment_onepay_atm_status --> 
									<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
									<!-- END: payment_onepay_atm_status -->	
								</select>
							</div>
                        </div>
                        <div align="center">
                            <input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
                            <a class="btn btn-primary btn-sm" href="{CANCEL}" title="{LANG.cancel}">{LANG.cancel}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
      

    <script type="text/javascript">

	function getRandomNum(lbound, ubound) {
		return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
	}

	function getRandomChar() {
	var numberChars = "0123456789";
	var lowerChars = "abcdefghijklmnopqrstuvwxyz";
	var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var otherChars = "`@#$%";
	var charSet = numberChars;
	charSet += lowerChars;
	charSet += upperChars;
	charSet += otherChars;
	return charSet.charAt(getRandomNum(0, charSet.length));
	}

	function getPassword() {

		length = 44;
			
		var rc = "";
		if (length > 0)
		rc = rc + getRandomChar();
		for (var idx = 1; idx < length; ++idx) {
		rc = rc + getRandomChar();
		}

		document.getElementById('payment_onepay_atm_encryption').value = rc;

	}
 
</script>
<!-- END: main -->