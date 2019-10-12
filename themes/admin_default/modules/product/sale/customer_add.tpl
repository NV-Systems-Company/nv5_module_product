<!-- BEGIN: main -->
 {AddMenu}
<div id="productcontent"> 
	<!-- BEGIN: warning -->
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i>
    </div>
	<!-- END: warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
			<div class="pull-right">
				<button type="submit" form="form-coupon" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a> 
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">{LANG.general}</a>
					</li>
					<!-- BEGIN: loadctab -->
					<li><a href="#tab-history" data-toggle="tab">{LANGE.entry_history}</a>
					</li>
					<li><a href="#tab-transaction" data-toggle="tab">{LANGE.entry_transactions}</a>
					</li>
					<li><a href="#tab-reward" data-toggle="tab">{LANGE.entry_reward_points}</a>
					</li>
					<!-- <li><a href="#tab-ip" data-toggle="tab">{LANGE.entry_ip_address}</a> </li> -->
					<!-- END: loadctab -->
				</ul>
				<div class="tab-content" style="margin-top: 20px;">
					<div class="tab-pane active" id="tab-general">
						<div class="row">
							<div class="col-sm-4">
								<ul class="nav nav-pills nav-stacked" id="address">
				
									<li class="active fixnav"><a href="#tab-customer" data-toggle="tab">{LANG.general}</a> </li>

									<!-- BEGIN: address1 -->
									<li><a href="#tab-address{stt}" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('#address a:first').tab('show'); $('#address a[href=\'#tab-address{stt}\']').parent().remove(); $('#tab-address1').remove();"></i> {LANGE.address} {stt}</a></li>
									<!-- END: address1 -->
									<li id="address-add"><a onclick="addAddress();"><i class="fa fa-plus-circle"></i> {LANGE.add_address}</a> </li>
								</ul>
							</div>
							<div class="col-sm-20">
								<div class="tab-content">
									<div class="tab-pane active" id="tab-customer">
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-customer-group">{LANGE.entry_customer_group}</label>
											<div class="col-sm-20">
												<select name="customer_group_id" id="input-customer-group" class="form-control input-sm">	
													<!-- BEGIN: customer_group -->
													<option value="{CUSTOMER_GROUP.key}" {CUSTOMER_GROUP.selected}>{CUSTOMER_GROUP.name}</option>
													<!-- END: customer_group -->
												</select>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-first-name">{LANGE.entry_first_name}</label>
											<div class="col-sm-20">
												<input type="text" name="first_name" value="{DATA.first_name}" placeholder="{LANGE.entry_first_name}" id="input-first-name" class="form-control input-sm" /> 
												<!-- BEGIN: error_first_name --><div class="text-danger">{error_first_name}</div><!-- END: error_first_name -->
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-last-name">{LANGE.entry_last_name}</label>
											<div class="col-sm-20">
												<input type="text" name="last_name" value="{DATA.last_name}" placeholder="{LANGE.entry_last_name}" id="input-last-name" class="form-control input-sm" />
												<!-- BEGIN: error_last_name --><div class="text-danger">{error_last_name}</div><!-- END: error_last_name -->
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-username">{LANGE.entry_username}</label>
											<div class="col-sm-20">
												<input type="text" name="username" value="{DATA.username}" placeholder="{LANGE.entry_username}" id="input-username" class="form-control input-sm" />
											<!-- BEGIN: error_username --><div class="text-danger">{error_username}</div><!-- END: error_username -->
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-email">{LANGE.entry_email}</label>
											<div class="col-sm-20">
												<input type="text" name="email" value="{DATA.email}" placeholder="{LANGE.entry_email}" id="input-email" class="form-control input-sm" />
											<!-- BEGIN: error_email --><div class="text-danger">{error_email}</div><!-- END: error_email -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-gender">{LANGE.entry_gender}</label>
											<div class="col-sm-20">
												<select name="gender" class="form-control input-sm">
												<option value=""> {LANG.NA} </option>
												<!-- BEGIN: gender -->
												<option value="{GENDER.key}" {GENDER.selected}>{GENDER.key}</option>
												<!-- END: gender -->
												</select>
												
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-telephone">{LANGE.entry_telephone}</label>
											<div class="col-sm-20">
												<input type="text" name="telephone" value="{DATA.telephone}" placeholder="{LANGE.entry_telephone}" id="input-telephone" class="form-control input-sm" /> 
												<!-- BEGIN: error_telephone --><div class="text-danger">{error_telephone}</div><!-- END: error_telephone -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-password">{LANGE.entry_password}</label>
											<div class="col-sm-20">
												<input type="password" name="password" value="{DATA.password}" placeholder="{LANGE.entry_password}" id="input-password" class="form-control input-sm" autocomplete="off" /> 
												<!-- BEGIN: error_password --><div class="text-danger">{error_password}</div><!-- END: error_password -->
												
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-confirm">{LANGE.entry_confirm}</label>
											<div class="col-sm-20">
												<input type="password" name="confirm" value="{DATA.confirm}" placeholder="{LANGE.entry_confirm}" autocomplete="off" id="input-confirm" class="form-control input-sm" /> 
												<!-- BEGIN: error_confirm --><div class="text-danger">{error_confirm}</div><!-- END: error_confirm -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-newsletter">{LANGE.entry_newsletter}</label>
											<div class="col-sm-20">
												<select name="newsletter" id="input-newsletter" class="form-control input-sm">
													<!-- BEGIN: newsletter -->
													<option value="{NEWSLETTER.key}" {NEWSLETTER.selected}>{NEWSLETTER.name}</option>
													<!-- END: newsletter -->
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-active">{LANGE.entry_status}</label>
											<div class="col-sm-20">
												<select name="active" id="input-active" class="form-control input-sm">
													<!-- BEGIN: active -->
													<option value="{ACTIVE.key}" {ACTIVE.selected}>{ACTIVE.name}</option>
													<!-- END: active -->
													
												</select>
											</div>
										</div>
			 
									</div>
									<!-- BEGIN: address2 -->
									<div class="tab-pane" id="tab-address{stt}">
										<input type="hidden" name="address[{stt}][address_id]" value="{LOOP.address_id}" />
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-first-name{stt}">{LANGE.entry_first_name}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][first_name]" value="{LOOP.first_name}" placeholder="{LANGE.entry_first_name}" id="input-first-name{stt}" class="form-control input-sm" /> 
												<!-- BEGIN: error_first_name --><div class="text-danger">{error_first_name}</div><!-- END: error_first_name -->
												
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-last-name{stt}">{LANGE.entry_last_name}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][last_name]" value="{LOOP.last_name}" placeholder="{LANGE.entry_last_name}" id="input-last-name{stt}" class="form-control input-sm" /> 
												<!-- BEGIN: error_last_name --><div class="text-danger">{error_last_name}</div><!-- END: error_last_name -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-company{stt}">{LANGE.entry_company}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][company]" value="{LOOP.company}" placeholder="{LANGE.entry_company}" id="input-company{stt}" class="form-control input-sm" /> 
												
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-address-1{stt}">{LANGE.entry_address_1}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][address_1]" value="{LOOP.address_1}" placeholder="{LANGE.entry_address_1}" id="input-address-1{stt}" class="form-control input-sm" /> 
												<!-- BEGIN: error_address_1 --><div class="text-danger">{error_address_1}</div><!-- END: error_address_1 -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-address-2{stt}">{LANGE.entry_address_2}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][address_2]" value="{LOOP.address_2}" placeholder="{LANGE.entry_address_2}" id="input-address-2{stt}" class="form-control input-sm" /> 
											
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-city{stt}">{LANGE.entry_city}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][city]" value="{LOOP.city}" placeholder="{LANGE.entry_city}" id="input-city{stt}" class="form-control input-sm" /> 
												<!-- BEGIN: error_city --><div class="text-danger">{error_city}</div><!-- END: error_city -->
											
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-postcode{stt}">{LANGE.entry_postcode}</label>
											<div class="col-sm-20">
												<input type="text" name="address[{stt}][postcode]" value="{LOOP.postcode}" placeholder="{LANGE.entry_postcode}" id="input-postcode1" class="form-control input-sm" /> 
												<!-- BEGIN: error_postcode --><div class="text-danger">{error_postcode}</div><!-- END: error_postcode -->
											
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-country{stt}">{LANGE.entry_country}</label>
											<div class="col-sm-20">
												<select name="address[{stt}][country_id]" id="input-country{stt}" onchange="country(this, '{stt}', '{LOOP.zone_id}');" class="form-control input-sm">
													<option value=""> --- {LANG.please_select_one} --- </option>
													<!-- BEGIN: loopcountry -->
													<option value="{country_id}" {country_selected}>{country_name}</option>
													 <!-- END: loopcountry -->
												</select>
												<!-- BEGIN: error_country --><div class="text-danger">{error_country}</div><!-- END: error_country -->
											
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-zone{stt}">{LANGE.entry_zone}</label>
											<div class="col-sm-20"> 
												<select name="address[{stt}][zone_id]" id="input-zone{stt}" class="form-control input-sm"> </select>
												<!-- BEGIN: error_zone --><div class="text-danger">{error_zone}</div><!-- END: error_zone -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label">{LANGE.entry_default}</label>
											<div class="col-sm-20">
												<label class="radio" >
													<input style="margin-left:0" type="radio" name="address[{stt}][default]" {LOOP.address_checked} value="1" /> </label>
											</div>
										</div>
									</div>
									<!-- END: address2 -->
								</div>
							</div>
						</div>
					</div>
					<!-- BEGIN: loadcontent -->
					<div class="tab-pane" id="tab-history">
						<div id="history"></div>
						<br />
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-comment">{LANGE.entry_comment}</label>
							<div class="col-sm-20">
								<textarea name="comment" rows="8" placeholder="{LANGE.entry_comment}" id="input-comment" class="form-control input-sm"></textarea>
							</div>
						</div>
						<div class="text-right">
							<button id="button-history" data-loading-text="Loading..." class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANGE.add_history}</button>
						</div>
					</div>
					<div class="tab-pane" id="tab-transaction">
						<div id="transaction"></div>
						<br />
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-transaction-description">{LANGE.entry_description}</label>
							<div class="col-sm-20">
								<input type="text" name="description" value="" placeholder="{LANGE.entry_description}" id="input-transaction-description" class="form-control input-sm" /> </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-amount">{LANGE.entry_amount}</label>
							<div class="col-sm-20">
								<input type="text" name="amount" value="" placeholder="{LANGE.entry_amount}" id="input-amount" class="form-control input-sm" /> </div>
						</div>
						<div class="text-right">
							<button type="button" id="button-transaction" data-loading-text="{LANGE.button_loading}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANGE.text_add_transition}</button>
						</div>
					</div>
					<div class="tab-pane" id="tab-reward">
						<div id="reward"></div>
						<br />
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-reward-description">{LANGE.entry_description}</label>
							<div class="col-sm-20">
								<input type="text" name="description" value="" placeholder="{LANGE.entry_description}" id="input-reward-description" class="form-control input-sm" /> </div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-points"><span data-toggle="tooltip" title="{LANGE.help_points}">{LANGE.entry_points}</span>
							</label>
							<div class="col-sm-20">
								<input type="text" name="points" value="" placeholder="{LANGE.entry_points}" id="input-points" class="form-control input-sm" /> </div>
						</div>
						<div class="text-right">
							<button type="button" id="button-reward" data-loading-text="Loading..." class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANG.add_reward_points}</button>
						</div>
					</div>
					<div class="tab-pane" id="tab-ip">
						<div id="ip"></div>
					</div>
					<!-- END: loadcontent -->
				</div>
				<div align="center">
					<input class="btn btn-primary btn-sm" name="userid" type="hidden" value="{DATA.userid}">
					<input class="btn btn-primary btn-sm" name="save" type="hidden" value="1">
					<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
					<a class="btn btn-default btn-sm" href="{BACK}" title="{LANG.cancel}">{LANG.cancel}</a> 
				</div>
			</form>	
		</div>
	</div>
</div>
<script type="text/javascript">
$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/customfield&token=7e8ec4740bcf778e8b58fcbe8c61bff2&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('.custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('.custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//$('select[name=\'customer_group_id\']').trigger('change');
</script> 
<script type="text/javascript">
var address_row = {NUM};

function addAddress() {
	html  = '<div class="tab-pane" id="tab-address' + address_row + '">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-first-name' + address_row + '">{LANGE.entry_first_name}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][first_name]" value="" placeholder="{LANGE.entry_first_name}" id="input-first-name' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-last-name' + address_row + '">{LANGE.entry_last_name}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][last_name]" value="" placeholder="{LANGE.entry_last_name}" id="input-last-name' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-4 control-label" for="input-company' + address_row + '">{LANGE.entry_company}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][company]" value="" placeholder="{LANGE.entry_company}" id="input-company' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-address-1' + address_row + '">{LANGE.entry_address_1}"</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][address_1]" value="" placeholder="{LANGE.entry_address_1}" id="input-address-1' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-4 control-label" for="input-address-2' + address_row + '">{LANGE.entry_address_2}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][address_2]" value="" placeholder="{LANGE.entry_address_2}" id="input-address-2' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-city' + address_row + '">{LANGE.entry_city}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][city]" value="" placeholder="{LANGE.entry_city}" id="input-city' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-postcode' + address_row + '">{LANGE.entry_postcode}</label>';
	html += '    <div class="col-sm-20"><input type="text" name="address[' + address_row + '][postcode]" value="" placeholder="{LANGE.entry_postcode}" id="input-postcode' + address_row + '" class="form-control input-sm" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-country' + address_row + '">Country</label>';
	html += '    <div class="col-sm-20"><select name="address[' + address_row + '][country_id]" id="input-country' + address_row + '" onchange="country(this, \'' + address_row + '\', \'0\');" class="form-control input-sm">';
		html += '<option value=""> --- Please Select --- </option>';
		<!-- BEGIN: getcountry -->
	    html += '         <option value="{getcountry_id}">{getcountry_name}</option>';
		<!-- END: getcountry -->

	html += '  </select></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-4 control-label" for="input-zone' + address_row + '">{LANGE.entry_zone}</label>';
	html += '    <div class="col-sm-20"><select name="address[' + address_row + '][zone_id]" id="input-zone' + address_row + '" class="form-control input-sm"><option value=""> --- None --- </option></select></div>';
	html += '  </div>';
 
	
	html += '  <div class="form-group">';
	html += '    <label class="col-sm-4 control-label">{LANGE.entry_default}</label>';
	html += '    <div class="col-sm-20"><label class="radio"><input type="radio" name="address[' + address_row + '][default]" value="1" /></label></div>';
	html += '  </div>';

    html += '</div>';

	$('#tab-general .tab-content').prepend(html);

	$('select[name=\'customer_group_id\']').trigger('change');

	$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');

	$('#address-add').before('<li><a href="#tab-address' + address_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'#address a:first\').tab(\'show\'); $(\'a[href=\\\'#tab-address' + address_row + '\\\']\').parent().remove(); $(\'#tab-address' + address_row + '\').remove();"></i> {LANGE.address} ' + address_row + '</a></li>');

	$('#address a[href=\'#tab-address' + address_row + '\']').tab('show');

 
	address_row++;
}
</script> 
<script type="text/javascript">
function country(element, index, zone_id) {
  if (element.value != '') {
		$.ajax({
			url: '{GET_LINK}&action=zone&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'address[' + index + '][country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('input[name=\'address[' + index + '][postcode]\']').parent().addClass('required');
				} else {
					$('input[name=\'address[' + index + '][postcode]\']').parent().parent().removeClass('required');
				}

				html = '<option value=""> --- Please Select --- </option>';

				if (json['zone']) {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0"> --- None --- </option>';
				}

				$('select[name=\'address[' + index + '][zone_id]\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('select[name$=\'[country_id]\']').trigger('change');
</script> 



<!-- BEGIN: loadscript -->

<script type="text/javascript">

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});
 
$('#button-history').on('click', function(e) {
  e.preventDefault();

	$.ajax({
		url: 'index.php?route=sale/customer/history&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1',
		type: 'post',
		dataType: 'html',
		data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-history').button('loading');
		},
		complete: function() {
			$('#button-history').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#history').html(html);

			$('#tab-history textarea[name=\'comment\']').val('');
		}
	});
});


$('#transaction').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#transaction').load(this.href);
});

//$('#transaction').load('index.php?route=sale/customer/transaction&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1');

$('#button-transaction').on('click', function(e) {
  e.preventDefault();
  $.ajax({
		url: 'index.php?route=sale/customer/transaction&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
		beforeSend: function() {
			$('#button-transaction').button('loading');
		},
		complete: function() {
			$('#button-transaction').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#transaction').html(html);

			$('#tab-transaction input[name=\'amount\']').val('');
			$('#tab-transaction input[name=\'description\']').val('');
		}
	});
});

$('#reward').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#reward').load(this.href);
});

//$('#reward').load('index.php?route=sale/customer/reward&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1');

$('#button-reward').on('click', function(e) {
	e.preventDefault();

	$.ajax({
		url: 'index.php?route=sale/customer/reward&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
		beforeSend: function() {
			$('#button-reward').button('loading');
		},
		complete: function() {
			$('#button-reward').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#reward').html(html);

			$('#tab-reward input[name=\'points\']').val('');
			$('#tab-reward input[name=\'description\']').val('');
		}
	});
});

$('#ip').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#ip').load(this.href);
});

//$('#ip').load('index.php?route=sale/customer/ip&token=7e8ec4740bcf778e8b58fcbe8c61bff2&userid=1');

$('body').delegate('.button-ban-add', 'click', function() {
	var element = this;

	$.ajax({
		url: '/addbanip&token=7e8ec4740bcf778e8b58fcbe8c61bff2',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(this.value),
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				 $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

				$('.alert').fadeIn('slow');
			}

			if (json['success']) {
				$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> Remove Ban IP</button>');
			}
		}
	});
});

$('body').delegate('.button-ban-remove', 'click', function() {
	var element = this;

	$.ajax({
		url: '/removebanip&token=7e8ec4740bcf778e8b58fcbe8c61bff2',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(this.value),
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				 $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				 $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-success btn-xs button-ban-add"><i class="fa fa-plus-circle"></i> Add Ban IP</button>');
			}
		}
	});
});

$('#productcontent').delegate('button[id^=\'button-custom-field\'], button[id^=\'button-address\']', 'click', function() {
	var node = this;
	
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: '',
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
					$(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
				}
							
				if (json['success']) {
					alert(json['success']);
				}
				
				if (json['code']) {
					$(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

</script>

<!-- END: loadscript -->

<!-- END: main -->