<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog http://dangdinhtu.com
 * @Developers http://developers.dangdinhtu.com/
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Mon, 20 Oct 2014 14:00:59 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$getCountries = getCountries();
$getOrderStatus = getOrderStatus();
$getLengthClass = getLengthClass();
$getWeightClass = getWeightClass();
$getInformation = getInformation();
$getCustomerGroup = getCustomerGroup();

if( ACTION_METHOD == 'zone' )
{

	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );

	$info = $getCountries[$country_id];

	$sql = 'SELECT zone_id, code, status, name FROM ' . NV_USERS_GLOBALTABLE . '_zone WHERE country_id=' . $country_id;
	$result = $db->query( $sql );

	while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
	{
		$json['zone'][] = array(
			'code' => $code,
			'country_id' => $country_id,
			'status' => $status,
			'name' => nv_htmlspecialchars( $name ),
			'zone_id' => $_zone_id );

	}

	nv_jsonOutput( $json );
}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$store_id = $nv_Request->get_title( 'store_id', 'get,post', '' );
	
	// if( $store_id == 0 && is_numeric( $store_id ) )
	// {
		// $lang_ext = getLangAdmin( $op, 'setting' );
	
	// }else
	// {
		
	// }
	$lang_ext = getLangAdmin( 'setting', 'setting' );
	if( defined( 'NV_EDITOR' ) )
	{
		require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
	}

	$currencies_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/currencies.ini', true );

	$active_payment_old = 0;
	
	
	$config_store = $ProductGeneral->getSetting( 'config', $store_id );
 
	if( is_numeric( $store_id ) )
	{
		$caption = $lang_ext['text_edit'];
	}else
	{
		$caption = $lang_ext['text_add'];
	}
 
	$error = array();

	if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
	{
		// General
		$config_store['config_name'] = $nv_Request->get_string( 'config_name', 'post', '' );
		$config_store['config_owner'] = $nv_Request->get_string( 'config_owner', 'post', '' );
		$config_store['config_address'] = $nv_Request->get_string( 'config_address', 'post', '' );
		$config_store['config_geocode'] = $nv_Request->get_string( 'config_geocode', 'post', '' );
		$config_store['config_email'] = $nv_Request->get_string( 'config_email', 'post', '' );
		$config_store['config_telephone'] = $nv_Request->get_string( 'config_telephone', 'post', '' );
		$config_store['config_fax'] = $nv_Request->get_string( 'config_fax', 'post', '' );
		$config_store['config_image'] = $nv_Request->get_string( 'config_image', 'post', '' );
		$config_store['config_open'] = $nv_Request->get_string( 'config_open', 'post', '' );
		$config_store['config_comment'] = $nv_Request->get_string( 'config_comment', 'post', '' );
		
		// Store
		$config_store['config_meta_title'] = $nv_Request->get_string( 'config_meta_title', 'post', '' );
		$config_store['config_meta_description'] = $nv_Request->get_string( 'config_meta_description', 'post', '' );
		$config_store['config_meta_keyword'] = $nv_Request->get_string( 'config_meta_keyword', 'post', '' );
		$config_store['config_template'] = $nv_Request->get_string( 'config_template', 'post', '' );
		$config_store['config_mobile_template'] = $nv_Request->get_string( 'config_mobile_template', 'post', '' );
		
		//location
		$config_store['config_country_id'] = $nv_Request->get_string( 'config_country_id', 'post', '' );
		$config_store['config_zone_id'] = $nv_Request->get_string( 'config_zone_id', 'post', '' );
		$config_store['config_language_id'] = $nv_Request->get_int( 'config_language_id', 'post', 0 );
		$config_store['config_currency'] = $nv_Request->get_string( 'config_currency', 'post', '' );
		$config_store['config_currency_auto'] = $nv_Request->get_string( 'config_currency_auto', 'post', '' );
		$config_store['config_length_class_id'] = $nv_Request->get_string( 'config_length_class_id', 'post', '' );
		$config_store['config_weight_class_id'] = $nv_Request->get_string( 'config_weight_class_id', 'post', '' );
		
		//product 
		$config_store['config_home_view'] = $nv_Request->get_string( 'config_home_view', 'post', '' );
		$config_store['config_per_page'] = $nv_Request->get_int( 'config_per_page', 'post', 0 );
		$config_store['config_per_row'] = $nv_Request->get_int( 'config_per_row', 'post', 0 );
		$config_store['config_active_order'] = $nv_Request->get_int( 'config_active_order', 'post', 0 );
		$config_store['config_active_price'] = $nv_Request->get_int( 'config_active_price', 'post', 0 );
		$config_store['config_active_order_number'] = $nv_Request->get_int( 'config_active_order_number', 'post', 0 );
		$config_store['config_active_payment'] = $nv_Request->get_int( 'config_active_payment', 'post', 0 );
		$config_store['config_show_compare'] = $nv_Request->get_int( 'config_show_compare', 'post', 0 );
		$config_store['config_show_displays'] = $nv_Request->get_int( 'config_show_displays', 'post', 0 );
		$config_store['config_format_code_id'] = $nv_Request->get_string( 'config_format_code_id', 'post', '' );
		$config_store['config_show_model'] = $nv_Request->get_int( 'config_show_model', 'post', 0 );
		$config_store['config_active_wishlist'] = $nv_Request->get_int( 'config_active_wishlist', 'post', 0 );
		$config_store['config_tags_alias'] = $nv_Request->get_int( 'config_tags_alias', 'post', 0 );
		$config_store['config_auto_tags'] = $nv_Request->get_int( 'config_auto_tags', 'post', 0 );
		$config_store['config_tags_remind'] = $nv_Request->get_int( 'config_tags_remind', 'post', 0 );
		
		//Customer Account
		$config_store['config_customer_group_id'] = $nv_Request->get_int( 'config_customer_group_id', 'post', 0 );
		$config_store['config_customer_group_display'] = array_unique( $nv_Request->get_typed_array( 'config_customer_group_display', 'post', 'int', array() ) );
 		$config_store['config_customer_price'] = $nv_Request->get_int( 'config_customer_price', 'post', 0 );
		$config_store['config_account_id'] = $nv_Request->get_int( 'config_account_id', 'post', 0 );
		
		//Voucher
		$config_store['config_voucher_min'] = $nv_Request->get_int( 'config_voucher_min', 'post', 0 );
		$config_store['config_voucher_max'] = $nv_Request->get_int( 'config_voucher_max', 'post', 0 );
		
		
		//Stock
		$config_store['config_stock_display'] = $nv_Request->get_int( 'config_stock_display', 'post', 0 );
		$config_store['config_stock_warning'] = $nv_Request->get_int( 'config_stock_warning', 'post', 0 );
		$config_store['config_stock_checkout'] = $nv_Request->get_int( 'config_stock_checkout', 'post', 0 );
		
 
		// Checkout
		$config_store['config_format_order_id'] = $nv_Request->get_string( 'config_format_order_id', 'post', '' );
		$config_store['config_checkout_guest'] = $nv_Request->get_int( 'config_checkout_guest', 'post', 0 );
		$config_store['config_order_status_id'] = $nv_Request->get_int( 'config_order_status_id', 'post', 0 );
		$config_store['config_checkout_id'] = $nv_Request->get_int( 'config_checkout_id', 'post', 0 );
		$config_store['config_processing_status'] = array_unique( $nv_Request->get_typed_array( 'config_processing_status', 'post', 'int', array() ) );
		$config_store['config_complete_status'] = array_unique( $nv_Request->get_typed_array( 'config_complete_status', 'post', 'int', array() ) );
		$config_store['config_fraud_status_id'] = $nv_Request->get_int( 'config_fraud_status_id', 'post', 0 );
		$config_store['config_order_mail'] = $nv_Request->get_int( 'config_order_mail', 'post', 0 );
 
		
		if( empty( $error ) )
		{
			$groups = 'config';
			
			$db->query( 'DELETE FROM  ' . TABLE_PRODUCT_NAME . '_setting WHERE store_id = ' . ( int )$store_id . ' AND groups = ' . $db->quote( $groups ) );

			foreach( $config_store as $code => $value )
			{ 
				if( substr( $code, 0, strlen( $groups ) ) == $groups )
				{
					if( ! is_array( $value ) )
					{
 
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_setting SET store_id = ' . ( int )$store_id . ', groups = ' . $db->quote( $groups ) . ', code = ' . $db->quote( $code ) . ', value = ' . $db->quote( $value ) );
					}
					else
					{
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_setting SET store_id = ' . ( int )$store_id . ', groups = ' . $db->quote( $groups ) . ', code = ' . $db->quote( $code ) . ', value = ' . $db->quote( serialize( $value ) ) . ', serialized = 1' );
					}
				}
			}

			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['setting'], 'Setting', $admin_info['userid'] );

			$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.config' );

			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}
	}
 
	$xtpl = new XTemplate( 'setting_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu() );
	$xtpl->assign( 'DATA', $config_store );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'CURRENT', NV_UPLOADS_DIR );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'GET_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting' );
	
	$theme_list = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme'] );
	$theme_mobile_list = nv_scandir( NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile'] );

	foreach( $theme_list as $template )
	{
		$xtpl->assign( 'TEMPLATE', array( 'key' => $template, 'name' => $template, 'selected' => $config_store['config_template'] == $template ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.template' );
	}

	foreach( $theme_mobile_list as $mobile_template )
	{
		$xtpl->assign( 'MOBILE_TEMPLATE', array( 'key' => $mobile_template, 'name' => $mobile_template, 'selected' => $config_store['config_mobile_template'] == $mobile_template ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.mobile_template' );
	}
	
	foreach( $getCountries as $country_id => $_value )
	{
		$xtpl->assign( 'COUNTRY', array(
			'country_id' => $country_id,
			'name' => nv_htmlspecialchars( $_value['name'] ),
			'selected' => ( $country_id == $config_store['config_country_id'] ) ? 'selected="selected"' : '',
			) );
		$xtpl->parse( 'main.country' );
	}
	
	// Số sản phẩm hiển thị trên một dòng
	for( $i = 3; $i <= 4; $i++ )
	{
		$xtpl->assign( 'PER_ROW', array( 'value' => $i, 'selected' => $config_store['config_per_row'] == $i ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.per_row' );
	}

	$check_view = array(
		'view_home_all' => '',
		'view_home_category' => '',
		'view_home_group' => '',
		'view_home_none' => '' );
	$check_view[$config_store['config_home_view']] = 'selected="selected"';

	foreach( $check_view as $type_view => $select )
	{
		$xtpl->assign( 'type_view', $type_view );
		$xtpl->assign( 'view_selected', $select );
		$xtpl->assign( 'name_view', $lang_module[$type_view] );
		$xtpl->parse( 'main.home_view_loop' );
	}

	$select = '';
	for( $i = 5; $i <= 50; $i = $i + 5 )
	{
		$select .= '<option value="' . $i . '"' . ( ( $i == $config_store['config_per_page'] ) ? ' selected="selected"' : '' ) . '>' . $i . '</option>\n';
	}

	$xtpl->assign( 'TAGS_ALIAS', $config_store['config_tags_alias'] ? ' checked="checked"' : '' );
	$xtpl->assign( 'AUTO_TAGS', $config_store['config_auto_tags'] ? ' checked="checked"' : '' );
	$xtpl->assign( 'TAGS_REMIND', $config_store['config_tags_remind'] ? ' checked="checked"' : '' );

	$check = ( $config_store['config_active_order'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_active_order', $check );

	$check = ( $config_store['config_active_price'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_active_price', $check );
 
	$check = ( $config_store['config_active_order_number'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_active_order_number', $check );

	$check = ( $config_store['config_active_payment'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_active_payment', $check );

	$check = ( $config_store['config_checkout_guest'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_checkout_guest', $check );

	$check = ! empty( $config_store['config_show_model'] ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_show_model', $check );

	$check = ( $config_store['config_show_compare'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_compare', $check );

	$check = ( $config_store['config_show_displays'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_show_displays', $check );

	$check = ( $config_store['config_active_wishlist'] == '1' ) ? 'checked="checked"' : '';
	$xtpl->assign( 'ck_active_wishlist', $check );

	// Tien te
	$result = $db->query( 'SELECT code, title FROM ' . TABLE_PRODUCT_NAME . '_currency ORDER BY code DESC' );
	while( list( $code, $title ) = $result->fetch( 3 ) )
	{
		$array_temp = array();
		$array_temp['value'] = $code;
		$array_temp['title'] = $code . ' - ' . $title;
		$array_temp['selected'] = ( $code == $config_store['config_currency'] ) ? ' selected="selected"' : '';
		$xtpl->assign( 'DATAMONEY', $array_temp );
		$xtpl->parse( 'main.money_loop' );
	}

	$xtpl->assign( 'per_page', $select );

	foreach( $getCustomerGroup as $customer_group_id => $value )
	{
		$value['selected'] = ( $customer_group_id == $config_store['config_customer_group_id'] ) ? ' selected="selected"' : '';
		$xtpl->assign( 'CGROUP', $value );
		$xtpl->parse( 'main.customer_group' );
	}

	foreach( $getCustomerGroup as $customer_group_display => $_value )
	{ 
		$_value['checked'] = in_array( $customer_group_display, array_values( $config_store['config_customer_group_display'] ) ) ? ' checked="checked"' : '';
		$xtpl->assign( 'DISPLAY', $_value );
		$xtpl->parse( 'main.customer_group_display' );
	}
 
	foreach( $productArrayYesNo as $key => $values )
	{
		$checked = ( $key == $config_store['config_stock_display'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'STOCK_DISPLAY', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.stock_display' );
		
		$checked = ( $key == $config_store['config_stock_warning'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'STOCK_WARNING', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.stock_warning' );
 
		$checked = ( $key == $config_store['config_stock_checkout'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'STOCK_CHECKOUT', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.stock_checkout' );
		
		$checked = ( $key == $config_store['config_customer_price'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'CUSTOMER_PRICE', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.customer_price' );
		
		$checked = ( $key == $config_store['config_cart_weight'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'CART_WEIGHT', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.cart_weight' );
 		
		$checked = ( $key == $config_store['config_order_mail'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'ORDER_EMAIL', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.order_mail' );

		$checked = ( $key == $config_store['config_currency_auto'] ) ? 'checked="checked"' : '';
		$xtpl->assign( 'CURRENCY_AUTO', array(
			'checked' => $checked,
			'key' => $key,
			'name' => $values ) );
		$xtpl->parse( 'main.currency_auto' );
	}
 
	foreach( $getOrderStatus as $order_status_id => $value )
	{
		$selected = ( $order_status_id == $config_store['config_order_status_id'] ) ? 'selected="selected"' : '';
		$xtpl->assign( 'ORDER_STATUS', array(
			'selected' => $selected,
			'key' => $order_status_id,
			'name' => $value['name'] ) );
		$xtpl->parse( 'main.order_status' );
	}

	foreach( $getOrderStatus as $key => $value )
	{
		$value['checked'] = in_array( $key, array_values( $config_store['config_processing_status'] ) ) ? ' checked="checked"' : '';
		$xtpl->assign( 'PSTATUS', $value );
		$xtpl->parse( 'main.processing_status' );
		
		$value['checked'] = in_array( $key, array_values( $config_store['config_complete_status'] ) ) ? ' checked="checked"' : '';
		$xtpl->assign( 'CSTATUS', $value );
		$xtpl->parse( 'main.complete_status' );
		
		$xtpl->assign( 'FRAUD', array(
			'key' => $key,
			'name' => $value['name'],
			'selected' => ( $key == $config_store['config_fraud_status_id'] ) ? 'selected="selected"' : '' ));
		$xtpl->parse( 'main.fraud_status' );
		
	}
 
 
	foreach( $getLangModId as $language_id => $values )
	{
		$selected = ( $language_id == $config_store['config_language_id'] ) ? 'selected="selected"' : '';
		$xtpl->assign( 'LANGUAGE', array(
			'selected' => $selected,
			'language_id' => $language_id,
			'name' => $values['name'] ) );
		$xtpl->parse( 'main.language' );
	}

	

	foreach( $getLengthClass as $length_class_id => $_value )
	{
		$xtpl->assign( 'LENGTH', array(
			'length_class_id' => $length_class_id,
			'name' => nv_htmlspecialchars( $_value['title'] ),
			'selected' => ( $length_class_id == $config_store['config_length_class_id'] ) ? 'selected="selected"' : '',
			) );
		$xtpl->parse( 'main.length_class' );
	}

	foreach( $getWeightClass as $weight_class_id => $_value )
	{
		$xtpl->assign( 'WEIGHT', array(
			'weight_class_id' => $weight_class_id,
			'name' => nv_htmlspecialchars( $_value['title'] ),
			'selected' => ( $weight_class_id == $config_store['config_weight_class_id'] ) ? 'selected="selected"' : '',
			) );
		$xtpl->parse( 'main.weight_class' );
	}

	//checkout
	foreach( $getInformation as $information_id => $_value )
	{
		$xtpl->assign( 'INFO', array(
			'key' => $information_id,
			'name' => nv_htmlspecialchars( $_value['title'] ),
			'selected' => ( $information_id == $config_store['config_checkout_id'] ) ? 'selected="selected"' : '',
			) );
		$xtpl->parse( 'main.information' );
		
		$xtpl->assign( 'ACCOUNT', array(
			'key' => $information_id,
			'name' => nv_htmlspecialchars( $_value['title'] ),
			'selected' => ( $information_id == $config_store['config_account_id'] ) ? 'selected="selected"' : '',
			) );
		$xtpl->parse( 'main.account' );
 	
	}

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'error', $error );
		$xtpl->parse( 'main.error' );
	}

	$xtpl->parse( 'main' );

	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
 
}

$lang_ext = getLangAdmin( 'store', 'setting' );

$xtpl = new XTemplate( 'setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu() );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=store&action=add' );

$config = $ProductGeneral->getSetting( 'config', 0 );

$getStores = getStores();
$getStores[0] = array(
	'store_id' => 0,
	'name' => $config['config_name'] . '<b> (' . $lang_module['text_default'] . ')</b>',
	'url' => $config['config_url'] );
if( ! empty( $getStores ) )
{
	foreach( $getStores as $store_id => $loop )
	{
		$loop['token'] = md5( $global_config['sitekey'] . session_id() . $loop['store_id'] );
		$loop['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&store_id=' . $store_id . '&token=' . $loop['token'];

		$xtpl->assign( 'LOOP', $loop );
		$xtpl->parse( 'main.loop' );
	}
}
else
{
	$xtpl->parse( 'main.no_results' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
