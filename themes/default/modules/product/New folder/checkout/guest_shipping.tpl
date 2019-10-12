<!-- BEGIN: main -->
<form class="form-horizontal">
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-first-name">{LANGE.entry_first_name}</label>
    <div class="col-sm-20">
      <input type="text" name="first_name" value="{DATA.first_name}" placeholder="{LANGE.entry_first_name}" id="input-shipping-first-name" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-last-name">{LANGE.entry_last_name}</label>
    <div class="col-sm-20">
      <input type="text" name="last_name" value="{DATA.last_name}" placeholder="{LANGE.entry_last_name}" id="input-shipping-last-name" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="input-shipping-company">{LANGE.entry_company}</label>
    <div class="col-sm-20">
      <input type="text" name="company" value="{DATA.company}" placeholder="{LANGE.entry_company}" id="input-shipping-company" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-address-1">{LANGE.entry_address_1}</label>
    <div class="col-sm-20">
      <input type="text" name="address_1" value="{DATA.address_1}" placeholder="{LANGE.entry_address_1}" id="input-shipping-address-1" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="input-shipping-address-2">{LANGE.entry_address_2}</label>
    <div class="col-sm-20">
      <input type="text" name="address_2" value="{DATA.address_2}" placeholder="{LANGE.entry_address_2}" id="input-shipping-address-2" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-city">{LANGE.entry_city}</label>
    <div class="col-sm-20">
      <input type="text" name="city" value="{DATA.city}" placeholder="{LANGE.entry_city}" id="input-shipping-city" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-postcode">{LANGE.entry_postcode}</label>
    <div class="col-sm-20">
      <input type="text" name="postcode" value="{DATA.postcode}" placeholder="{LANGE.entry_postcode}" id="input-shipping-postcode" class="form-control input-sm" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-country">{LANGE.entry_country}</label>
    <div class="col-sm-20">
      <select name="country_id" id="input-shipping-country" class="form-control input-sm">
		<option value=""> --- {LANGE.entry_select} --- </option>
		<!-- BEGIN: country -->
		<option value="{COUNTRY.key}" {COUNTRY.selected}> {COUNTRY.name} </option>
		<!-- END: country -->
      </select>
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-4 control-label" for="input-shipping-zone">{LANGE.entry_zone}</label>
    <div class="col-sm-20">
      <select name="zone_id" id="input-shipping-zone" class="form-control input-sm">
      </select>
    </div>
  </div>
 
  <div class="buttons">
    <div class="pull-right">
      <input type="button" value="{LANG.button_continue}" id="button-guest-shipping" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
    </div>
  </div>
</form>
<script type="text/javascript"><!--
// Sort the custom fields
$('#collapse-shipping-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group:first').before(this);
	}
});
//--></script>
 
<script type="text/javascript"><!--
$('#collapse-shipping-address select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&register&action=zone&country_id=' + this.value + '&nocache=' + new Date().getTime(),
		dataType: 'json',
		beforeSend: function() {
			$('#collapse-shipping-address select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();

			if (json['postcode_required'] == '1') {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value="">{LANGE.text_select}</option>';

			if (json['zone']) {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '{DATA.zone_id}') {
						html += ' selected="selected"';
          			}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected">{LANG.text_none}</option>';
			}

			$('#collapse-shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-shipping-address select[name=\'country_id\']').trigger('change');
//--></script>
<!-- BEGIN: main -->