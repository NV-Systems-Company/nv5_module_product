<!-- BEGIN: main -->
<form class="form-horizontal">
    <div class="radio">
    <label>
      <input type="radio" name="shipping_address" value="existing" checked="checked" />Tôi muốn sử dụng địa chỉ đã có sẵn</label>
  </div>
  <div id="shipping-existing">
    <select name="address_id" class="form-control">
		<!-- BEGIN: address -->
		<option value="{ADDRESS.address_id}" {ADDRESS.selected}>{ADDRESS.first_name} {ADDRESS.last_name}, {ADDRESS.address_1}, {ADDRESS.city}, {ADDRESS.zone}, {ADDRESS.country}</option>
		<!-- END: address -->
	</select>
  </div>
  <div class="radio">
    <label>
      <input type="radio" name="shipping_address" value="new" />Tôi muốn sử dụng một địa chỉ mới</label>
  </div>
    <br />
  <div id="shipping-new" style="display: none;">
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-firstname">Họ và tên đệm</label>
      <div class="col-sm-20">
        <input type="text" name="firstname" value="Triệu Thị" placeholder="Họ và tên đệm" id="input-shipping-firstname" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-lastname">Tên</label>
      <div class="col-sm-20">
        <input type="text" name="lastname" value="Yến" placeholder="Tên" id="input-shipping-lastname" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label" for="input-shipping-company">Công ty</label>
      <div class="col-sm-20">
        <input type="text" name="company" value="Gia Nguyễn" placeholder="Công ty" id="input-shipping-company" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-address-1">Địa chỉ 1</label>
      <div class="col-sm-20">
        <input type="text" name="address_1" value="Mai Dịch - Cầu Giấy - Hà Nội" placeholder="Địa chỉ 1" id="input-shipping-address-1" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label" for="input-shipping-address-2">Địa chỉ 2</label>
      <div class="col-sm-20">
        <input type="text" name="address_2" value="Mai Dịch - Cầu Giấy - Hà Nội" placeholder="Địa chỉ 2" id="input-shipping-address-2" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-city">Thành phố</label>
      <div class="col-sm-20">
        <input type="text" name="city" value="Hà Nội" placeholder="City" id="input-shipping-city" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-postcode">Mã bưu chính</label>
      <div class="col-sm-20">
        <input type="text" name="postcode" value="8431" placeholder="Mã bưu chính" id="input-shipping-postcode" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-country">Quốc gia</label>
      <div class="col-sm-20">
        <select name="country_id" id="input-shipping-country" class="form-control">
          <option value=""> --- Chọn quốc gia --- </option>
		  <option value="230" selected="selected">Việt Nam</option>		
		</select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-zone">Quận/Huyện</label>
      <div class="col-sm-20">
        <select name="zone_id" id="input-shipping-zone" class="form-control">
        </select>
      </div>
    </div>
     </div>
  <div class="buttons clearfix">
    <div class="pull-right">
      <input type="button" value="Tiếp tục" id="button-shipping-address" data-loading-text="Đang tải..." class="btn btn-primary" />
    </div>
  </div>
</form>
<script type="text/javascript">
$('input[name=\'shipping_address\']').on('change', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
	}
});
</script>
<script type="text/javascript">
$('#collapse-shipping-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-shipping-address .form-group').length-2) {
		$('#collapse-shipping-address .form-group').eq(parseInt($(this).attr('data-sort'))+2).before(this);
	}

	if ($(this).attr('data-sort') > $('#collapse-shipping-address .form-group').length-2) {
		$('#collapse-shipping-address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#collapse-shipping-address .form-group').length-2) {
		$('#collapse-shipping-address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#collapse-shipping-address .form-group').length-2) {
		$('#collapse-shipping-address .form-group:first').before(this);
	}
});
</script>
<script type="text/javascript">
$('#collapse-shipping-address select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=country&country_id=' + this.value + '&nocache=' + new Date().getTime(),		
		dataType: 'json',
		beforeSend: function() {
			$('#collapse-shipping-address select[name=\'country_id\']').prop('disabled', true);
		},
		complete: function() {
			$('#collapse-shipping-address select[name=\'country_id\']').prop('disabled', false);
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""> --- Chọn Quận/Huyện --- </option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '3774') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"> --- None --- </option>';
			}

			$('#collapse-shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-shipping-address select[name=\'country_id\']').trigger('change');
</script>
<!-- END: main -->
