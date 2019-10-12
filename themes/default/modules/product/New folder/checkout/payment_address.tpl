<!-- BEGIN: main -->
<form class="form-horizontal">
    <div class="radio">
        <label>
            <input type="radio" name="payment_address" value="existing" checked="checked" /> {LANGE.text_address_existing}</label>
    </div>
    <div id="payment-existing">
        <select name="address_id" class="form-control input-sm">
			<!-- BEGIN: addresses -->
				<option value="{ADDRESS.address_id}" {ADDRESS.selected} > {ADDRESS.last_name} {ADDRESS.first_name}, {ADDRESS.address_1}, {ADDRESS.city}, {ADDRESS.zone}, {ADDRESS.country}   </option>
			<!-- END: addresses -->
        </select>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="payment_address" value="new" /> {LANGE.text_address_new}</label>
    </div>
    <br />
    <div id="payment-new" style="display: none;">
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-first-name">{LANGE.entry_first_name}</label>
            <div class="col-sm-20">
                <input type="text" name="first_name" value="" placeholder="{LANGE.entry_first_name}" id="input-payment-first-name" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-last-name">{LANGE.entry_last_name}</label>
            <div class="col-sm-20">
                <input type="text" name="last_name" value="" placeholder="{LANGE.entry_last_name}" id="input-payment-last-name" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="input-payment-company">{LANGE.entry_company}</label>
            <div class="col-sm-20">
                <input type="text" name="company" value="" placeholder="{LANGE.entry_company}" id="input-payment-company" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-address-1">{LANGE.entry_address_1}</label>
            <div class="col-sm-20">
                <input type="text" name="address_1" value="" placeholder="{LANGE.entry_address_1}" id="input-payment-address-1" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="input-payment-address-2">{LANGE.entry_address_2}</label>
            <div class="col-sm-20">
                <input type="text" name="address_2" value="" placeholder="{LANGE.entry_address_2}" id="input-payment-address-2" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-city">{LANGE.entry_city}</label>
            <div class="col-sm-20">
                <input type="text" name="city" value="" placeholder="{LANGE.entry_city}" id="input-payment-city" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-postcode">{LANGE.entry_postcode}</label>
            <div class="col-sm-20">
                <input type="text" name="postcode" value="" placeholder="{LANGE.entry_postcode}" id="input-payment-postcode" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-country">{LANGE.entry_country}</label>
            <div class="col-sm-20">
                <select name="country_id" id="input-payment-country" class="form-control input-sm">
                    <option value=""> --- {LANGE.entry_select} --- </option>
                     
					 <!-- BEGIN: country -->
					  <option value="{COUNTRY.country_id}" {COUNTRY.selected} > {COUNTRY.name} </option>
					 <!-- END: country -->
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-payment-zone">{LANGE.entry_zone}</label>
            <div class="col-sm-20">
                <select name="zone_id" id="input-payment-zone" class="form-control input-sm">
                </select>
            </div>
        </div>
    </div>
    <div class="buttons clearfix">
        <div class="pull-right">
            <input type="button" value="{LANG.button_continue}" id="button-payment-address" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
        </div>
    </div>
</form>
<script type="text/javascript">
    <!--
    $('input[name=\'payment_address\']').on('change', function() {
        if (this.value == 'new') {
            $('#payment-existing').hide();
            $('#payment-new').show();
        } else {
            $('#payment-existing').show();
            $('#payment-new').hide();
        }
    });
    //-->
</script>
<script type="text/javascript">
    <!--
    // Sort the custom fields
    $('#collapse-payment-address .form-group[data-sort]').detach().each(function() {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-payment-address .form-group').length) {
            $('#collapse-payment-address .form-group').eq($(this).attr('data-sort')).before(this);
        }

        if ($(this).attr('data-sort') > $('#collapse-payment-address .form-group').length) {
            $('#collapse-payment-address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('#collapse-payment-address .form-group').length) {
            $('#collapse-payment-address .form-group:first').before(this);
        }
    });
    //-->
</script>
<script type="text/javascript">
    <!--
    $('#collapse-payment-address button[id^=\'button-payment-custom-field\']').on('click', function() {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        $('#form-upload input[name=\'file\']').on('change', function() {
            $.ajax({
                url: 'index.php?route=tool/upload',
                type: 'post',
                dataType: 'json',
                data: new FormData($(this).parent()[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');
                },
                success: function(json) {
                    $('.text-danger').remove();

                    if (json['error']) {
                        $(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $(node).parent().find('input[name^=\'custom_field\']').attr('value', json['file']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //-->
</script>
<script type="text/javascript">
    <!--


    //-->
</script>
<script type="text/javascript">
    <!--
    $('#collapse-payment-address select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&zone&nocache=' + new Date().getTime(),
			data: 'country_id=' + this.value,
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

                if (json['zone']) {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '{DATA.zone_id}') {
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
    //-->
</script>
<!-- END: main -->