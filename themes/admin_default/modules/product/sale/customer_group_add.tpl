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
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i></button> 
				<a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-customer-group" class="form-horizontal">
				<input type="hidden" name="customer_group_id" value="{DATA.customer_group_id}" />
				<input name="save" type="hidden" value="1" />
				<div class="tab-content">
					<div class="form-group required">
						<label class="col-sm-4 control-label">{LANGE.entry_name}</label>
						<div class="col-sm-20" style="padding-left: 0;">
							<!-- BEGIN: looplang -->
							<div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
								<input type="text" name="customer_group_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm" maxlength="240">
							</div>
							<!-- BEGIN: error_name -->
							<div class="text-danger">{error_name}</div>
							<!-- END: error_name -->
							<!-- END: looplang -->
						</div>
					</div>
					<!-- BEGIN: looplang1 -->
					<div class="form-group">
						<label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description}</label>
						<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
							<textarea name="customer_group_description[{LANG_ID}][description]" rows="5" placeholder="{LANGE.entry_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
						</div>
					</div>
					<!-- END: looplang1 -->
					<div class="form-group">
						<label class="col-sm-4 control-label"><span data-toggle="tooltip"  title="{LANGE.help_approval}">{LANGE.entry_approval}</span></label>
						<div class="col-sm-20">
							<!-- BEGIN: approval -->
							<label class="radio-inline"> <input type="radio" name="approval" value="{APPROVAL.key}" {APPROVAL.checked}> {APPROVAL.name} </label>
							<!-- END: approval -->
						</div>
					</div>
					<div align="center">
						<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
						<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a>
					</div>
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