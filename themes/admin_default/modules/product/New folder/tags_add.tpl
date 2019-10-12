<!-- BEGIN: main -->
 
<div id="productcontent">
    <!-- BEGIN: tags_error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}
        <i class="fa fa-times"></i>
        <br>
    </div>
    <!-- END: tags_error_warning -->
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
                <form action="" method="post" enctype="multipart/form-data" id="form-tags" class="form-horizontal">
                    <input type="hidden" name="tags_id" value="{DATA.tags_id}" />
                    <input name="save" type="hidden" value="1" />

                    <div class="tab-content">
                        <div class="form-group required">
                            <label class="col-sm-2 control-label">{LANG.tags_alias}</label>
                            <div class="col-sm-10" style="padding-left: 0;">
                                <!-- BEGIN: looplang -->
                                <div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
                                    <input type="text" name="tags_description[{LANG_ID}][alias]" value="{VALUE.alias}" placeholder="{LANG.tags_alias}" class="form-control input-sm">
								</div>
								<!-- BEGIN: tags_error_alias --><div class="text-danger">{tags_error_alias}</div><!-- END: tags_error_alias -->
								
                                <!-- END: looplang -->

                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-2 control-label">{LANG.tags_keywords}</label>
                            <div class="col-sm-10" style="padding-left: 0;">
                                <!-- BEGIN: looplang1 -->
                                <div class="input-group"> <span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
                                    <input type="text" name="tags_description[{LANG_ID}][keywords]" value="{VALUE.keywords}" placeholder="{LANG.tags_keywords}" class="form-control input-sm">
                                </div>
                                <!-- END: looplang1 -->

                            </div>
                        </div>
						<!-- BEGIN: looplang2 -->
						<div class="form-group">
                            <label class="col-sm-2 control-label" for="input-description{LANG_ID}">{LANG.tags_description}</label>
                            <div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>
								<textarea name="tags_description[{LANG_ID}][description]" rows="5" placeholder="{LANG.tags_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
							</div>
                        </div>
						<!-- END: looplang2 -->
						<div class="form-group">
                                <label class="col-sm-2 control-label" for="image">{LANG.tags_image} </label>
                                <div class="col-sm-10">
									<input class="form-control input-sm w500 pull-left" type="text" name="image" id="image" value="{DATA.image}" placeholder="{LANG.tags_image} "/>
									&nbsp;<input type="button" value="Browse server" name="selectimg" class="btn btn-info btn-sm" />
                                </div>
                        </div>
                        <div align="center">
                            <input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
                            <a class="btn btn-primary btn-sm" href="#" title="{LANG.cancel}">{LANG.cancel}</a>
                        </div>
                    </div>
                </form>
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
    </script>
 
</div>

 
<!-- END: main -->