<!-- BEGIN: main -->
 {AddMenu} 
<div id="productcontent">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="well">
				<div class="row">
					<form action="{NV_BASE_ADMINURL}index.php" method="get">
					<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
					<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
					<div class="col-sm-8">
						<div class="form-group">
							<label class="control-label" for="input-name">{LANGE.column_name}</label>
							<input type="text" name="name" value="{DATA.name}" placeholder="{LANGE.column_name}" id="input-name" class="form-control input-sm" autocomplete="off">
						</div>
						<div class="form-group">
							<label class="control-label" for="input-category">{LANGE.column_category}</label>
							<select class="form-control input-sm" name="category_id">
								<option value="0">{LANGE.column_category_all}</option>
								<!-- BEGIN: category -->
								<option value="{CAT.category_id}"{CAT.selected}>{CAT.name}</option>
								<!-- END: category -->
								
							</select>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<label class="control-label" for="input-price">{LANGE.column_price}</label>
							<input type="text" name="price" value="{DATA.price}" placeholder="{LANGE.column_price}" id="input-price" class="form-control input-sm">
						</div>
						<div class="form-group">
							<label class="control-label" for="input-quantity">{LANGE.column_quantity}</label>
							<input type="text" name="quantity" value="{DATA.quantity}" placeholder="{LANGE.column_quantity}" id="input-quantity" class="form-control input-sm">
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<label class="control-label" for="input-status">{LANGE.column_status}</label>
							<select name="status" id="input-status" class="form-control input-sm">
								<option value="*"> ----- </option>
								<!-- BEGIN: status -->
								<option value="{STATUS.status}"{STATUS.selected}>{STATUS.name}</option>
								<!-- END: status -->
							</select>
						</div>
						<input type="hidden" name ="checkss" value="{TOKEN}" />
						<button type="submit" id="button-filter" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> {LANG.search}</button>
					</div>
					</form>
				</div>
			</div>

			<form class="form-inline" name="items_list">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="col-md-0 text-center">
									<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
								</th>
								<th class="col-md-2 text-center">{LANGE.column_image}</th>
								<th class="col-md-8 text-center"><a href="{URL_NAME}">{LANGE.column_name}</a></th>
								<th class="col-md-2 text-center"><a href="{URL_ADDTIME}">{LANGE.column_public}</a></th>
								<th	class="col-md-2 text-center"><a href="{URL_STATUS}">{LANGE.column_status}</a></th>
								<th class="col-md-2 text-center"><a href="{URL_PRICE}">{LANGE.column_price}</a></th>
								<th class="col-md-2 text-center"><a href="{URL_QUANTITY}">{LANGE.column_quantity}</a></th>
								<th class="col-md-4 text-center">{LANGE.column_action}</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: loop -->
							<tr id="group_{LOOP.product_id}">
								<td class="text-center middle">
									<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{LOOP.product_id}" name="idcheck[]">
								</td>
								<td><a href="{LOOP.imghome}" rel="shadowbox[random]"/ title="{LOOP.name}"><img src="{LOOP.thumb}" alt="{LOOP.name}" width="40"/></a></td>
								<td class="top">
								<p>
									<a target="_blank" href="{LOOP.link}">{LOOP.name}</a>
								</p>
								<div class="product-info">
									{LANGE.column_update}: <span class="other">{LOOP.edittime}</span> |
									{LANGE.column_admin}: <span class="other">{LOOP.admin_id}</span>
								</div></td>
								<td class="text-center middle">{LOOP.addtime}</td>
								<td class="text-center middle">{LOOP.status}</td>
								<td class="text-right middle">
								<!-- BEGIN: special -->
									<span style="text-decoration: line-through;">{LOOP.price}</span>
									<br><div class="text-danger">{LOOP.special}</div>
								<!-- END: special -->
								<!-- BEGIN: price -->
									{LOOP.price}
								<!-- END: price -->
			 
								</td>
								<td class="text-center middle">
									<span class="label label-{LOOP.quantity_label}">{LOOP.quantity}</span>
			 
								</td>
								<td class="text-center">
									<a href="{LOOP.edit}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
									 
									<a href="javascript:void(0);" onclick="delete_product('{LOOP.product_id}', '{LOOP.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
			 
								</td>
							</tr>
							<!-- END: loop -->
						</tbody>
						<tfoot>
							<tr align="left">
								<td colspan="8">
								<input type="button" class="btn btn-primary btn-sm" id="button-copy"  value="{LANG.copy}">
								<!-- BEGIN: generate_page -->
								<div  align="center">
									<div style="clear:both"></div>
									{GENERATE_PAGE}
								</div>
								<!-- END: generate_page -->
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/content.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/autofill.js"></script>
  


<script type="text/javascript">
function delete_product(product_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'product_id=' + product_id + '&token=' + token,
			beforeSend: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					 $.each(json['id'], function(i, id) {
						$('#group_' + id ).remove();
					});
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('#button-delete').on('click', function() {
	if(confirm('{LANG.confirm}')) 
	{
		var listid = [];
		$('input[name="selected[]"]:checked').each(function() {
			listid.push($(this).val());
		});
		if (listid.length < 1) {
			alert("{LANG.please_select_one}");
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items&action=delete&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'listid=' + listid + '&token={TOKEN}',
			beforeSend: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				$('.alert').remove();
				
				
				
				if (json['error']) {
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					 $.each(json['id'], function(i, id) {
						$('#group_' + id ).remove();
					});
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}	
});
$('input[name=\'name\']').autofill({
	'source': function(request, response) {
		$.ajax({
			url: '{URL_SEARCH}&name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'name\']').val(item['label']);
	}
});
$('#button-copy').on('click', function() {
 
		var listid = [];
		$("input[name=\"idcheck[]\"]:checked").each(function() {
			listid.push($(this).val());
		});
		if (listid.length < 1) {
			alert("{LANG.please_select_one}");
			return false;
		}
	 
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items&action=copy&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'listid=' + listid + '&token={TOKEN}',
			beforeSend: function() {
 				$('#button-copy').prop('disabled', true);
			},	
			complete: function() {
 				$('#button-copy').prop('disabled', false);
			},
			success: function(json) {
				 $('.alert').remove();
				 if( json['success'] ) alert(json['success']);
				 location.reload();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
 	
});
</script>

<!-- END: main -->