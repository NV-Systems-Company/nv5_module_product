<!-- BEGIN: main -->
<div id="productcontent">
    <div class="container-fluid">
		<!-- BEGIN: error_warning -->
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> {error_warning}
			<i class="fa fa-times"></i>
			<br>
		</div>
		<!-- END: error_warning -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-brand" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-brand" class="form-horizontal">
					<input type="hidden" name ="brand_id" value="{DATA.brand_id}" />
 					<input name="save" type="hidden" value="1" />
 
                    <div class="tab-content">
                        <div class="tab-pane active in" id="tab-general">
                            <ul class="nav nav-tabs" id="language" {DISPLAYLANG}>
                                <!-- BEGIN: looplangtab -->
                                <li>
                                    <a href="#language{LANG_KEY}" data-toggle="tab"><img src="{LANG_TITLE.image}" title="{LANG_TITLE.name}" /> {LANG_TITLE.name}</a>
                                </li>
                                <!-- END: looplangtab -->
                            </ul>
                            <div class="tab-content">
                                <!-- BEGIN: looplang -->
                                <div class="tab-pane" id="language{LANG_ID}">
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANGE.entry_name}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="brand_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" id="input-name{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description} </label>
                                        <div class="col-sm-20">
                                            {VALUE.description}
                                        
                                        </div>
                                    </div>
                                </div>
                                <!-- END: looplang -->
                            </div>
							 <div class="form-group">
                                <label class="col-sm-4 control-label" for="image">{LANGE.entry_image} </label>
                                <div class="col-sm-20">
									<input class="form-control input-sm w500 pull-left" type="text" name="image" id="image" value="{DATA.image}" placeholder="{LANGE.entry_image}"/>
									&nbsp;<input type="button" value="{LANG.select_image}" name="selectimg" class="btn btn-info btn-sm" />
                                </div>
                            </div>
                        </div>

						<div align="center">
							<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
							<a class="btn btn-primary btn-sm" href="{CANCEL}" title="{LANG.back}">{LANG.cancel}</a> 
						</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
  

<script type="text/javascript">
$("input[name=selectimg]").click(function() {
	var area = "image";
	var path = "{UPLOAD_PATH}";
	var currentpath = "{UPLOAD_CURRENT}";
	var type = "image";
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>
<!-- END: main -->