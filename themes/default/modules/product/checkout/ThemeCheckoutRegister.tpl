<!-- BEGIN: main -->
<div class="row">
  <div class="col-sm-12">
    <fieldset id="account">
      <legend>Thông tin đăng ký</legend>
		<div class="form-group" style="display:  none ;">
			<label class="control-label">Nhóm khách hàng</label>
			<div class="radio">
			  <label><input type="radio" name="customer_group_id" value="1" checked="checked" />Mặc định</label>
			</div>
		</div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-lastname">Họ và tên đệm</label>
        <input type="text" name="lastname" value="Đặng Hải" placeholder="Họ và tên đệm" id="input-payment-lastname" class="form-control" />
      </div>
     <div class="form-group required">
        <label class="control-label" for="input-payment-firstname">Tên</label>
        <input type="text" name="firstname" value="Văn" placeholder="Tên" id="input-payment-firstname" class="form-control" />
      </div>
       <div class="form-group required">
        <label class="control-label" for="input-payment-username">Tên đăng nhập</label>
        <input type="text" name="username" value="danghaivan" placeholder="Tên đăng nhập" id="input-payment-username" class="form-control" />
      </div>
     <div class="form-group required">
        <label class="control-label" for="input-payment-email">Email</label>
        <input type="text" name="email" value="danghaivan@gmail.com" placeholder="Email" id="input-payment-email" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-telephone">Số điện thoại</label>
        <input type="text" name="telephone" value="0982912930" placeholder="Số điện thoại" id="input-payment-telephone" class="form-control" />
      </div>
	</fieldset>
    <fieldset>
      <div class="form-group required">
        <label class="control-label" for="input-payment-password">Mật khẩu</label>
        <input type="password" name="password" value="Tu1591990" placeholder="Password" id="input-payment-password" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-confirm">Xác nhận mật khẩu</label>
        <input type="password" name="confirm" value="Tu1591990" placeholder="Xác nhận mật khẩu" id="input-payment-confirm" class="form-control" />
      </div>
    </fieldset>
  </div>
  <div class="col-sm-12">
    <fieldset id="address">
      <legend>Địa chỉ của bạn</legend>
      <div class="form-group">
        <label class="control-label" for="input-payment-company">Công ty</label>
        <input type="text" name="company" value="Phong Linh" placeholder="Công ty" id="input-payment-company" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-address-1">Địa chỉ 1</label>
        <input type="text" name="address_1" value="Quảng Thanh - Thủy Nguyên - Hải Phòng" placeholder="Địa chỉ 1" id="input-payment-address-1" class="form-control" />
      </div>
      <div class="form-group">
        <label class="control-label" for="input-payment-address-2">Địa chỉ 2</label>
        <input type="text" name="address_2" value="Quảng Thanh - Thủy Nguyên - Hải Phòng" placeholder="Địa chỉ 2" id="input-payment-address-2" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-city">Thành phố</label>
        <input type="text" name="city" value="Hải Phòng" placeholder="Thành phố" id="input-payment-city" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-postcode">Mã bưu chính</label>
        <input type="text" name="postcode" value="8431" placeholder="Mã bưu chính" id="input-payment-postcode" class="form-control" />
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-country">Quốc gia</label>
        <select name="country_id" id="input-payment-country" class="form-control">
         <!--  <option value=""> --- Chọn quốc gia --- </option> -->
 
          <option value="230">Việt Nam</option>

        </select>
      </div>
      <div class="form-group required">
        <label class="control-label" for="input-payment-zone">Quận / Huyện</label>
        <select name="zone_id" id="input-payment-zone" class="form-control">
        </select>
      </div>
          </fieldset>

    </div>
</div>
<div class="checkbox">
	<label for="newsletter">
    <input type="checkbox" name="newsletter" value="1" id="newsletter" checked="checked" />
    Tôi muốn đăng ký nhận bản tin của Cửa hàng của bạn.
	</label>
</div>
<div class="buttons clearfix">
  <div class="pull-right">Tôi đã đọc và đồng ý với <a href="#" class="agree"><b>chính sách bảo mật</b></a> 
    &nbsp;
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <input type="button" value="Tiếp tục" id="button-register" data-loading-text="Đang tải..." class="btn btn-primary" />
  </div>
</div>
 
<script type="text/javascript"> 
// Sort the custom fields
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});

$('#address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
		$('#address .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#address .form-group').length) {
		$('#address .form-group:first').before(this);
	}
});

$('#collapse-payment-address input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=register&action=customfield&customer_group_id=' + this.value + '&nocache=' + new Date().getTime(),
		dataType: 'json',
		success: function(json) {
			$('#collapse-payment-address .custom-field').hide();
			$('#collapse-payment-address .custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#payment-custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#payment-custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-payment-address input[name=\'customer_group_id\']:checked').trigger('change');
</script> 
 
<script type="text/javascript">
$('#collapse-payment-address select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&method=register&action=zone&country_id=' + this.value + '&nocache=' + new Date().getTime(),
		dataType: 'json',
		beforeSend: function() {
			$('#collapse-payment-address select[name=\'country_id\']').prop('disabled', true);
		},
		complete: function() {
			$('#collapse-payment-address select[name=\'country_id\']').prop('disabled', false);
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#collapse-payment-address input[name=\'postcode\']').parent().addClass('required');
			} else {
				$('#collapse-payment-address input[name=\'postcode\']').parent().removeClass('required');
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

			$('#collapse-payment-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-payment-address select[name=\'country_id\']').trigger('change');
</script> 
<!-- END: main -->
