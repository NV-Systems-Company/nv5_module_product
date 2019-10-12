<!-- BEGIN: main -->
{AddMenu} 
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning} <i class="fa fa-times"></i>    
    </div>
    <!-- END: error_warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i></button> 
				<a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-voucher_theme" class="form-horizontal">
				<input type="hidden" name="language_id" value="{DATA.language_id}" />
				<input name="save" type="hidden" value="1" />

				<div class="tab-content">
					<div class="form-group required">
						<label class="col-sm-4 control-label">{LANGE.entry_name}</label>
						<div class="col-sm-20" style="padding-left: 0;">
							<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm">
							<!-- BEGIN: error_name -->
							<div class="text-danger">{error_name}</div>
							<!-- END: error_name -->
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-4 control-label" for="input-image"><span data-toggle="tooltip" title="{LANGE.entry_example}: vi.png">{LANGE.entry_image}</span>
								</label>
						<div class="col-sm-20">
							<input type="text" name="image" value="{DATA.image}" placeholder="{LANGE.entry_image}" id="input-image" class="form-control input-sm">
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