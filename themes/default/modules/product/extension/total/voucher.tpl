<!-- BEGIN: main -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Sử dụng Voucher <i class="fa fa-caret-down"></i></a></h4>
	</div>
	<div id="collapse-voucher" class="panel-collapse collapse">
		<div class="panel-body">
			<label class="col-sm-5 control-label" for="input-voucher">Nhập mã voucher</label>
			<div class="input-group">
				<input type="text" name="voucher" value="{VOUCHER}" placeholder="Nhập mã voucher tại đây" id="input-voucher" class="form-control">
				<span class="input-group-btn">
					<input type="submit" value="Sử dụng voucher" id="button-voucher" data-loading-text="Đang tải..." class="btn btn-primary">
				</span> 
			</div>
			<script type="text/javascript">
			   
				$('#button-voucher').on('click', function() {
					$.ajax({
						url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cart&action=voucher&nocache=' + new Date().getTime(),
						type: 'post',
						data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
						dataType: 'json',
						beforeSend: function() {
							var loading = $('#button-voucher').attr('data-loading-text');
							var text = $('#button-voucher').attr('value');
							$('#button-voucher').attr('value', loading).attr('data-loading-text', text);
						},
						complete: function() {
							var loading = $('#button-voucher').attr('data-loading-text');
							var text = $('#button-voucher').attr('value');
							$('#button-voucher').attr('value', loading).attr('data-loading-text', text);
						},
						success: function(json) {
							$('.alert').remove();
							if (json['error']) {
								$('#ProductContent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
								$('html, body').animate({
									scrollTop: 0
								}, 'slow');
							}
							if (json['redirect']) {
								location.href = json['redirect'];
							}
						}
					});
				});
				 
			</script>
		</div>
	</div>
</div>
<!-- END: main -->
