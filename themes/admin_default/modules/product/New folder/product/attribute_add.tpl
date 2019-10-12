<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}
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
                <form action="" method="post" enctype="multipart/form-data" id="form-attribute-group" class="form-horizontal">
                    <input type="hidden" name="attribute_id" value="{DATA.attribute_id}" />
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-4 control-label">{LANGE.entry_name}</label>
                            <div class="col-sm-20">
                                <!-- BEGIN: looplang -->
                                <div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
                                    <input type="text" name="attribute_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" class="form-control input-sm">
								</div>         
                                <!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
								<!-- END: looplang -->
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-attribute-group"><font><font>{LANGE.entry_attribute_group}</font></font></label>
							<div class="col-sm-20">
								<select name="attribute_group_id" id="input-attribute-group" class="form-control input-sm">
									<!-- BEGIN: attribute_group -->
									<option value="{ATBG.key}" {ATBG.selected} >{ATBG.name}</option>
									<!-- END: attribute_group -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{LANGE.entry_sort_order}</label>
							<div class="col-sm-20">
								<input type="text" name="sort_order" value="{DATA.sort_order}" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm">
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
</div>
  

<script type="text/javascript">
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>
<!-- END: main -->