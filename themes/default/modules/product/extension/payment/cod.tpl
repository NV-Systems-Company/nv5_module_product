<!-- BEGIN: main -->
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="{LANG.button_confirm}" id="button-confirm" data-loading-text="Đang tải..." class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=confirm&payment=cod&nocache=' + new Date().getTime(),					
		dataType: 'json',
		beforeSend: function() {
			var loading = $('#button-confirm').attr('data-loading-text');
			var text = $('#button-confirm').attr('value');
			$('#button-confirm').attr('value', loading).attr('data-loading-text', text);
		},
		complete: function() {
			var loading = $('#button-confirm').attr('data-loading-text');
			var text = $('#button-confirm').attr('value');
			$('#button-confirm').attr('value', loading).attr('data-loading-text', text);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];	
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
<!-- END: main -->