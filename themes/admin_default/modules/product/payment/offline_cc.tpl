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
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-payment_offline_cc" class="form-horizontal">
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_total}">{LANGE.entry_total}</span></label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_offline_cc_total" value="{DATA.payment_offline_cc_total}" placeholder="{LANGE.entry_total}" class="form-control input-sm">    
								 <!-- BEGIN: error_total --><div class="text-danger">{error_total} </div><!-- END: error_total -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGE.entry_order_status}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_offline_cc_order_status_id" class="form-control input-sm">
                                   <option value="0"> ---{LANGE.entry_select}--- </option>
								   <!-- BEGIN: order_status -->
									<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
									<!-- END: order_status -->
                                 </select>
								 <!-- BEGIN: error_order_status --><div class="text-danger">{error_order_status} </div><!-- END: error_order_status -->
                            </div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGE.entry_geo_zone}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_offline_cc_geo_zone_id"  class="form-control input-sm">
									<option value="0">{LANGE.entry_all_zone}</option>
										<!-- BEGIN: geo_zone -->
										<option value="{GEOZONE.key}" {GEOZONE.selected}>{GEOZONE.name}</option>
										<!-- END: geo_zone -->
                                </select>
                             </div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGE.entry_email}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <input type="text" name="payment_offline_cc_email" value="{DATA.payment_offline_cc_email}" placeholder="{LANG.payment_offline_cc_email}" class="form-control input-sm">
								<!-- BEGIN: error_email --><div class="text-danger">{error_email} </div><!-- END: error_email -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGE.entry_encryption}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <input type="text" value="{DATA.payment_offline_cc_encryption}" name="payment_offline_cc_encryption" id="payment_offline_cc_encryption" placeholder="{LANG.payment_offline_cc_encryption}" class="form-control input-sm">
								<!-- BEGIN: error_encryption --><div class="text-danger">{error_encryption} </div><!-- END: error_encryption -->
								<a style="cursor:pointer;" onmouseup="getPassword();">{LANGE.entry_safe_password}</a>
							</div>
							
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGE.entry_save_card_name}:</label>
                            <div class="col-sm-20" style="padding-left: 0;padding-top: 10px;">
								<!-- BEGIN: use_cc_name -->
								<input type="radio" name="payment_offline_cc_use_cc_name" value="{CCNAME.key}" {CCNAME.checked}> {CCNAME.name} 
								<!-- END: use_cc_name -->
 							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGE.entry_save_card_type}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								 <!-- BEGIN: use_cc_type --> 
								<input type="radio" name="payment_offline_cc_use_cc_type" value="{CCTYPE.key}" {CCTYPE.checked}> {CCTYPE.name} 
								<!-- END: use_cc_type -->
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGE.entry_accept_credit_card}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
 
								<div><input type="checkbox" class="form-control input-sm" name="payment_offline_cc_accept_visa" value="1" {payment_offline_cc_accept_visa}> {LANGE.cc_visa}</div>
								<div><input type="checkbox" class="form-control input-sm" name="payment_offline_cc_accept_master" value="1" {payment_offline_cc_accept_master}> {LANGE.cc_masterCard}</div>
								<div><input type="checkbox" class="form-control input-sm" name="payment_offline_cc_accept_ae" value="1"{payment_offline_cc_accept_ae}> {LANGE.cc_american_express}</div>
								<div><input type="checkbox" class="form-control input-sm" name="payment_offline_cc_accept_cu" value="1" {payment_offline_cc_accept_cu}> {LANGE.cc_china_unionPay}</div>
								<div><input type="checkbox" class="form-control input-sm" name="payment_offline_cc_accept_jcb" value="1"{payment_offline_cc_accept_jcb}>{LANGE.cc_jsb} </div>
 
							</div>
                        </div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-sort-order">{LANGE.entry_sort_order}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_offline_cc_sort_order" value="{DATA.payment_offline_cc_sort_order}" placeholder="{LANGE.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</div>
						</div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGE.entry_status}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
 
								<select name="payment_offline_cc_status" class="form-control input-sm">
									<!-- BEGIN: payment_status --> 
									<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
									<!-- END: payment_status -->
 									
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

		document.getElementById('payment_offline_cc_encryption').value = rc;

	}
 
</script>
<!-- END: main -->