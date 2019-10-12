<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <!-- BEGIN:  error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}
        <i class="fa fa-times"></i>
        <br>
    </div>
    <!-- END: error_warning -->
    
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-review" class="form-horizontal">
                    <input type="hidden" name="review_id" value="{DATA.review_id}" />
                    <input name="save" type="hidden" value="1" />
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-customer-name">{LANGE.entry_customer_name}</label>
						<div class="col-sm-20">
							<input type="text" disabled name="customer_name" value="{DATA.customer_name}" placeholder="{LANGE.entry_customer_name}" id="input-customer-name" class="form-control input-sm">
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-product"><span data-toggle="tooltip" title="{LANGE.help_product}">{LANGE.entry_product}</span>
						</label>
						<div class="col-sm-20">
							<input type="text" disabled name="product" value="{DATA.product}" placeholder="{LANGE.entry_product}" id="input-product" class="form-control input-sm" autocomplete="off">
							<ul class="dropdown-menu" style="top: 35px; left: 15px; display: none;">
								<li data-value="{DATA.product_id}"><a href="#">{DATA.product}</a> </li>
							</ul>
							<input type="hidden" name="product_id" value="{DATA.product_id}">
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-title">{LANGE.entry_title}</label>
						<div class="col-sm-20">
							<input type="text" name="title" value="{DATA.title}" placeholder="{LANGE.entry_title}" id="input-title" class="form-control input-sm">
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-text">{LANGE.entry_detail}</label>
						<div class="col-sm-20">
							<textarea name="detail" cols="60" rows="8" placeholder="{LANGE.entry_detail}" id="input-text" class="form-control input-sm">{DATA.detail}</textarea>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-name">{LANGE.entry_rating}</label>
						<div class="col-sm-20">
							<!-- BEGIN: rating -->
							<label class="radio-inline">
								<input type="radio" disabled name="rating" value="{RATING.key}" {RATING.checked}> {RATING.name}
							</label>
							<!-- END: rating -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-status">Status</label>
						<div class="col-sm-20">
							<select name="status" id="input-status" class="form-control input-sm">
								<!-- BEGIN: status -->
								<option value="{STATUS.key}" {STATUS.selected} >{STATUS.name}</option>
								<!-- END: status -->
							</select>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/content.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/autofill.js"></script>
  

<script type="text/javascript">
$('input[name=\'product\']').autofill({
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
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);		
	}	
});
</script>
 
<!-- END: main -->