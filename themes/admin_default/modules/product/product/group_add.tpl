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
                <form action="" method="post"  enctype="multipart/form-data" id="form-category" class="form-horizontal">
					<input type="hidden" name ="group_id" value="{DATA.group_id}" />
					<input type="hidden" name ="category_id" value="{DATA.category_id}" />
					<input type="hidden" name ="parentid_old" value="{DATA.parent_id}" />
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
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANG.group_name}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="group_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANG.group_name}" id="input-name{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANG.group_alias}</label>
                                        <div class="col-sm-20">
                                            	<input class="form-control input-sm w500 pull-left" name="group_description[{LANG_ID}][alias]" placeholder="{LANG.group_alias}" type="text" value="{VALUE.alias}" maxlength="255" id="input-alias{LANG_ID}"/>
												&nbsp;<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="shops_get_alias( {LANG_ID} );">&nbsp;</em>
						
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANG.group_description} </label>
                                        <div class="col-sm-20">
                                            <textarea name="group_description[{LANG_ID}][description]" placeholder="{LANG.group_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
											<!-- <span class="text-middle"> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </span> -->
                                        
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-meta-title{LANG_ID}">{LANG.group_meta_tag_title}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="group_description[{LANG_ID}][meta_title]" value="{VALUE.meta_title}" placeholder="{LANG.group_meta_tag_title}" id="input-meta-title{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_meta_title --><div class="text-danger">{error_meta_title}</div><!-- END: error_meta_title -->
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-description{LANG_ID}">{LANG.group_meta_description}</label>
                                        <div class="col-sm-20">
                                            <textarea name="group_description[{LANG_ID}][meta_description]" rows="5" placeholder="{LANG.group_meta_description}" id="input-meta-description{LANG_ID}" class="form-control input-sm">{VALUE.meta_description}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-keyword{LANG_ID}">{LANG.group_meta_keyword}</label>
                                        <div class="col-sm-20">
                                            <textarea name="group_description[{LANG_ID}][meta_keyword]" rows="5" placeholder="{LANG.group_meta_keyword}" id="input-meta-keyword{LANG_ID}" class="form-control input-sm">{VALUE.meta_keyword}</textarea>
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
                                <label class="col-sm-4 control-label" for="input-parent">{LANG.group_sub}</label>
                                <div class="col-sm-20">
                                    <select class="form-control input-sm" name="parent_id" onchange="nv_getcatalog(this)">
										<!-- BEGIN: parent_loop -->
										<option value="{pgroup_i}" {pselect}>{ptitle_i}</option>
										<!-- END: parent_loop -->
									</select>
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-parent">{LANG.group_of}</label>
                                <div class="col-sm-20" id="vcategory">
 
                                </div>
                            </div>
							
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-keyword">{LANG.group_image} 
                                </label>
                                <div class="col-sm-20">
                                    <input class="form-control input-sm w500 pull-left" type="text" placeholder="{LANG.group_image}" name="image" id="image" value="{DATA.image}"/>
									&nbsp;<input type="button" value="Browse server" name="selectimg" class="btn btn-info btn-sm" />
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-parent">{LANG.group_layout}</label>
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
                                <label class="col-sm-4 control-label" for="input-inhome">{LANG.group_inhome}</label>
                                <div class="col-sm-20">
                                    <select name="inhome" id="input-inhome" class="form-control input-sm">
                                         <!-- BEGIN: inhome -->
                                        <option value="{inhome_id}" {inhome_selected}>{inhome_title}</option>
										 <!-- END: inhome -->
                                    </select>
                                </div>
                            </div>
                        	 
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-inorder">{LANG.group_in_order}</label>
                                <div class="col-sm-20">
                                    <select name="in_order" id="input-inorder" class="form-control input-sm">
                                         <!-- BEGIN: in_order -->
                                        <option value="{in_order_id}" {in_order_selected}>{in_order_title}</option>
										 <!-- END: in_order -->
                                    </select>
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
		$("input[name=selectimg]").click(function() {
			var area = "image";
			var path = "{UPLOAD_PATH}";
			var currentpath = "{UPLOAD_CURRENT}";
			var type = "image";
			nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
			return false;
		});
	</script>
    <script type="text/javascript">
		$(document).ready(function() {
			$('#language a:first').tab('show');
		});
 
    </script>
	<script type="text/javascript">
	$('#vcategory').load('{URL}');
	function nv_getcatalog(obj) {
		var pid = $(obj).val();
		var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=group&action=getcatalog&pid=' + pid;
		$('#vcategory').load(url);
	}
 
    </script>
	
	
</div>

 
<!-- END: main -->