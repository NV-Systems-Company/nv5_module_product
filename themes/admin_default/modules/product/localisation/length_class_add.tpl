<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i>
    </div>
    <!-- END: error_warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
			<div class="pull-right"> <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
						</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a> </div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-length-class" class="form-horizontal"> <input type="hidden" name="length_class_id" value="{DATA.length_class_id}" /> <input name="save" type="hidden" value="1" />
				<div class="tab-content">
					<div class="form-group required"> <label class="col-sm-4 control-label">{LANGE.entry_title}</label>
						<div class="col-sm-20" style="padding-left: 0;">
							<!-- BEGIN: looplang -->
							<div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span> <input type="text" name="length_class_description[{LANG_ID}][title]" value="{VALUE.title}" placeholder="{LANGE.entry_title}" class="form-control input-sm"> </div>
							<!-- BEGIN: error_title -->
							<div class="text-danger">{error_title}</div>
							<!-- END: error_title -->
							<!-- END: looplang -->
						</div>
					</div>
					<div class="form-group required"> <label class="col-sm-4 control-label">{LANGE.entry_unit}</label>
						<div class="col-sm-20" style="padding-left: 0;">
							<!-- BEGIN: looplang1 -->
							<div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span> <input type="text" name="length_class_description[{LANG_ID}][unit]" value="{VALUE.unit}" placeholder="{LANGE.entry_unit}" class="form-control input-sm"> </div>
							<!-- BEGIN: error_unit -->
							<div class="text-danger">{error_unit}</div>
							<!-- END: error_unit -->
							<!-- END: looplang1 -->
						</div>
					</div>
					<div class="form-group"> <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_value}">{LANGE.entry_value}</span></label>
						<div class="col-sm-20"> <input type="text" name="value" value="{DATA.value}" placeholder="{LANGE.entry_value}" class="form-control input-sm"> </div>
					</div>
					<div align="center"> <input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}"> <a class="btn btn-default btn-sm" href="{CANCEL}" title="{LANG.cancel}">{LANG.cancel}</a> </div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>

<!-- END: main -->