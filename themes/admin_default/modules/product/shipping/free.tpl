<!-- BEGIN: main -->
<div id="productcontent">
    <!-- BEGIN: warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING} <i class="fa fa-times"></i>
        
    </div>
    <!-- END: warning -->
 
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGP.text_edit}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
				<form action="" method="post" enctype="multipart/form-data" id="form-free" class="form-horizontal">
					<input type="hidden" name="save" value="1">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-total"><span data-toggle="tooltip" title="{LANGP.help_total}" >{LANGP.entry_total}</span></label>
						<div class="col-sm-20">
							<input type="text" name="shipping_free_total" value="{DATA.shipping_free_total}" placeholder="{LANGP.entry_total}" id="input-cost" class="form-control input-sm">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-geo-zone">{LANGP.entry_geo_zone}</label>
						<div class="col-sm-20">
							<select name="shipping_free_geo_zone_id" id="input-geo-zone" class="form-control input-sm">
								<option value="0">{LANGP.entry_all_zone}</option>
								<!-- BEGIN: geo_zone -->
								<option value="{GEOZONE.key}" {GEOZONE.selected}>{GEOZONE.name}</option>
								<!-- END: geo_zone -->
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-status">{LANGP.entry_status}</label>
						<div class="col-sm-20">
							<select name="shipping_free_status" id="input-status" class="form-control input-sm">
								<!-- BEGIN: free_status -->
								<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
								<!-- END: free_status -->
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-sort-order">{LANGP.entry_sort_order}</label>
						<div class="col-sm-20">
							<input type="text" name="shipping_free_sort_order" value="{DATA.shipping_free_sort_order}" placeholder="{LANGP.entry_sort_order}" id="input-sort-order" class="form-control input-sm">
							
						</div>
					</div>
				</form>
			</div>
        </div>
</div>
  

 
<!-- END: main -->