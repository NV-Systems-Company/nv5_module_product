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
                <form action="" method="post" enctype="multipart/form-data" id="form-payment_baokim-pro" class="form-horizontal">
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_merchant}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_merchant" value="{DATA.payment_baokim_merchant}" placeholder="{LANGP.entry_merchant}" class="form-control input-sm">    
								 <!-- BEGIN: error_merchant --><div class="text-danger">{error_merchant} </div><!-- END: error_merchant -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_security}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_security" value="{DATA.payment_baokim_security}" placeholder="{LANGP.entry_security}" class="form-control input-sm">    
								 <!-- BEGIN: error_security --><div class="text-danger">{error_security} </div><!-- END: error_security -->
							</div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_business}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<input type="text" name="payment_baokim_business" value="{DATA.payment_baokim_business}" placeholder="{LANGP.entry_business}" class="form-control input-sm">    
								 <!-- BEGIN: error_business --><div class="text-danger">{error_business} </div><!-- END: error_business -->
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_server} :</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_server" id="input-server"  class="form-control input-sm">
								   <!-- BEGIN: server -->
									<option value="{SERVER.key}" {SERVER.selected}>{SERVER.name}</option>
									<!-- END: server -->
                                 </select>
								 <!-- BEGIN: error_server --><div class="text-danger">{error_server} </div><!-- END: error_server -->
                            </div>
                        </div> 
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_order_status}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_order_status_id" class="form-control input-sm">
								   <!-- BEGIN: order_status -->
									<option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
									<!-- END: order_status -->
                                 </select>
								 <!-- BEGIN: error_order_status  --><div class="text-danger">{error_order_status} </div><!-- END: error_order_status -->
                            </div>
                        </div>
						<div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGP.entry_geo_zone}</label>
                            <div class="col-sm-20" style="padding-left: 0;">
                                <select name="payment_baokim_geo_zone_id"  class="form-control input-sm">
									<option value="0">{LANGP.entry_all_zone}</option>
										<!-- BEGIN: geo_zone -->
										<option value="{GEOZONE.key}" {GEOZONE.selected}>{GEOZONE.name}</option>
										<!-- END: geo_zone -->
                                </select>
                             </div>
                        </div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-sort-order">{LANGP.entry_sort_order}:</label>
								<div class="col-sm-20">
									<input type="text" name="payment_baokim_sort_order" value="{DATA.payment_baokim_sort_order}" placeholder="{LANGP.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
								</div>
						</div>
						<div class="form-group">
                            <label class="col-sm-4 control-label">{LANGP.entry_status}:</label>
                            <div class="col-sm-20" style="padding-left: 0;">
								<select name="payment_baokim_status" class="form-control input-sm">
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
<!-- END: main -->