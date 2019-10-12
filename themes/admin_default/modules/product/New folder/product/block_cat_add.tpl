<!-- BEGIN: main -->
{AddMenu}
<script type="text/javascript">
function shops_get_alias( key ) {
	 var title = strip_tags(document.getElementById('input-name'+key+'').value);
	 if (title != '') {
	 	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title), function(res) {
	 		if (res != "") {
	 			document.getElementById('input-alias'+key+'').value = res;
	 		} else {
	 			document.getElementById('input-alias'+key+'').value = '';
	 		}
	 	});
	 }
	return false;
}
</script>
<div id="productcontent">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}
        <i class="fa fa-times"></i>
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
                <form action="" method="post"  enctype="multipart/form-data" id="form-block" class="form-horizontal">
					<input type="hidden" name ="block_id" value="{DATA.block_id}" />
 					<input name="save" type="hidden" value="1" />
					<ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">{LANG.general}</a>
                        </li>
                        <li><a href="#tab-data" data-toggle="tab">{LANG.data}</a>
                        </li>
 
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active in" id="tab-general">
                            <ul class="nav nav-tabs" id="language">
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
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANG.block_cat_name}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="block_cat_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANG.block_cat_name}" id="input-name{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANG.block_cat_alias}</label>
                                        <div class="col-sm-20">
                                            	<input class="form-control input-sm w500 pull-left" name="block_cat_description[{LANG_ID}][alias]" placeholder="{LANG.block_cat_alias}" type="text" value="{VALUE.alias}" maxlength="255" id="input-alias{LANG_ID}"/>
												&nbsp;<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="shops_get_alias( {LANG_ID} );">&nbsp;</em>
						
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANG.block_cat_description} </label>
                                        <div class="col-sm-20">
                                            <textarea name="block_cat_description[{LANG_ID}][description]" placeholder="{LANG.block_cat_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
                                         
                                        </div>
                                    </div>
 
                                </div>
								<!-- BEGIN: getalias -->
								<script type="text/javascript">
									$("#input-name{LANG_ID}").change(function() {
										 shops_get_alias({LANG_ID});
									});
								</script>
								<!-- END: getalias -->
                                <!-- END: looplang -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-data">
 
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-adddefault">{LANG.block_cat_adddefault}</label>
                                <div class="col-sm-20">
                                    <select name="adddefault" id="input-adddefault" class="form-control input-sm">
                                         <!-- BEGIN: adddefault -->
                                        <option value="{adddefault_id}" {adddefault_selected}>{adddefault_title}</option>
										 <!-- END: adddefault -->
                                    </select>
                                </div>
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
</div>
  

<script type="text/javascript">
if( $('#language li').length == 1 ) 
{
	$('#language').hide();
	$('.tab-content').css('padding-top', '0px');
}
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>
<!-- END: main -->