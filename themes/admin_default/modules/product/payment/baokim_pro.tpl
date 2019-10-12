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
                <form action="" method="post" enctype="multipart/form-data" id="form-baokim-pro" class="form-horizontal">
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_email}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_pro_email" value="{DATA.payment_baokim_pro_email}" placeholder="{LANGP.entry_email}" class="form-control input-sm">    
								 <!-- BEGIN: error_email --><div class="text-danger">{error_email} </div><!-- END: error_email -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_username}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_pro_username" value="{DATA.payment_baokim_pro_username}" placeholder="{LANGP.entry_username}" class="form-control input-sm">    
								 <!-- BEGIN: error_username --><div class="text-danger">{error_username} </div><!-- END: error_username -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_password}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_pro_password" value="{DATA.payment_baokim_pro_password}" placeholder="{LANGP.entry_password}" class="form-control input-sm">    
								 <!-- BEGIN: error_password --><div class="text-danger">{error_password} </div><!-- END: error_password -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_signature}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<textarea  style="min-height: 200px" name="payment_baokim_pro_signature" placeholder="{LANGP.entry_signature}" class="form-control input-sm">{DATA.payment_baokim_pro_signature}</textarea>   
								 <!-- BEGIN: error_signature --><div class="text-danger">{error_signature} </div><!-- END: error_signature -->
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_server} </label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_pro_server" id="input-server"  class="form-control input-sm">
								   <!-- BEGIN: server -->
									<option value="{SERVER.key}" {SERVER.selected}>{SERVER.name}</option>
									<!-- END: server -->
                                 </select>
								 <!-- BEGIN: error_server --><div class="text-danger">{error_server} </div><!-- END: error_server -->
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_transaction}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_pro_transaction" class="form-control input-sm">
								   <!-- BEGIN: transaction -->
									<option value="{TRANSACTION.key}" {TRANSACTION.selected}>{TRANSACTION.name}</option>
									<!-- END: transaction -->
                                 </select>
								 <!-- BEGIN: error_transaction --><div class="text-danger">{error_transaction} </div><!-- END: error_transaction -->
                            </div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_log_file}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_pro_log_file" value="{DATA.payment_baokim_pro_log_file}" placeholder="{LANGP.entry_log_file}" class="form-control input-sm">    
								 <!-- BEGIN: error_log_file --><div class="text-danger">{error_log_file} </div><!-- END: error_log_file -->
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_order_status}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_pro_order_status_id" class="form-control input-sm">
								   <!-- BEGIN: order_status -->
									<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
									<!-- END: order_status -->
                                 </select>
								 <!-- BEGIN: error_order_status  --><div class="text-danger">{error_order_status} </div><!-- END: error_order_status -->
                            </div>
                        </div>
 
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-sort-order">{LANGP.entry_sort_order}</label>
								<div class="col-sm-20">
									<input type="text" name="payment_baokim_pro_sort_order" value="{DATA.payment_baokim_pro_sort_order}" placeholder="{LANGP.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</div>
						</div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_status}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<select name="payment_baokim_pro_status" class="form-control input-sm">
									<!-- BEGIN: status --> 
									<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
									<!-- END: status -->	
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

		document.getElementById('payment_baokim_pro_encryption').value = rc;

	}
 
</script>
<!-- END: main -->