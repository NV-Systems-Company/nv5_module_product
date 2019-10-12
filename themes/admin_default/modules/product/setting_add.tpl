<!-- BEGIN: main -->

{AddMenu}
<div id="productcontent">
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
        <div class="pull-right">
          <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i> </button>
          <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a> </div>
        <div style="clear:both"></div>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">{LANG.tab_general}</a></li>
            <li><a href="#tab-store" data-toggle="tab">{LANG.tab_store}</a></li>
            <li><a href="#tab-local" data-toggle="tab">{LANG.tab_local}</a></li>
            <li><a href="#tab-product" data-toggle="tab">{LANG.tab_product}</a></li>
            <li><a href="#tab-customers" data-toggle="tab">{LANG.tab_customer}</a></li>
            <li><a href="#tab-vouchers" data-toggle="tab">{LANG.tab_voucher}</a></li>
            <li><a href="#tab-stock" data-toggle="tab">{LANG.tab_stock}</a></li>
            <li><a href="#tab-checkout" data-toggle="tab">{LANG.tab_checkout}</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-name">{LANGE.entry_name}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_name" value="{DATA.config_name}" placeholder="{LANGE.entry_name}" id="input-name" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-owner">{LANGE.entry_owner}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_owner" value="{DATA.config_owner}" placeholder="{LANGE.entry_owner}" id="input-owner" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-address">{LANGE.entry_address}</label>
                <div class="col-sm-20">
                  <textarea name="config_address" placeholder="{LANGE.entry_address}" rows="5" id="input-address" class="form-control input-sm">{DATA.config_address}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#tab-general" title="{LANGE.help_geocode}">Geocode</span> </label>
                <div class="col-sm-20">
                  <input type="text" name="config_geocode" value="{DATA.config_geocode}" placeholder="{LANGE.entry_geocode}" id="input-geocode" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-email">{LANGE.entry_email}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_email" value="{DATA.config_email}" placeholder="{LANGE.entry_email}" id="input-email" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-telephone">{LANGE.entry_telephone}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_telephone" value="{DATA.config_telephone}" placeholder="{LANGE.entry_telephone}" id="input-telephone" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-fax">{LANGE.entry_fax}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_fax" value="{DATA.config_fax}" placeholder="{LANGE.entry_fax}" id="input-fax" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-image">{LANGE.entry_image}</label>
                <div class="col-sm-20">
                  <input class="form-control input-sm" style="width:400px; margin-right: 5px; display: inline-block" placeholder="{LANGE.entry_image}" type="text" name="config_image" id="config_image" value="{DATA.config_image}"/>
                  <input type="button" value="{LANG.browse_image}" name="selectimg" class="btn btn-info btn-sm" style="margin-right: 5px" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-open"><span data-toggle="tooltip" data-container="#tab-general"  title="{LANGE.help_open}">{LANGE.entry_open}</span> </label>
                <div class="col-sm-20">
                  <textarea name="config_open" rows="5" placeholder="{LANGE.entry_open}" id="input-open" class="form-control input-sm">{DATA.config_open}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-comment"><span data-toggle="tooltip" data-container="#tab-general"  title="{LANGE.help_comment}">{LANGE.entry_comment}</span> </label>
                <div class="col-sm-20">
                  <textarea name="config_comment" rows="5" placeholder="{DATA.config_comment}" id="input-comment" class="form-control input-sm">{DATA.config_comment}</textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-store">
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-meta-title">{LANGE.entry_meta_title}</label>
                <div class="col-sm-20">
                  <input type="text" name="config_meta_title" value="{DATA.config_meta_title}" placeholder="{LANGE.entry_meta_title}" id="input-meta-title" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-meta-description">{LANGE.entry_meta_description}</label>
                <div class="col-sm-20">
                  <textarea name="config_meta_description" rows="5" placeholder="{LANGE.entry_meta_description}" id="input-meta-description" class="form-control input-sm">{DATA.config_meta_description}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-meta-keyword">{LANGE.entry_meta_keyword}</label>
                <div class="col-sm-20">
                  <textarea name="config_meta_keyword" rows="5" placeholder="{LANGE.entry_meta_keyword}" id="input-meta-keyword" class="form-control input-sm">{DATA.config_meta_keyword}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-template">{LANGE.entry_template}</label>
                <div class="col-sm-20">
                  <select name="config_template" id="input-template" class="form-control input-sm">
                    <!-- BEGIN: template -->
                    <option value="{TEMPLATE.key}" {TEMPLATE.selected}>{TEMPLATE.name}</option>
                    <!-- END: template -->
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-mobile-template">{LANGE.entry_mobile_template}</label>
                <div class="col-sm-20">
                  <select name="config_mobile_template" id="input-mobile-template" class="form-control input-sm">
                    <option value="">{LANG.default}</option>
                    <!-- BEGIN: mobile_template -->
                    <option value="{MOBILE_TEMPLATE.key}" {MOBILE_TEMPLATE.selected}>{MOBILE_TEMPLATE.name}</option>
                    <!-- END: mobile_template -->
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-local">
              <div class="tab-pane active" id="tab-local">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">{LANGE.entry_country}</label>
                  <div class="col-sm-20">
                    <select name="config_country_id" id="input-country" class="form-control input-sm">
                      <!-- BEGIN: country -->
                      <option value="{COUNTRY.country_id}" {COUNTRY.selected} >{COUNTRY.name}</option>
                      <!-- END: country -->
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zone">{LANGE.entry_zone}</label>
                  <div class="col-sm-20">
                    <select name="config_zone_id" id="input-zone" class="form-control input-sm">
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label"> {LANGE.entry_language} </label>
                  <div class="col-sm-20">
                    <select name="config_language_id" class="form-control input-sm">
                      <!-- BEGIN: language -->
                      <option value="{LANGUAGE.language_id}" {LANGUAGE.selected}>{LANGUAGE.name}</option>
                      <!-- END: language -->
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label"><span data-toggle="tooltip"  title="{LANGE.help_currency}"> {LANGE.entry_currency}</span></label>
                  <div class="col-sm-20">
                    <select class="form-control input-sm" name="config_currency">
                      <!-- BEGIN: money_loop -->
                      <option value="{DATAMONEY.value}"{DATAMONEY.selected}>{DATAMONEY.title}</option>
                      <!-- END: money_loop -->
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label"><span data-toggle="tooltip"  title="Set your store to automatically update currencies daily.">Auto Update Currency</span> </label>
                  <div class="col-sm-20"> 
                    <!-- BEGIN: currency_auto -->
                    <label class="radio-inline">
                      <input type="radio" name="config_currency_auto" value="{CURRENCY_AUTO.key}" {CURRENCY_AUTO.checked}>
                      {CURRENCY_AUTO.name} </label>
                    <!-- END: currency_auto --> 
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-length-class">{LANGE.entry_length_class}</label>
                  <div class="col-sm-20">
                    <select name="config_length_class_id" id="input-length-class" class="form-control input-sm">
                      <!-- BEGIN: length_class -->
                      <option value="{LENGTH.length_class_id}" {LENGTH.selected}>{LENGTH.name}</option>
                      <!-- END: length_class -->
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-weight-class">{LANGE.entry_weight_class}</label>
                  <div class="col-sm-20">
                    <select name="config_weight_class_id" id="input-weight-class" class="form-control input-sm">
                      <!-- BEGIN: weight_class -->
                      <option value="{WEIGHT.weight_class_id}" {WEIGHT.selected}>{WEIGHT.name}</option>
                      <!-- END: weight_class -->
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-product">
              <div class="form-group">
                <label class="col-sm-4 control-label">{LANGE.entry_home_view}</label>
                <div class="col-sm-20">
                  <select class="form-control input-sm" name="config_home_view">
                    <!-- BEGIN: home_view_loop -->
                    <option value="{type_view}"{view_selected}>{name_view}</option>
                    <!-- END: home_view_loop -->
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.entry_per_note_home}">{LANGE.entry_per_page}</span></label>
                <div class="col-sm-20">
                  <input class="form-control input-sm" type="text" value="{DATA.config_per_page}" name="config_per_page" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.entry_per_note}">{LANGE.entry_per_row}</span></label>
                <div class="col-sm-20">
                  <select class="form-control input-sm" name="config_per_row">
                    <!-- BEGIN: per_row -->
                    <option value="{PER_ROW.value}" {PER_ROW.selected}>{PER_ROW.value}</option>
                    <!-- END: per_row -->
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">{LANGE.entry_active_order}</label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_active_order" {ck_active_order} />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">{LANGE.entry_active_price}</label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_active_price" {ck_active_price} id="active_price" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.entry_active_order_number_note}">{LANGE.entry_active_order_number}</span></label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_active_order_number" {ck_active_order_number} id="active_order_number" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.entry_active_payment_note}">{LANGE.entry_active_payment}</span></label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_active_payment" {ck_active_payment} id="active_payment" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_compare} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_show_compare" {ck_compare} />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_displays} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_show_displays" {ck_show_displays} />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.entry_format_order_id_note}"> {LANGE.entry_format_code_id}</span> </label>
                <div class="col-sm-20">
                  <input class="form-control input-sm" type="text" value="{DATA.config_format_code_id}" style="width: 100px;" name="config_format_code_id" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_show_model} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_show_model" {ck_show_model} id="show_model" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_active_wishlist} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_active_wishlist" {ck_active_wishlist} id="active_wishlist" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_tags_alias} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_tags_alias"{TAGS_ALIAS}/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_auto_tags} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_auto_tags"{AUTO_TAGS}/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> {LANGE.entry_tags_remind} </label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_tags_remind"{TAGS_REMIND}/>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-customers">
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-customer-group"><span data-toggle="tooltip"  title="{LANGE.help_customer_group}">{LANGE.entry_customer_group}</span> </label>
                <div class="col-sm-20">
                  <select name="config_customer_group_id" id="input-customer-group" class="form-control input-sm">
                    <!-- BEGIN: customer_group -->
                    
                    <option value="{CGROUP.customer_group_id}"{CGROUP.selected}>{CGROUP.name}</option>
                    <!-- END: customer_group -->
                    
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_customer_price}">{LANGE.entry_customer_price}</span></label>
                <div class="col-sm-20"> 
                  <!-- BEGIN: customer_price -->
                  <label class="radio-inline">
                    <input type="radio" name="config_customer_price" value="{CUSTOMER_PRICE.key}" {CUSTOMER_PRICE.checked}>
                    {CUSTOMER_PRICE.name} </label>
                  <!-- END: customer_price --> 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_customer_group_display}">{LANGE.entry_customer_group_display}</span> </label>
                <div class="col-sm-20"> 
                  <!-- BEGIN: customer_group_display -->
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="config_customer_group_display[]" value="{DISPLAY.customer_group_id}" {DISPLAY.checked}>
                      {DISPLAY.name} </label>
                  </div>
                  <!-- END: customer_group_display --> 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_account}">{LANGE.entry_account}</span> </label>
                <div class="col-sm-20">
                  <select name="config_account_id" id="input-account-id" class="form-control input-sm">
                    <!-- BEGIN: account -->
                    <option value="{ACCOUNT.key}"{ACCOUNT.selected}>{ACCOUNT.name}</option>
                    <!-- END: account -->
                  </select>
                </div>
              </div>
 
            </div>
            <div class="tab-pane" id="tab-vouchers">
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-voucher-min"><span data-toggle="tooltip" title="{LANGE.help_voucher_min}">{LANGE.entry_voucher_min}</span> </label>
                <div class="col-sm-20">
                  <input type="text" name="config_voucher_min" value="{DATA.config_voucher_min}" placeholder="{LANGE.entry_voucher_min}" id="input-voucher-min" class="form-control input-sm">
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-4 control-label" for="input-voucher-max"><span data-toggle="tooltip" title="{LANGE.help_voucher_max}">{LANGE.entry_voucher_max}</span> </label>
                <div class="col-sm-20">
                  <input type="text" name="config_voucher_max" value="{DATA.config_voucher_max}" placeholder="{LANGE.entry_voucher_max}" id="input-voucher-max" class="form-control input-sm">
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-stock">
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_stock_display}">{LANG.display_stock}</span> </label>
                <div class="col-sm-20"> 
                  <!-- BEGIN: stock_display -->
                  <label class="radio-inline">
                    <input type="radio" name="config_stock_display" value="{STOCK_DISPLAY.key}" {STOCK_DISPLAY.checked}>
                    {STOCK_DISPLAY.name} </label>
                  <!-- END: stock_display --> 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_stock_warning}">{LANGE.entry_stock_warning}</span> </label>
                <div class="col-sm-20"> 
                  
                  <!-- BEGIN: stock_warning -->
                  <label class="radio-inline">
                    <input type="radio" name="config_stock_warning" value="{STOCK_WARNING.key}" {STOCK_WARNING.checked}> {STOCK_WARNING.name} 
				  </label>
                  <!-- END: stock_warning --> 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_stock_checkout}">{LANGE.entry_stock_checkout}</span> </label>
                <div class="col-sm-20"> 
                  
                  <!-- BEGIN: stock_checkout -->
                  <label class="radio-inline">
                    <input type="radio" name="config_stock_checkout" value="{STOCK_CHECKOUT.key}" {STOCK_CHECKOUT.checked}>
                    {STOCK_CHECKOUT.name} </label>
                  <!-- END: stock_checkout --> 
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-checkout">
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.format_order_id_note}"> {LANGE.entry_format_code_id}</span> </label>
                <div class="col-sm-20">
                  <input class="form-control input-sm" type="text" value="{DATA.config_format_order_id}" style="width: 100px;" name="config_format_order_id" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_cart_weight}">{LANGE.entry_cart_weight}</span></label>
                <div class="col-sm-20">
					<!-- BEGIN: cart_weight -->
					<label class="radio-inline"><input type="radio" name="config_cart_weight" value="{CART_WEIGHT.key}" {CART_WEIGHT.checked}>{CART_WEIGHT.name}</label>
					<!-- END: cart_weight -->
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"> <span data-toggle="tooltip" title="{LANGE.help_checkout_guest}">{LANGE.entry_checkout_guest}</span></label>
                <div class="col-sm-20">
                  <input type="checkbox" value="1" name="config_checkout_guest" {ck_checkout_guest} />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-checkout"><span data-toggle="tooltip" title="{LANGE.help_checkout}.">{LANGE.entry_checkout}</span> </label>
                <div class="col-sm-20">
                  <select name="config_checkout_id" id="input-checkout" class="form-control input-sm">
                    <option value="0"> --- None --- </option>
                    <!-- BEGIN: information -->
                    <option value="{INFO.key}" {INFO.selected}>{INFO.name}</option>
                    <!-- END: information -->
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip"  title="{LANGE.help_order_status}" >{LANGE.entry_order_status}</span></label>
                <div class="col-sm-20">
                  <select name="config_order_status_id" id="input-order-status" class="form-control input-sm">
                    <!-- BEGIN: order_status -->
                    <option value="{ORDER_STATUS.key}" {ORDER_STATUS.selected}>{ORDER_STATUS.name}</option>
                    <!-- END: order_status -->
                    
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-process-status"><span data-toggle="tooltip" title="{LANGE.help_processing_status}">{LANGE.entry_processing_status}</span> </label>
                <div class="col-sm-20">
                  <div class="well well-sm" style="height: 150px; overflow: auto;"> 
                    <!-- BEGIN: processing_status -->
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="config_processing_status[]" value="{PSTATUS.order_status_id}" {PSTATUS.checked}>
                        {PSTATUS.name} </label>
                    </div>
                    <!-- END: processing_status --> 
                    
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-complete-status"><span data-toggle="tooltip" title="{LANGE.help_complete_status}">{LANGE.entry_complete_status}</span> </label>
                <div class="col-sm-20">
                  <div class="well well-sm" style="height: 150px; overflow: auto;"> 
                    <!-- BEGIN: complete_status -->
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="config_complete_status[]" value="{CSTATUS.order_status_id}" {CSTATUS.checked}>
                        {CSTATUS.name} 
					  </label>
                    </div>
                    <!-- END: complete_status --> 
                  </div>
                </div>
              </div>
			  <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-fraud-status"><span data-toggle="tooltip" title="Set the order status when a customer is suspected of trying to alter the order payment details or use a coupon, gift voucher or reward points that have already been used.">Fraud Order Status</span></label>
                  <div class="col-sm-20">
                    <select name="config_fraud_status_id" id="input-fraud-status" class="form-control">
                        <!-- BEGIN: fraud_status-->
						<option value="{FRAUD.key}" {FRAUD.selected}>{FRAUD.name}</option>
						<!-- END: fraud_status-->
                    </select>
                  </div>
                </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_order_mail}">{LANGE.entry_order_mail}</span> </label>
                <div class="col-sm-20"> 
                  <!-- BEGIN: order_mail -->
                  <label class="radio-inline">
                    <input type="radio" name="config_order_mail" value="{ORDER_EMAIL.key}" {ORDER_EMAIL.checked}>
                    {ORDER_EMAIL.name} </label>
                  <!-- END: order_mail --> 
                </div>
              </div>
            </div>
          </div>
          <div align="center">
            <input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}" name="Submit1" />
            <input type="hidden" value="1" name="save">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  
 
<script type="text/javascript">
	$("input[name=selectimg]").click(function() {
		var area = "config_image";
		var path = "{CURRENT}";
		var currentpath = "{CURRENT}";
		var type = "image";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
    $('select[name=\'config_country_id\']').on('change', function() {
        $.ajax({
            url: '{GET_LINK}&action=zone&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                $('.fa-spin').remove();

                html = '<option value=""> --- Chọn quốc gia --- </option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '{DATA.config_zone_id}') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"> --- Chọn --- </option>';
                }

                $('select[name=\'config_zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'config_country_id\']').trigger('change');
 
</script> 

<!-- BEGIN: main -->