<!-- BEGIN: main -->
<div id="productcontent">
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-list"></i> {LANGE.text_edit}</h3> 
                <div class="pull-right">
                     <a href="#sale/order/invoice&amp;order_id=5" target="_blank" data-toggle="tooltip" class="btn btn-info btn-sm"  title="Print Invoice"><i class="fa fa-print"></i></a> 
					 <a href="#sale/order/shipping&amp;order_id=5" target="_blank" data-toggle="tooltip" class="btn btn-info btn-sm"  title="Print Shipping List"><i class="fa fa-truck"></i></a>  
					<a href="{ORDER_EDIT}" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.edit}"><i class="fa fa-pencil"></i></a> 
					<a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" data-original-title="{LANG.button_cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
			<div class="panel-body">
				<form class="form-horizontal">
					<ul id="order" class="nav nav-tabs nav-justified">
						<li class="disabled active"><a href="#tab-customer" data-toggle="tab">1. {LANG.tab_customer}</a>
						</li>
						<li class="disabled"><a href="#tab-cart" data-toggle="tab">2. {LANG.tab_product}</a>
						</li>
						<li class="disabled"><a href="#tab-payment" data-toggle="tab">3. {LANG.tab_payment}</a>
						</li>
						<li class="disabled"><a href="#tab-shipping" data-toggle="tab">4. {LANG.tab_shipping}</a>
						</li>
						<li class="disabled"><a href="#tab-total" data-toggle="tab">5. {LANG.tab_total}</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-customer">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-store">{LANGE.entry_store}</label>
								<div class="col-sm-10">
									<select name="store_id" id="input-store" class="form-control input-sm">
										<!-- BEGIN: store -->
										<option value="{STORE.key}" {STORE.selected}>{STORE.name}</option>
										<!-- END: store -->
									</select>
								</div>
							  </div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-customer">{LANGE.entry_customer}</label>
								<div class="col-sm-20">
									<input type="text" name="customer" value="{DATA.full_name}" placeholder="{LANGE.entry_customer}" id="input-customer" class="form-control input-sm" />
									<input type="hidden" name="userid" value="{DATA.userid}" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-customer-group">{LANGE.entry_customer_group}</label>
								<div class="col-sm-20">
									<select name="customer_group_id" id="input-customer-group" class="form-control input-sm">
										<!-- BEGIN: customer_group -->
										<option value="{CGROUP.key}" {CGROUP.selected}>{CGROUP.name}</option>
										<!-- END: customer_group -->
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-first-name">{LANGE.entry_first_name}</label>
								<div class="col-sm-20">
									<input type="text" name="first_name" value="{DATA.first_name}" id="input-first-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-last-name">{LANGE.entry_last_name}</label>
								<div class="col-sm-20">
									<input type="text" name="last_name" value="{DATA.last_name}" id="input-last-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-email">{LANGE.entry_email}</label>
								<div class="col-sm-20">
									<input type="text" name="email" value="{DATA.email}" id="input-email" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-telephone">{LANGE.entry_telephone}</label>
								<div class="col-sm-20">
									<input type="text" name="telephone" value="{DATA.telephone}" id="input-telephone" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-fax">{LANGE.entry_fax}</label>
								<div class="col-sm-20">
									<input type="text" name="fax" value="{DATA.fax}" id="input-fax" class="form-control input-sm" />
								</div>
							</div>
							<div class="text-right">
								<button type="button" id="button-customer" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i> {LANG.button_continue}</button>
							</div>
						</div>
						<div class="tab-pane" id="tab-cart">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-left">{LANGE.column_product}</td>
											<td class="text-left">{LANGE.column_model}</td>
											<td class="text-right">{LANGE.column_quantity}</td>
											<td class="text-right">{LANGE.column_price}</td>
											<td class="text-right">{LANGE.column_total}</td>
											<td></td>
										</tr>
									</thead>
									
									<tbody id="cart">
										<!-- BEGIN: product -->
										<tr>
											<td class="text-left">{PRODUCT.name}
												<br>
												<input type="hidden" name="product[{KEY}][product_id]" value="{PRODUCT.product_id}">
											</td>
											<td class="text-left">{PRODUCT.model}</td>
											<td class="text-right">{PRODUCT.quantity}
												<input type="hidden" name="product[{KEY}][quantity]" value="{PRODUCT.quantity}">
											</td>
											<td class="text-right">{PRODUCT.price}</td>
											<td class="text-right">{PRODUCT.total}</td>
											<td class="text-center" style="width: 3px;">
												<button type="button" value="{PRODUCT.key}=" data-toggle="tooltip" data-loading-text="{LANG.button_loading}" class="btn btn-danger btn-sm" title="{LANG.delete}"><i class="fa fa-minus-circle"></i></button>
											</td>
										</tr>
										<!-- END: product -->
										<!-- BEGIN: no_results -->
										<tr>
											<td class="text-center" colspan="6">{LANG.text_no_results}!</td>
										</tr>
										<!-- END: no_results -->
									</tbody>
 
								</table>
							</div>
							<ul class="nav nav-tabs nav-justified">
								<li class="active"><a href="#tab-product" data-toggle="tab">{LANG.tab_product}</a>
								</li>
								<li><a href="#tab-voucher" data-toggle="tab">{LANG.tab_voucher}</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab-product">
									<fieldset>
										<legend>{LANGE.text_product}</legend>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-product">{LANGE.entry_product}</label>
											<div class="col-sm-20">
												<input type="text" name="product" value="" id="input-product" class="form-control input-sm" />
												<input type="hidden" name="product_id" value="" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-quantity">{LANGE.entry_quantity}</label>
											<div class="col-sm-20">
												<input type="text" name="quantity" value="1" id="input-quantity" class="form-control input-sm" />
											</div>
										</div>
										<div id="option"></div>
									</fieldset>
									<div class="text-right">
										<button type="button" id="button-product-add" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANG.button_product_add}</button>
									</div>
								</div>
								<div class="tab-pane" id="tab-voucher">
									<fieldset>
										<legend>{LANGE.text_voucher}</legend>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-to-name">{LANGE.entry_to_name}</label>
											<div class="col-sm-20">
												<input type="text" name="to_name" value="" id="input-to-name" class="form-control input-sm" />
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-to-email">{LANGE.entry_to_email}</label>
											<div class="col-sm-20">
												<input type="text" name="to_email" value="" id="input-to-email" class="form-control input-sm" />
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-from-name">{LANGE.entry_from_name}</label>
											<div class="col-sm-20">
												<input type="text" name="from_name" value="" id="input-from-name" class="form-control input-sm" />
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-from-email">{LANGE.entry_from_email}</label>
											<div class="col-sm-20">
												<input type="text" name="from_email" value="" id="input-from-email" class="form-control input-sm" />
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-theme">{LANGE.entry_theme}</label>
											<div class="col-sm-20">
												<select name="voucher_theme_id" id="input-theme" class="form-control input-sm">
													<!--  BEGIN: voucher_theme -->
													<option value="{VOUCHER_THEME.voucher_theme_id}">{VOUCHER_THEME.name}</option>
													<!--  END: voucher_theme -->
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label" for="input-message">{LANGE.entry_message}</label>
											<div class="col-sm-20">
												<textarea name="message" rows="5" id="input-message" class="form-control input-sm"></textarea>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-4 control-label" for="input-amount">{LANGE.entry_amount}</label>
											<div class="col-sm-20">
												<input type="text" name="amount" value="1" id="input-amount" class="form-control input-sm" />
											</div>
										</div>
									</fieldset>
									<div class="text-right">
										<button type="button" id="button-voucher-add" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {LANG.button_voucher}</button>
									</div>
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-sm-12 text-left">
									<button type="button" onclick="$('a[href=\'#tab-customer\']').tab('show');" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> {LANG.button_back}</button>
								</div>
								<div class="col-sm-12 text-right">
									<button type="button" id="button-cart" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i> {LANG.button_continue}</button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-payment">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-payment-address">{LANGE.entry_address}</label>
								<div class="col-sm-20">
									<select name="payment_address" id="input-payment-address" class="form-control input-sm">
										<option value="0" selected="selected"> {LANG.text_none} </option>
										<!-- BEGIN: payment_address -->
										<option value="{ADDRESS.key}">{ADDRESS.name}</option>
										<!-- END: payment_address -->
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-first-name">{LANGE.entry_first_name}</label>
								<div class="col-sm-20">
									<input type="text" name="first_name" value="{DATA.payment_first_name}" id="input-payment-first-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-last-name">{LANGE.entry_last_name}</label>
								<div class="col-sm-20">
									<input type="text" name="last_name" value="{DATA.payment_last_name}" id="input-payment-last-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-payment-company">{LANGE.entry_company}</label>
								<div class="col-sm-20">
									<input type="text" name="company" value="{DATA.payment_company}" id="input-payment-company" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-address-1">{LANGE.entry_address_1}</label>
								<div class="col-sm-20">
									<input type="text" name="address_1" value="{DATA.payment_address_1}" id="input-payment-address-1" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-payment-address-2">{LANGE.entry_address_2}</label>
								<div class="col-sm-20">
									<input type="text" name="address_2" value="{DATA.payment_address_2}" id="input-payment-address-2" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-city">{LANGE.entry_city}</label>
								<div class="col-sm-20">
									<input type="text" name="city" value="{DATA.payment_city}" id="input-payment-city" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-postcode">{LANGE.entry_postcode}</label>
								<div class="col-sm-20">
									<input type="text" name="postcode" value="{DATA.payment_postcode}" id="input-payment-postcode" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-payment-country">{LANGE.entry_country}</label>
								<div class="col-sm-20">
									<select name="country_id" id="input-payment-country" class="form-control input-sm">
										<option value=""> {LANG.text_select} </option>
										<!-- BEGIN: pcountry -->
										<option value="{PCOUNTRY.key}" {PCOUNTRY.selected}>{PCOUNTRY.name}</option>
									    <!-- END: pcountry -->
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
							<div class="row">
								<div class="col-sm-12 text-left">
									<button type="button" onclick="$('a[href=\'#tab-cart\']').tab('show');" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> {LANG.button_back}</button>
								</div>
								<div class="col-sm-12 text-right">
									<button type="button" id="button-payment-address" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i> {LANG.button_continue}</button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-shipping">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-shipping-address">{LANGE.entry_address}</label>
								<div class="col-sm-20">
									<select name="shipping_address" id="input-shipping-address" class="form-control input-sm">
										<option value="0" selected="selected"> {LANG.text_none} </option>
										<!-- BEGIN: shipping_address -->
										<option value="{ADDRESS.key}">{ADDRESS.name}</option>
										<!-- END: shipping_address -->
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-first-name">{LANGE.entry_first_name}</label>
								<div class="col-sm-20">
									<input type="text" name="first_name" value="{DATA.shipping_first_name}" id="input-shipping-first-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-last-name">{LANGE.entry_last_name}</label>
								<div class="col-sm-20">
									<input type="text" name="last_name" value="{DATA.shipping_last_name}" id="input-shipping-last-name" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-shipping-company">{LANGE.entry_company}</label>
								<div class="col-sm-20">
									<input type="text" name="company" value="{DATA.shipping_company}" id="input-shipping-company" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-address-1">{LANGE.entry_address_1}</label>
								<div class="col-sm-20">
									<input type="text" name="address_1" value="{DATA.shipping_address_1}" id="input-shipping-address-1" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-shipping-address-2">{LANGE.entry_address_1}</label>
								<div class="col-sm-20">
									<input type="text" name="address_2" value="{DATA.shipping_address_2}" id="input-shipping-address-2" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-city">{LANGE.entry_city}</label>
								<div class="col-sm-20">
									<input type="text" name="city" value="{DATA.shipping_city}" id="input-shipping-city" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-postcode">{LANGE.entry_postcode}</label>
								<div class="col-sm-20">
									<input type="text" name="postcode" value="{DATA.shipping_postcode}" id="input-shipping-postcode" class="form-control input-sm" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-4 control-label" for="input-shipping-country">{LANGE.entry_country}</label>
								<div class="col-sm-20">
									<select name="country_id" id="input-shipping-country" class="form-control input-sm">
										<option value=""> {LANG.text_select} </option>
										<!-- BEGIN: scountry -->
										<option value="{SCOUNTRY.key}" {SCOUNTRY.selected}>{SCOUNTRY.name}</option>
									    <!-- END: scountry -->
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
							<div class="row">
								<div class="col-sm-12 text-left">
									<button type="button" onclick="$('a[href=\'#tab-payment\']').tab('show');" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> {LANG.button_back}</button>
								</div>
								<div class="col-sm-12 text-right">
									<button type="button" id="button-shipping-address" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i> {LANG.button_continue}</button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-total">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-left">{LANGE.column_product}</td>
											<td class="text-left">{LANGE.column_model}</td>
											<td class="text-right">{LANGE.column_quantity}
											<td class="text-right">{LANGE.column_price}</td>
											<td class="text-right">{LANGE.column_total}</td>
										</tr>
									</thead>
									<tbody id="total">
										<tr>
											<td class="text-center" colspan="5">{LANGE.text_no_results}!</td>
										</tr>
									</tbody>
								</table>
							</div>
							<fieldset>
								<legend>{LANGE.text_order}</legend>
								<div class="form-group required">
									<label class="col-sm-4 control-label" for="input-shipping-method">{LANGE.entry_shipping_method}</label>
									<div class="col-sm-20">
										<div class="input-group">
											<select name="shipping_method" id="input-shipping-method" class="form-control input-sm">
												<option value=""> {LANG.text_select} </option>
												<!-- BEGIN: shipping_method -->
												<option value="{SHIPPING.key}" selected="selected">{SHIPPING.name}</option>
												<!-- END: shipping_method -->
											</select>
											<span class="input-group-btn">
								  <button type="button" id="button-shipping-method" data-toggle="tooltip" title="{LANG.button_shipping}" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
								  </span>
										</div>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-4 control-label" for="input-payment-method">{LANGE.entry_payment_method}</label>
									<div class="col-sm-20">
										<div class="input-group">
											<select name="payment_method" id="input-payment-method" class="form-control input-sm">
												<option value=""> {LANG.text_select} </option>
												<!-- BEGIN: payment_method -->
												<option value="{PAYMENT.key}" selected="selected">{PAYMENT.name}</option>
												<!-- END: payment_method -->
											</select>
											<span class="input-group-btn">
								  <button type="button" id="button-payment-method" data-toggle="tooltip" title="{LANG.button_payment}" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
								  </span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-coupon">{LANGE.entry_coupon}</label>
									<div class="col-sm-20">
										<div class="input-group">
											<input type="text" name="coupon" value="" id="input-coupon" class="form-control input-sm" />
											<span class="input-group-btn">
								  <button type="button" id="button-coupon" data-toggle="tooltip" title="{LANG.button_coupon}" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
								  </span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-voucher">{LANGE.entry_voucher}</label>
									<div class="col-sm-20">
										<div class="input-group">
											<input type="text" name="voucher" value="" id="input-voucher" data-loading-text="{LANG.text_loading}" class="form-control input-sm" />
											<span class="input-group-btn">
								  <button type="button" id="button-voucher" data-toggle="tooltip" title="{LANGE.button_voucher}" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
								  </span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-reward">{LANGE.entry_reward}</label>
									<div class="col-sm-20">
										<div class="input-group">
											<input type="text" name="reward" value="" id="input-reward" data-loading-text="{LANG.text_loading}" class="form-control input-sm" />
											<span class="input-group-btn">
											  <button type="button" id="button-reward" data-toggle="tooltip" title="{LANG.button_reward}" data-loading-text="{LANG.text_loading}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-order-status">{LANGE.entry_order_status}</label>
									<div class="col-sm-20">
										<select name="order_status_id" id="input-order-status" class="form-control input-sm">
											<!-- BEGIN: order_status -->
											<option value="{ORDER_STATUS.key}">{ORDER_STATUS.name}</option>
											<!-- END: order_status -->
										</select>
										<input type="hidden" name="order_id" value="{DATA.order_id}" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-comment">{LANGE.entry_comment}</label>
									<div class="col-sm-20">
										<textarea name="comment" rows="5" id="input-comment" class="form-control input-sm"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="input-affiliate">{LANGE.entry_affiliate}</label>
									<div class="col-sm-20">
										<input type="text" name="affiliate" value=" " id="input-affiliate" class="form-control input-sm" />
										<input type="hidden" name="affiliate_id" value="{DATA.affiliate_id}" />
									</div>
								</div>
							</fieldset>
							<div class="row">
								<div class="col-sm-12 text-left">
									<button type="button" onclick="$('select[name=\'shipping_method\']').prop('disabled') ? $('a[href=\'#tab-payment\']').tab('show') : $('a[href=\'#tab-shipping\']').tab('show');" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> {LANG.button_back}</button>
								</div>
								<div class="col-sm-12 text-right">
									<button type="button" id="button-refresh" data-loading-text="{LANG.text_loading}" class="btn btn-warning"><i class="fa fa-refresh"></i>
									</button>
									<button type="button" id="button-save" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i> {LANG.button_save}</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
 
		</div>
	</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/content.js"></script>
  

<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/autofill.js"></script>

<script type="text/javascript">
    // Disable the tabs
    $('#order a[data-toggle=\'tab\']').on('click', function(e) {
        return false;
    });
	
	// Add all products to the cart
	$('#button-refresh').on('click', function() {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=cart_products&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
			dataType: 'json',
			success: function(json) {
				$('.alert-danger, .text-danger').remove();
				// Check for errors
				if (json['error']) {
					if (json['error']['warning']) {
						$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
					}
					if (json['error']['stock']) {
						$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['stock'] + '</div>');
					}
					if (json['error']['minimum']) {
						for (i in json['error']['minimum']) {
							$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['minimum'][i] + ' <i class="fa fa-times"></i></div>');
						}
					}
				}
				var shipping = false;
				html = '';
				if (json['products']) {
					for (i = 0; i < json['products'].length; i++) {
						product = json['products'][i];
						html += '<tr>';
						html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
						html += '  <input type="hidden" name="product[' + i + '][product_id]" value="' + product['product_id'] + '" />';
						if (product['option']) {
							for (j = 0; j < product['option'].length; j++) {
								option = product['option'][j];
								html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
								if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
									html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '" />';
								}
								if (option['type'] == 'checkbox') {
									html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '" />';
								}
								if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
									html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '" />';
								}
							}
						}
						html += '</td>';
						html += '  <td class="text-left">' + product['model'] + '</td>';
						html += '  <td class="text-right">' + product['quantity'] + '<input type="hidden" name="product[' + i + '][quantity]" value="' + product['quantity'] + '" /></td>';
						html += '  <td class="text-right">' + product['price'] + '</td>';
						html += '  <td class="text-right">' + product['total'] + '</td>';
						html += '  <td class="text-center" style="width: 3px;"><button type="button" value="' + product['key'] + '" data-toggle="tooltip" title="{LANG.text_remove}" data-loading-text="{LANG.text_loading}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
						html += '</tr>';
						if (product['shipping'] != 0) {
							shipping = true;
						}
					}
				}
				if (!shipping) {
					$('select[name=\'shipping_method\'] option').removeAttr('selected');
					$('select[name=\'shipping_method\']').prop('disabled', true);
					$('#button-shipping-method').prop('disabled', true);
				} else {
					$('select[name=\'shipping_method\']').prop('disabled', false);
					$('#button-shipping-method').prop('disabled', false);
				}
				if (json['vouchers']) {
					for (i in json['vouchers']) {
						voucher = json['vouchers'][i];
						html += '<tr>';
						html += '  <td class="text-left">' + voucher['description'];
						html += '    <input type="hidden" name="voucher[' + i + '][code]" value="' + voucher['code'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][description]" value="' + voucher['description'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][from_name]" value="' + voucher['from_name'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][from_email]" value="' + voucher['from_email'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][to_name]" value="' + voucher['to_name'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][to_email]" value="' + voucher['to_email'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][voucher_theme_id]" value="' + voucher['voucher_theme_id'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][message]" value="' + voucher['message'] + '" />';
						html += '    <input type="hidden" name="voucher[' + i + '][amount]" value="' + voucher['amount'] + '" />';
						html += '  </td>';
						html += '  <td class="text-left"></td>';
						html += '  <td class="text-right">1</td>';
						html += '  <td class="text-right">' + voucher['amount'] + '</td>';
						html += '  <td class="text-right">' + voucher['amount'] + '</td>';
						html += '  <td class="text-center" style="width: 3px;"><button type="button" value="' + voucher['code'] + '" data-toggle="tooltip" title="{LANG.text_remove}" data-loading-text="{LANG.text_loading}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
						html += '</tr>';
					}
				}
				if (json['products'] == '' && json['vouchers'] == '') {
					html += '<tr>';
					html += '  <td colspan="6" class="text-center">{LANG.text_no_results}</td>';
					html += '</tr>';
				}
				$('#cart').html(html);
				// Totals
				html = '';
				if (json['products']) {
					for (i = 0; i < json['products'].length; i++) {
						product = json['products'][i];
						html += '<tr>';
						html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
						if (product['option']) {
							for (j = 0; j < product['option'].length; j++) {
								option = product['option'][j];
								html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							}
						}
						html += '  </td>';
						html += '  <td class="text-left">' + product['model'] + '</td>';
						html += '  <td class="text-right">' + product['quantity'] + '</td>';
						html += '  <td class="text-right">' + product['price'] + '</td>';
						html += '  <td class="text-right">' + product['total'] + '</td>';
						html += '</tr>';
					}
				}
				if (json['vouchers']) {
					for (i in json['vouchers']) {
						voucher = json['vouchers'][i];
						html += '<tr>';
						html += '  <td class="text-left">' + voucher['description'] + '</td>';
						html += '  <td class="text-left"></td>';
						html += '  <td class="text-right">1</td>';
						html += '  <td class="text-right">' + voucher['amount'] + '</td>';
						html += '  <td class="text-right">' + voucher['amount'] + '</td>';
						html += '</tr>';
					}
				}
				if (json['totals']) {
					for (i in json['totals']) {
						total = json['totals'][i];
						html += '<tr>';
						html += '  <td class="text-right" colspan="4">' + total['title'] + ':</td>';
						html += '  <td class="text-right">' + total['text'] + '</td>';
						html += '</tr>';
					}
				}
				if (!json['totals'] && !json['products'] && !json['vouchers']) {
					html += '<tr>';
					html += '  <td colspan="5" class="text-center">{LANG.text_no_results}</td>';
					html += '</tr>';
				}
				$('#total').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
    // Customer
    $('input[name=\'customer\']').autofill({
        'source': function(request, response) {
            $.ajax({
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_customer&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        userid: '{DATA.userid}',
                        customer_group_id: '{DATA.customer_group_id}',
                        name: ' {LANG.text_none} ',
                        customer_group: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        telephone: '',
                        fax: '',
                        custom_field: [],
                        address: []
                    });

                    response($.map(json, function(item) {
                        return {
                            category: item['customer_group'],
                            label: item['name'],
                            value: item['userid'],
                            customer_group_id: item['customer_group_id'],
                            first_name: item['first_name'],
                            last_name: item['last_name'],
                            email: item['email'],
                            telephone: item['telephone'],
                            fax: item['fax'],
                            custom_field: item['custom_field'],
                            address: item['address']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            // Reset all custom fields
            $('#tab-customer input[type=\'text\'], #tab-customer input[type=\'text\'], #tab-customer textarea').not('#tab-customer input[name=\'customer\'], #tab-customer input[name=\'userid\']').val('');
            $('#tab-customer select option').removeAttr('selected');
            $('#tab-customer input[type=\'checkbox\'], #tab-customer input[type=\'radio\']').removeAttr('checked');

            $('#tab-customer input[name=\'customer\']').val(item['label']);
            $('#tab-customer input[name=\'userid\']').val(item['value']);
            $('#tab-customer select[name=\'customer_group_id\']').val(item['customer_group_id']);
            $('#tab-customer input[name=\'first_name\']').val(item['first_name']);
            $('#tab-customer input[name=\'last_name\']').val(item['last_name']);
            $('#tab-customer input[name=\'email\']').val(item['email']);
            $('#tab-customer input[name=\'telephone\']').val(item['telephone']);
            $('#tab-customer input[name=\'fax\']').val(item['fax']);

            for (i in item.custom_field) {
                $('#tab-customer select[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
                $('#tab-customer textarea[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
                $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(item.custom_field[i]);
                $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(item.custom_field[i]);
                $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + item.custom_field[i] + '\']').prop('checked', true);

                if (item.custom_field[i] instanceof Array) {
                    for (j = 0; j < item.custom_field[i].length; j++) {
                        $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + item.custom_field[i][j] + '\']').prop('checked', true);
                    }
                }
            }

            //$('select[name=\'customer_group_id\']').trigger('change');

            html = '<option value="0"> {LANG.text_none} </option>';

            for (i in item['address']) {
                html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['first_name'] + ' ' + item['address'][i]['last_name'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
            }

            $('select[name=\'payment_address\']').html(html);
            $('select[name=\'shipping_address\']').html(html);

			$('select[name=\'payment_address\']').trigger('change');
			$('select[name=\'shipping_address\']').trigger('change');
        }
    });
	$('#button-customer').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_customer&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-customer').button('loading');
            },
            complete: function() {
                $('#button-customer').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-' + i.replace('_', '-'));

                        if (element.parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
                } else {
				
					$.ajax({
						url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=cart_add&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
						type: 'post',
						data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\']'),
						dataType: 'json',
						beforeSend: function() {
							$('#button-product-add').button('loading');
						},
						complete: function() {
							$('#button-product-add').button('reset');
						},
						success: function(json) {
							$('.alert, .text-danger').remove();
							$('.form-group').removeClass('has-error');
							if (json['error'] && json['error']['warning']) {
								$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
					$.ajax({
						url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=add_voucher&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
						type: 'post',
						data: $('#cart input[name^=\'voucher\'][type=\'text\'], #cart input[name^=\'voucher\'][type=\'hidden\'], #cart input[name^=\'voucher\'][type=\'radio\']:checked, #cart input[name^=\'voucher\'][type=\'checkbox\']:checked, #cart select[name^=\'voucher\'], #cart textarea[name^=\'voucher\']'),
						dataType: 'json',
						beforeSend: function() {
							$('#button-voucher-add').button('loading');
						},
						complete: function() {
							$('#button-voucher-add').button('reset');
						},
						success: function(json) {
							$('.alert, .text-danger').remove();
							$('.form-group').removeClass('has-error');
							if (json['error'] && json['error']['warning']) {
								$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');

                    $('a[href=\'#tab-cart\']').tab('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
	$('input[name=\'product\']').autofill({
        'source': function(request, response) {
            $.ajax({
                url:  script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_product&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['product_id'],
                            model: item['model'],
                            option: item['option'],
                            price: item['price']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'product\']').val(item['label']);
            $('input[name=\'product_id\']').val(item['value']);

            if (item['option'] != '') {
                html = '<fieldset>';
                html += '  <legend>{LANGE.entry_option}</legend>';

                for (i = 0; i < item['option'].length; i++) {
                    option = item['option'][i];

                    if (option['type'] == 'select') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20">';
                        html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class</selectontrol">';
                        html += '      <option value=""> {LANG.text_select} </option>';

                        for (j = 0; j < option['product_option_value'].length; j++) {
                            option_value = option['product_option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                        }

                        html += '    </select>';
                        html += '  </div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'radio') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20">';
                        html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control input-sm">';
                        html += '      <option value=""> {LANG.text_select} </option>';

                        for (j = 0; j < option['product_option_value'].length; j++) {
                            option_value = option['product_option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                        }

                        html += '    </select>';
                        html += '  </div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'checkbox') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20">';
                        html += '    <div id="input-option' + option['product_option_id'] + '">';

                        for (j = 0; j < option['product_option_value'].length; j++) {
                            option_value = option['product_option_value'][j];

                            html += '<div class="checkbox">';

                            html += '  <label><input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" /> ' + option_value['name'];

                            if (option_value['price']) {
                                html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '  </label>';
                            html += '</div>';
                        }

                        html += '    </div>';
                        html += '  </div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'image') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20">';
                        html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control input-sm">';
                        html += '      <option value=""> {LANG.text_select} </option>';

                        for (j = 0; j < option['product_option_value'].length; j++) {
                            option_value = option['product_option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                        }

                        html += '    </select>';
                        html += '  </div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'text') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" class="form-control input-sm" /></div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'textarea') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="c</label control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20"><textarea name="option[' + option['product_option_id'] + ']" rows="5" id="input-option' + option['product_option_id'] + '" class="form-control input-sm">' + option['value'] + '</textarea></div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'file') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20">';
                        html += '    <button type="button" id="button-upload' + option['product_option_id'] + '" data-loading-text="{LANG.text_loading}" class="btn btn-default btn-sm"><i class="fa fa-upload"></i> Upload</button>';
                        html += '    <input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" />';
                        html += '  </div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'date') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-3"><div class="input-group date"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-format="YYYY-MM-DD" id="input-option' + option['product_option_id'] + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'datetime') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20"><div class="input-group datetime"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-format="YYYY-MM-DD HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
                        html += '</div>';
                    }

                    if (option['type'] == 'time') {
                        html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
                        html += '  <label class="col-sm-4 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
                        html += '  <div class="col-sm-20"><div class="input-group time"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-format="HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
                        html += '</div>';
                    }
                }

                html += '</fieldset>';

                $('#option').html(html);

                //$('.date').datetimepicker({
                //    pickTime: false
                //});

               // $('.datetime').datetimepicker({
                 //   pickDate: true,
                //    pickTime: true
                //});

                //$('.time').datetimepicker({
                //    pickDate: false
                //});
            } else {
                $('#option').html('');
            }
        }
    });
	
	$('#button-product-add').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=cart_add&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('#tab-product input[name=\'product_id\'], #tab-product input[name=\'quantity\'], #tab-product input[name^=\'option\'][type=\'text\'], #tab-product input[name^=\'option\'][type=\'hidden\'], #tab-product input[name^=\'option\'][type=\'radio\']:checked, #tab-product input[name^=\'option\'][type=\'checkbox\']:checked, #tab-product select[name^=\'option\'], #tab-product textarea[name^=\'option\']'),dataType: 'json',
            beforeSend: function() {
                $('#button-product-add').button('loading');
            },
            complete: function() {
                $('#button-product-add').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
                    }

                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                $(element).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['store']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['store'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
                } else {
					
				    $('#input-product').val('');
                    $('input[name="product_id"]').val(0);
                
                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
	// Voucher
    $('#button-voucher-add').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=add_voucher&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('#tab-voucher input[type=\'text\'], #tab-voucher input[type=\'hidden\'], #tab-voucher input[type=\'radio\']:checked, #tab-voucher input[type=\'checkbox\']:checked, #tab-voucher select, #tab-voucher textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-voucher-add').button('loading');
            },
            complete: function() {
                $('#button-voucher-add').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-' + i.replace('_', '-'));

                        if (element.parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
                } else {
                    $('input[name=\'from_name\']').attr('value', '');
                    $('input[name=\'from_email\']').attr('value', '');
                    $('input[name=\'to_name\']').attr('value', '');
                    $('input[name=\'to_email\']').attr('value', '');
                    $('textarea[name=\'message\']').attr('value', '');
                    $('input[name=\'amount\']').attr('value', '1');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#tab-cart').delegate('.btn-danger', 'click', function() {
        var node = this;

        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=cart_remove&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: 'key=' + encodeURIComponent(this.value),
            dataType: 'json',
            beforeSend: function() {
                $(node).button('loading');
            },
            complete: function() {
                $(node).button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                // Check for errors
                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');
                } else {
                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#button-cart').on('click', function() {
        $('a[href=\'#tab-payment\']').tab('show');
    });
	// Payment Address
    $('select[name=\'payment_address\']').on('change', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_address&address_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'payment_address\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('#tab-payment .fa-spin').remove();
            },
            success: function(json) {
                // Reset all fields
                $('#tab-payment input[type=\'text\'], #tab-payment input[type=\'text\'], #tab-payment textarea').val('');
                $('#tab-payment select option').not('#tab-payment select[name=\'payment_address\']').removeAttr('selected');
                $('#tab-payment input[type=\'checkbox\'], #tab-payment input[type=\'radio\']').removeAttr('checked');

                $('#tab-payment input[name=\'first_name\']').val(json['first_name']);
                $('#tab-payment input[name=\'last_name\']').val(json['first_name']);
                $('#tab-payment input[name=\'company\']').val(json['company']);
                $('#tab-payment input[name=\'address_1\']').val(json['address_1']);
                $('#tab-payment input[name=\'address_2\']').val(json['address_2']);
                $('#tab-payment input[name=\'city\']').val(json['city']);
                $('#tab-payment input[name=\'postcode\']').val(json['postcode']);
                $('#tab-payment select[name=\'country_id\']').val(json['country_id']);

                payment_zone_id = json['zone_id'];

                for (i in json['custom_field']) {
                    $('#tab-payment select[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
                    $('#tab-payment textarea[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
                    $('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(json['custom_field'][i]);
                    $('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(json['custom_field'][i]);
                    $('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);
                    $('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);

                    if (json['custom_field'][i] instanceof Array) {
                        for (j = 0; j < json['custom_field'][i].length; j++) {
                            $('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i][j] + '\']').prop('checked', true);
                        }
                    }
                }

                $('#tab-payment select[name=\'country_id\']').trigger('change');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    var payment_zone_id = '{DATA.payment_zone_id}';

    $('#tab-payment select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_zone&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#tab-payment select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('#tab-payment .fa-spin').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#tab-payment input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('#tab-payment input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""> --- Please Select --- </option>';

                if (json['zone']) {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == payment_zone_id) {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"> --- None --- </option>';
                }

                $('#tab-payment select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#tab-payment select[name=\'country_id\']').trigger('change');

    $('#button-payment-address').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_payment_address&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-address').button('loading');
            },
            complete: function() {
                $('#button-payment-address').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                // Check for errors
                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-payment-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
                } else {
                    // Payment Methods
                    $.ajax({
                        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_payment_methods&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#button-payment-address i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                            $('#button-payment-address').prop('disabled', true);
                        },
                        complete: function() {
                            $('#button-payment-address i').replaceWith('<i class="fa fa-arrow-right"></i>');
                            $('#button-payment-address').prop('disabled', false);
                        },
                        success: function(json) {
                            if (json['error']) {
                                $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');
                            } else {
                                html = '<option value=""> --- {LANG.text_select} --- </option>';

                                if (json['payment_methods']) {
                                    for (i in json['payment_methods']) {
                                        if (json['payment_methods'][i]['code'] == $('select[name=\'payment_method\'] option:selected').val()) {
                                            html += '<option value="' + json['payment_methods'][i]['code'] + '" selected="selected">' + json['payment_methods'][i]['title'] + '</option>';
                                        } else {
                                            html += '<option value="' + json['payment_methods'][i]['code'] + '">' + json['payment_methods'][i]['title'] + '</option>';
                                        }
                                    }
                                }

                                $('select[name=\'payment_method\']').html(html);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');

                    // If shipping required got to shipping tab else total tabs
                    if ($('select[name=\'shipping_method\']').prop('disabled')) {
                        $('a[href=\'#tab-total\']').tab('show');
                    } else {
                        $('a[href=\'#tab-shipping\']').tab('show');
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Shipping Address
    $('select[name=\'shipping_address\']').on('change', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_address&address_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'shipping_address\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('#tab-shipping .fa-spin').remove();
            },
            success: function(json) {
                // Reset all fields
                $('#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'text\'], #tab-shipping textarea').val('');
                $('#tab-shipping select option').not('#tab-shipping select[name=\'shipping_address\']').removeAttr('selected');
                $('#tab-shipping input[type=\'checkbox\'], #tab-shipping input[type=\'radio\']').removeAttr('checked');

                $('#tab-shipping input[name=\'first_name\']').val(json['first_name']);
                $('#tab-shipping input[name=\'last_name\']').val(json['last_name']);
                $('#tab-shipping input[name=\'company\']').val(json['company']);
                $('#tab-shipping input[name=\'address_1\']').val(json['address_1']);
                $('#tab-shipping input[name=\'address_2\']').val(json['address_2']);
                $('#tab-shipping input[name=\'city\']').val(json['city']);
                $('#tab-shipping input[name=\'postcode\']').val(json['postcode']);
                $('#tab-shipping select[name=\'country_id\']').val(json['country_id']);

                shipping_zone_id = json['zone_id'];

                for (i in json['custom_field']) {
                    $('#tab-shipping select[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
                    $('#tab-shipping textarea[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
                    $('#tab-shipping input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(json['custom_field'][i]);
                    $('#tab-shipping input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(json['custom_field'][i]);
                    $('#tab-shipping input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);
                    $('#tab-shipping input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);

                    if (json['custom_field'][i] instanceof Array) {
                        for (j = 0; j < json['custom_field'][i].length; j++) {
                            $('#tab-shipping input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i][j] + '\']').prop('checked', true);
                        }
                    }
                }

                $('#tab-shipping select[name=\'country_id\']').trigger('change');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    var shipping_zone_id = '{DATA.shipping_zone_id}';

    $('#tab-shipping select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_zone&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#tab-shipping select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('#tab-shipping .fa-spin').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#tab-shipping input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('#tab-shipping input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""> {LANG.text_select} </option>';

                if (json['zone']) {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == shipping_zone_id) {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"> {LANG.text_none} </option>';
                }

                $('#tab-shipping select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#tab-shipping select[name=\'country_id\']').trigger('change');

    $('#button-shipping-address').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_shipping_address&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-address').button('loading');
            },
            complete: function() {
                $('#button-shipping-address').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                // Check for errors
                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <i class="fa fa-times"></i></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-shipping-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
                } else {
                    // Shipping Methods
                    $.ajax({
                        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_shipping_methods&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#button-shipping-address i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                            $('#button-shipping-address').prop('disabled', true);
                        },
                        complete: function() {
                            $('#button-shipping-address i').replaceWith('<i class="fa fa-arrow-right"></i>');
                            $('#button-shipping-address').prop('disabled', false);
                        },
                        success: function(json) {
                            if (json['error']) {
                                $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');
                            } else {
                                // Shipping Methods
                                html = '<option value=""> --- {LA.text_select} --- </option>';

                                if (json['shipping_methods']) {
                                    for (i in json['shipping_methods']) {
                                        html += '<optgroup label="' + json['shipping_methods'][i]['title'] + '">';

                                        if (!json['shipping_methods'][i]['error']) {
                                            for (j in json['shipping_methods'][i]['quote']) {
                                                if (json['shipping_methods'][i]['quote'][j]['code'] == $('select[name=\'shipping_method\'] option:selected').val()) {
                                                    html += '<option value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" selected="selected">' + json['shipping_methods'][i]['quote'][j]['title'] + '</option>';
                                                } else {
                                                    html += '<option value="' + json['shipping_methods'][i]['quote'][j]['code'] + '">' + json['shipping_methods'][i]['quote'][j]['title'] + '</option>';
                                                }
                                            }
                                        } else {
                                            html += '<option value="" style="color: #F00;" disabled="disabled">' + json['shipping_method'][i]['error'] + '</option>';
                                        }

                                        html += '</optgroup>';
                                    }
                                }

                                $('select[name=\'shipping_method\']').html(html);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');

                    $('a[href=\'#tab-total\']').tab('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Shipping Method
    $('#button-shipping-method').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_shipping_method&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: 'shipping_method=' + $('select[name=\'shipping_method\'] option:selected').val(),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-method').button('loading');
            },
            complete: function() {
                $('#button-shipping-method').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');

                    // Highlight any found errors
                    $('select[name=\'shipping_method\']').parent().parent().parent().addClass('has-error');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Payment Method
    $('#button-payment-method').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_payment_method&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: 'payment_method=' + $('select[name=\'payment_method\'] option:selected').val(),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-method').button('loading');
            },
            complete: function() {
                $('#button-payment-method').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button</button"close" data-dismiss="alert">&times;</button></div>');

                    // Highlight any found errors
                    $('select[name=\'payment_method\']').parent().parent().parent().addClass('has-error');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Coupon
    $('#button-coupon').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_coupon&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('input[name=\'coupon\']'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-coupon').button('loading');
            },
            complete: function() {
                $('#button-coupon').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');

                    // Highlight any found errors
                    $('input[name=\'coupon\']').parent().parent().parent().addClass('has-error');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Voucher
    $('#button-voucher').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_voucher&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('input[name=\'voucher\']'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-voucher').button('loading');
            },
            complete: function() {
                $('#button-voucher').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');

                    // Highlight any found errors
                    $('input[name=\'voucher\']').parent().parent().parent().addClass('has-error');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Reward
    $('#button-reward').on('click', function() {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=set_reward&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
            type: 'post',
            data: $('input[name=\'reward\']'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward').button('loading');
            },
            complete: function() {
                $('#button-reward').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');

                    // Highlight any found errors
                    $('input[name=\'reward\']').parent().parent().parent().addClass('has-error');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');

                    // Refresh products, vouchers and totals
                    $('#button-refresh').trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Affiliate
    $('input[name=\'affiliate\']').autofill({
        'source': function(request, response) {
            $.ajax({
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=get_affiliate&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        affiliate_id: 0,
                        name: ' --- None --- '
                    });

                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['affiliate_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'affiliate\']').val(item['label']);
            $('input[name=\'affiliate_id\']').val(item['value']);
        }
    });

    // Checkout
    $('#button-save').on('click', function() {
        var order_id = $('input[name=\'order_id\']').val();

        if (order_id == 0) {
            var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=order_add&store_id=' + $('select[name=\'store_id\'] option:selected').val();
        } else {
            var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order_edit&action=order_edit&store_id=' + $('select[name=\'store_id\'] option:selected').val() + '&order_id=' + order_id;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: $('#tab-total select[name=\'order_status_id\'], #tab-total select, #tab-total textarea[name=\'comment\'], #tab-total input[name=\'affiliate_id\']'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-save').button('loading');
            },
            complete: function() {
                $('#button-save').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['error']) {
                    $('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <i class="fa fa-times"></i></div>');
                }

                if (json['success']) {
                    $('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <i class="fa fa-times"></i></div>');
                }

                if (json['order_id']) {
                    $('input[name=\'order_id\']').val(json['order_id']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#productcontent').delegate('button[id^=\'button-upload\'], button[id^=\'button-custom-field\'], button[id^=\'button-payment-custom-field\'], button[id^=\'button-shipping-custom-field\']', 'click', function() {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        $('#form-upload input[name=\'file\']').on('change', function() {
            $.ajax({
                url: 'index.php?route=tool/upload/upload',
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
<!-- END: main -->