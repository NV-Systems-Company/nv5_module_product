<!-- BEGIN: main -->
{AddMenu}
<script type="text/javascript">
	function shops_get_alias( key ) {
		
		 var title = strip_tags(document.getElementById('input-title'+key+'').value);
 
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
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post"  enctype="multipart/form-data" id="form-information" class="form-horizontal">
					<input type="hidden" name ="information_id" value="{DATA.information_id}" />
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
                                        <label class="col-sm-4 control-label" for="input-title{LANG_ID}">{LANGE.entry_title}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="information_description[{LANG_ID}][title]" value="{VALUE.title}" placeholder="{LANGE.entry_title}" id="input-title{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_title --><div class="text-danger">{error_title}</div><!-- END: error_title -->
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANGE.entry_alias}</label>
                                        <div class="col-sm-20">
                                            	<input class="form-control input-sm w300 pull-left" name="information_description[{LANG_ID}][alias]" placeholder="{LANGE.entry_alias}" type="text" value="{VALUE.alias}" maxlength="255" id="input-alias{LANG_ID}"/>
												&nbsp;<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="shops_get_alias( {LANG_ID} );">&nbsp;</em>
						
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description} </label>
                                        <div class="col-sm-20">
                                            {VALUE.descript}
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-meta-title{LANG_ID}">{LANGE.entry_meta_title}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="information_description[{LANG_ID}][meta_title]" value="{VALUE.meta_title}" placeholder="{LANGE.entry_meta_title}" id="input-meta-title{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_meta_title --><div class="text-danger">{error_meta_title}</div><!-- END: error_meta_title -->
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-description{LANG_ID}">{LANGE.entry_meta_description}</label>
                                        <div class="col-sm-20">
                                            <textarea name="information_description[{LANG_ID}][meta_description]" rows="5" placeholder="{LANGE.entry_meta_description}" id="input-meta-description{LANG_ID}" class="form-control input-sm">{VALUE.meta_description}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-keyword{LANG_ID}">{LANGE.entry_meta_keyword}</label>
                                        <div class="col-sm-20">
                                            <textarea name="information_description[{LANG_ID}][meta_keyword]" rows="5" placeholder="{LANGE.entry_meta_keyword}" id="input-meta-keyword{LANG_ID}" class="form-control input-sm">{VALUE.meta_keyword}</textarea>
                                        </div>
                                    </div>
                                </div>
								<!-- BEGIN: getalias -->
								<script type="text/javascript">
									$("#input-title{LANG_ID}").change(function() {
										 shops_get_alias({LANG_ID});
									});
								</script>
								<!-- END: getalias -->
                                <!-- END: looplang -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-data">
 
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-layout">{LANGE.entry_layout}</label>
                                <div class="col-sm-20">
                                    <select class="form-control input-sm" name="layout">
										<option value="">{LANG.default}</option>
										<!-- BEGIN: layout -->
										<option value="{LAYOUT.key}" {LAYOUT.selected}>{LAYOUT.key}</option>
										<!-- END: layout -->
									</select>
                                </div>
                            </div>
 
							 
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status">{LANGE.entry_status}</label>
                                <div class="col-sm-20">
                                    <select name="status" id="input-status" class="form-control input-sm">
                                         <!-- BEGIN: status -->
                                        <option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
										 <!-- END: status -->
                                    </select>
                                </div>
                            </div>
                        	 
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-sortorder">{LANGE.entry_sort_order}</label>
                                <div class="col-sm-20">
                                      <input type="text" name="sort_order" value="{DATA.sort_order}" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm" />
                                </div>
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
  

<script type="text/javascript">
 
if( $('#language li').length == 1 ) 
{
	$('#language').hide();
	//$('.tab-content').css('padding-top', '0px');
}
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script>
</div>
<!-- END: main -->