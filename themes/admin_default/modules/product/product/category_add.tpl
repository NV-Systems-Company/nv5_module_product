<!-- BEGIN: main -->
<div id="category-content">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning} <i class="fa fa-times"></i>
    </div>
    <!-- END: error_warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION} </h3>
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
						</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
				<input type="hidden" name="category_id" value="{DATA.category_id}" />
				<input type="hidden" name="parentid_old" value="{DATA.parent_id}" />
				<input name="save" type="hidden" value="1" />
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">{LANG.general}</a></li>
					<li><a href="#tab-data" data-toggle="tab">{LANG.data}</a></li>
				</ul>
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
										<input type="text" name="category_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.entry_name}" id="input-name{LANG_ID}" class="form-control input-sm" />
										<!-- BEGIN: error_name -->
										<div class="text-danger">{error_name}</div>
										<!-- END: error_name -->
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANGE.entry_alias}</label>
									<div class="col-sm-20">
										<input class="form-control input-sm w500 pull-left" name="category_description[{LANG_ID}][alias]" placeholder="{LANGE.entry_alias}" type="text" value="{VALUE.alias}" maxlength="255" id="input-alias{LANG_ID}" /> &nbsp;
										<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="shops_get_alias( {LANG_ID} );">&nbsp;</em>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description} </label>
									<div class="col-sm-20">
										<textarea name="category_description[{LANG_ID}][description]" placeholder="{LANGE.entry_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-meta-title{LANG_ID}">{LANGE.entry_meta_title}</label>
									<div class="col-sm-20">
										<input type="text" name="category_description[{LANG_ID}][meta_title]" value="{VALUE.meta_title}" placeholder="{LANGE.entry_meta_title}" id="input-meta-title{LANG_ID}" class="form-control input-sm" />
										<!-- BEGIN: error_meta_title -->
										<div class="text-danger">{error_meta_title}</div>
										<!-- END: error_meta_title -->
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-meta-description{LANG_ID}">{LANGE.entry_meta_description}</label>
									<div class="col-sm-20">
										<textarea name="category_description[{LANG_ID}][meta_description]" rows="5" placeholder="{LANGE.entry_meta_description}" id="input-meta-description{LANG_ID}" class="form-control input-sm">{VALUE.meta_description}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-meta-keyword{LANG_ID}">{LANGE.entry_meta_keyword}</label>
									<div class="col-sm-20">
										<textarea name="category_description[{LANG_ID}][meta_keyword]" rows="5" placeholder="{LANGE.entry_meta_keyword}" id="input-meta-keyword{LANG_ID}" class="form-control input-sm">{VALUE.meta_keyword}</textarea>
									</div>
								</div>
							</div>
							<!-- BEGIN: getalias -->
							<script type="text/javascript">
								$("#input-name{LANG_ID}").change(function() {
									shops_get_alias({
										LANG_ID
									});
								});
							</script>
							<!-- END: getalias -->
							<!-- END: looplang -->
						</div>
					</div>
					<div class="tab-pane fade" id="tab-data">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-category"><span data-toggle="tooltip" title="{LANG.autocomplete}">{LANGE.entry_sub_sl}</span></label>
							<div class="col-sm-20">
								<input type="text" name="category" value="{DATA.perent_name}" placeholder="{LANGE.entry_sub_sl}" id="input-category" class="form-control input-sm" autocomplete="off" />
								<input type="hidden" name="parent_id" value="{DATA.parent_id}" id="input-parent-id" class="form-control input-sm" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-filter"><span data-toggle="tooltip" title="{LANG.autocomplete}">{LANGE.entry_filters}</span>
									</label>
							<div class="col-sm-20">
								<input type="text" name="filter" value="" placeholder="{LANGE.entry_filters}" id="input-filter" class="form-control input-sm" />
								<div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
									<!-- BEGIN:filter -->
									<div id="category-filter{FILTER.filter_id}"><i class="fa fa-minus-circle"></i> {FILTER.name}
										<input type="hidden" name="category_filter[]" value="{FILTER.filter_id}"></div>
									<!-- END:filter -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{LANGE.entry_stores}</label>
							<div class="col-sm-20">
								<div class="well well-sm" style="height: 150px; overflow: auto;">
									<!-- BEGIN: store -->
									<div class="checkbox">
										<label><input type="checkbox" name="category_store[]" value="{STORE.key}" {STORE.checked} /> {STORE.name} </label>
									</div>
									<!-- END: store -->
								</div>
								<!-- BEGIN: error_store -->
								<div class="text-danger">{error_store}</div>
								<!-- END: error_store -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="image">{LANGE.entry_image} 
									</label>
							<div class="col-sm-20">
								<input class="form-control input-sm w500 pull-left" type="text" name="image" id="image" value="{DATA.image}" placeholder="{LANGE.entry_image}" /> &nbsp;
								<input type="button" value="{LANGE.entry_select_image}" name="selectimg" class="btn btn-info btn-sm" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-columns-in-menu"><span data-toggle="tooltip" title="{LANGE.entry_columns_in_menu_help}">{LANGE.entry_columns_in_menu}</span></label>
							<div class="col-sm-20">
								<input class="form-control input-sm numberonly" type="text" name="columns_in_menu" id="input-columns-in-menu" value="{DATA.columns_in_menu}" maxlength="1" placeholder="{LANGE.entry_columns_in_menu}" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-columns-in-body">{LANGE.entry_columns_in_body} 
									</label>
							<div class="col-sm-20">
								<input class="form-control input-sm numberonly" type="text" name="columns_in_body" id="input-columns-in-body" value="{DATA.columns_in_body}" maxlength="1" placeholder="{LANGE.entry_columns_in_body}" /> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-parent">{LANGE.entry_layout}</label>
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
							<label class="col-sm-4 control-label" for="input-inhome">{LANGE.entry_inhome}</label>
							<div class="col-sm-20">
								<select name="inhome" id="input-inhome" class="form-control input-sm">
									<!-- BEGIN: inhome -->
									<option value="{INHOME.key}" {INHOME.selected}>{INHOME.name}</option>
									<!-- END: inhome -->
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
	
if ($('#language li').length == 1) {
	$('#language').hide()
}

$(document).ready(function() {
	$('#language a:first').tab('show');
});

$("input[name=selectimg]").click(function() {
	var area = "image";
	var path = "{UPLOAD_PATH}";
	var currentpath = "{UPLOAD_CURRENT}";
	var type = "image";
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});

$('input[name="category"]').autofill({
	'source': function(request, response) {
		$.ajax({
			url: '{JSON_CATEGORY}&filter_name=' + encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name="category"]').val(item['label']);
		$('input[name="parent_id"]').val(item['value']);
	}
});
$('input[name="filter"]').autofill({
	'source': function(request, response) {
		$.ajax({
			url: '{JSON_FILTER}&filter_name=' + encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name="filter"]').val('');
		$('#category-filter' + item['value']).remove();
		$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
	}
});
$('#category-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
</script>
<!-- END: main -->