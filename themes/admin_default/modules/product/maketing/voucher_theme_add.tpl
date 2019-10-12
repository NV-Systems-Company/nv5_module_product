<!-- BEGIN: main -->
{AddMenu} 
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}  <i class="fa fa-times"></i>
    </div>
    <!-- END: error_warning -->
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
            <form action="" method="post" enctype="multipart/form-data" id="form-voucher_theme" class="form-horizontal">
				<input type="hidden" name="voucher_theme_id" value="{DATA.voucher_theme_id}" />
				<input name="save" type="hidden" value="1" />
				<div class="tab-content">
                    <div class="form-group required">
                        <label class="col-sm-4 control-label">{LANGE.entry_name}</label>
                        <div class="col-sm-20" style="padding-left: 0;">
                            <!-- BEGIN: looplang -->
                            <div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
                                <input type="text" name="voucher_theme_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm">
                            </div>
							<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->      
                            <!-- END: looplang -->

                        </div>
                    </div>
					<div class="form-group">
						<label class="col-sm-4 control-label">{LANGE.entry_image}</label>
						<div class="col-sm-20">
							<div class="form-inline row">
								<div class="input-group">
									<input type="text" class="form-control input-sm" style="width:300px" name="image" id="image" value="{DATA.image}" placeholder="{LANGE.entry_image}">
									<span class="input-group-btn">
										<button class="btn btn-primary btn-sm" type="button" id="btn_upload"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
									</span>
								</div>
                            </div>
                        </div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#btn_upload').click(function() {
	var area = 'image';
	var path = '{UPLOAD_PATH}';
	var currentpath = '{UPLOAD_CURRENT}';
	var type = 'image';
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>
<!-- END: main -->