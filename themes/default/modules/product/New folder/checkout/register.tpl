<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12">
        <fieldset id="account">
            <legend>{LANGE.text_your_details}</legend>
            <!-- <div class="form-group" style="display: none;">
                <label class="control-label">{lANGE.entry_customer_group}</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="customer_group_id" value="1" checked="checked"> Default
                    </label>
                </div>
            </div> -->
            <div class="form-group required">
                <label class="control-label" for="input-payment-first-name">{LANGE.entry_first_name}</label>
                <input type="text" name="first_name" value="" placeholder="{LANGE.entry_first_name}" id="input-payment-first-name" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-last-name">{LANGE.entry_last_name}</label>
                <input type="text" name="last_name" value="" placeholder="{LANGE.entry_last_name}" id="input-payment-last-name" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-email">{LANGE.entry_email_address}</label>
                <input type="text" name="email" value="" placeholder="{LANGE.entry_email_address}" id="input-payment-email" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-telephone">{LANGE.entry_telephone}</label>
                <input type="text" name="telephone" value="" placeholder="{LANGE.entry_telephone}" id="input-payment-telephone" class="form-control input-sm">
            </div>
            <div class="form-group">
                <label class="control-label" for="input-payment-fax">{LANGE.entry_fax}</label>
                <input type="text" name="fax" value="" placeholder="{LANGE.entry_fax}" id="input-payment-fax" class="form-control input-sm">
            </div>
        </fieldset>
        <fieldset>
            <legend>{LANGE.entry_password}</legend>
            <div class="form-group required">
                <label class="control-label" for="input-payment-password">{LANGE.entry_password}</label>
                <input type="password" name="password" value="" placeholder="{LANGE.entry_password}" id="input-payment-password" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-confirm">{LANGE.entry_confirm}</label>
                <input type="password" name="confirm" value="" placeholder="{LANGE.entry_confirm}" id="input-payment-confirm" class="form-control input-sm">
            </div>
        </fieldset>
    </div>
    <div class="col-sm-12">
        <fieldset id="address" class="required">
            <legend>{LANGE.entry_address}</legend>
            <div class="form-group">
                <label class="control-label" for="input-payment-company">{LANGE.entry_company}</label>
                <input type="text" name="company" value="" placeholder="{LANGE.entry_company}" id="input-payment-company" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-address-1">{LANGE.entry_address_1}</label>
                <input type="text" name="address_1" value="" placeholder="{LANGE.entry_address_1}" id="input-payment-address-1" class="form-control input-sm">
            </div>
            <div class="form-group">
                <label class="control-label" for="input-payment-address-2">{LANGE.entry_address_2}</label>
                <input type="text" name="address_2" value="" placeholder="{LANGE.entry_address_2}" id="input-payment-address-2" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-city">{LANGE.entry_city}</label>
                <input type="text" name="city" value="" placeholder="{LANGE.entry_city}" id="input-payment-city" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-postcode">{LANGE.entry_postcode}</label>
                <input type="text" name="postcode" value="" placeholder="{LANGE.entry_postcode}" id="input-payment-postcode" class="form-control input-sm">
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-country">{LANGE.entry_country}</label>
                <select name="country_id" id="input-payment-country" class="form-control input-sm">
                    <option value=""> --- {LANGE.entry_select} --- </option>
                    <!-- BEGIN: country -->
					<option value="{COUNTRY.key}" {COUNTRY.selected}> {COUNTRY.name} </option>
                    <!-- END: country -->
                </select>
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-payment-zone">{LANGE.entry_zone}</label>
                <select name="zone_id" id="input-payment-zone" class="form-control input-sm">
                    <option value=""> --- {LANGE.entry_select} --- </option>
                </select>
            </div>
        </fieldset>
    </div>
</div>
<div class="checkbox">
    <label for="newsletter">
        <input type="checkbox" name="newsletter" value="1" id="newsletter"> {LANGE.entry_newsletter}.</label>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" name="shipping_address" value="1" checked="checked"> {LANGE.entry_shipping}.</label>
</div>
<div class="buttons clearfix">
    <div class="pull-right">{AGREE}&nbsp;
        <input type="checkbox" name="agree" value="1">
        <input type="button" value="{LANG.button_continue}" id="button-register" data-loading-text="{LANG.button_loading}" class="btn btn-primary">
    </div>
</div> 
 
<script type="text/javascript"><!--
$('#collapse-payment-address select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkout&register&action=zone&country_id=' + this.value + '&nocache=' + new Date().getTime(),
		dataType: 'json',
		beforeSend: function() {
			$('#collapse-payment-address select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.fa-spin').remove();
			
			if (json['postcode_required'] == '1') {
				$('#collapse-payment-address input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#collapse-payment-address input[name=\'postcode\']').parent().parent().removeClass('required');
			}
			
			html = '<option value=""> --- {LANGE.entry_select} --- </option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '') {
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
//--></script> 
<!-- END: main -->