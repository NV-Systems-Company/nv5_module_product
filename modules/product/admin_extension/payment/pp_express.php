<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );


// cài đặt tiện ích
if( ACTION_METHOD =='install' )
{
	$db->query("CREATE TABLE IF NOT EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order (
		paypal_order_id int(11) NOT NULL AUTO_INCREMENT,
		order_id int(11) NOT NULL,
		date_added int(11) unsigned NOT NULL DEFAULT '0',
		date_modified int(11) unsigned NOT NULL DEFAULT '0',
		capture_status ENUM('Complete','NotComplete') DEFAULT NULL,
		currency_code CHAR(3) NOT NULL,
		authorization_id VARCHAR(30) NOT NULL,
		total DECIMAL( 10, 2 ) NOT NULL,
		PRIMARY KEY (paypal_order_id)
	) ENGINE=MyISAM");

	$db->query("CREATE TABLE IF NOT EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction (
		paypal_order_transaction_id int(11) NOT NULL AUTO_INCREMENT,
		paypal_order_id int(11) NOT NULL,
		transaction_id CHAR(20) NOT NULL,
		parent_transaction_id CHAR(20) NOT NULL,
		date_added int(11) unsigned NOT NULL DEFAULT '0',
		note VARCHAR(255) NOT NULL,
		msgsubid CHAR(38) NOT NULL,
		receipt_id CHAR(20) NOT NULL,
		payment_type ENUM('none','echeck','instant', 'refund', 'void') DEFAULT NULL,
		payment_status CHAR(20) NOT NULL,
		pending_reason CHAR(50) NOT NULL,
		transaction_entity CHAR(50) NOT NULL,
		amount DECIMAL( 10, 2 ) NOT NULL,
		debug_data TEXT NOT NULL,
		call_data TEXT NOT NULL,
		PRIMARY KEY (paypal_order_transaction_id)
	) ENGINE=MyISAM"); 
	
	// Tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	$nv_Cache->delMod($module_name);
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	$db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction");
	$db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order");
 
	// Tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	$ProductGeneral->deleteCache( 'payment_pp_express' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_pp_express' );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$payment_pp_express_config = $ProductGeneral->getSetting( 'payment_pp_express', $ProductGeneral->store_id );
 
$data['payment_pp_express_username'] = isset( $payment_pp_express_config['payment_pp_express_username'] ) ? $payment_pp_express_config['payment_pp_express_username'] : '';
$data['payment_pp_express_password'] = isset( $payment_pp_express_config['payment_pp_express_password'] ) ? $payment_pp_express_config['payment_pp_express_password'] : '';
$data['payment_pp_express_signature'] = isset( $payment_pp_express_config['payment_pp_express_signature'] ) ? $payment_pp_express_config['payment_pp_express_signature'] : '';
$data['payment_pp_express_test'] = isset( $payment_pp_express_config['payment_pp_express_test'] ) ? $payment_pp_express_config['payment_pp_express_test'] : '';
$data['payment_pp_express_debug'] = isset( $payment_pp_express_config['payment_pp_express_debug'] ) ? $payment_pp_express_config['payment_pp_express_debug'] : '';
$data['payment_pp_express_currency'] = isset( $payment_pp_express_config['payment_pp_express_currency'] ) ? $payment_pp_express_config['payment_pp_express_currency'] : '';
$data['payment_pp_express_recurring_cancel_status'] = isset( $payment_pp_express_config['payment_pp_express_recurring_cancel_status'] ) ? $payment_pp_express_config['payment_pp_express_recurring_cancel_status'] : '';
$data['payment_pp_express_method'] = isset( $payment_pp_express_config['payment_pp_express_method'] ) ? $payment_pp_express_config['payment_pp_express_method'] : '';
$data['payment_pp_express_total'] = isset( $payment_pp_express_config['payment_pp_express_total'] ) ? $payment_pp_express_config['payment_pp_express_total'] : '';
$data['payment_pp_express_sort_order'] = isset( $payment_pp_express_config['payment_pp_express_sort_order'] ) ? $payment_pp_express_config['payment_pp_express_sort_order'] : '';
$data['payment_pp_express_geo_zone_id'] = isset( $payment_pp_express_config['payment_pp_express_geo_zone_id'] ) ? $payment_pp_express_config['payment_pp_express_geo_zone_id'] : '';
$data['payment_pp_express_status'] = isset( $payment_pp_express_config['payment_pp_express_status'] ) ? $payment_pp_express_config['payment_pp_express_status'] : '';
$data['payment_pp_express_canceled_reversal_status_id'] = isset( $payment_pp_express_config['payment_pp_express_canceled_reversal_status_id'] ) ? $payment_pp_express_config['payment_pp_express_canceled_reversal_status_id'] : '';
$data['payment_pp_express_completed_status_id'] = isset( $payment_pp_express_config['payment_pp_express_completed_status_id'] ) ? $payment_pp_express_config['payment_pp_express_completed_status_id'] : '';
$data['payment_pp_express_denied_status_id'] = isset( $payment_pp_express_config['payment_pp_express_denied_status_id'] ) ? $payment_pp_express_config['payment_pp_express_denied_status_id'] : '';
$data['payment_pp_express_expired_status_id'] = isset( $payment_pp_express_config['payment_pp_express_expired_status_id'] ) ? $payment_pp_express_config['payment_pp_express_expired_status_id'] : '';
$data['payment_pp_express_failed_status_id'] = isset( $payment_pp_express_config['payment_pp_express_failed_status_id'] ) ? $payment_pp_express_config['payment_pp_express_failed_status_id'] : '';
$data['payment_pp_express_pending_status_id'] = isset( $payment_pp_express_config['payment_pp_express_pending_status_id'] ) ? $payment_pp_express_config['payment_pp_express_pending_status_id'] : '';
$data['payment_pp_express_processed_status_id'] = isset( $payment_pp_express_config['payment_pp_express_processed_status_id'] ) ? $payment_pp_express_config['payment_pp_express_processed_status_id'] : '';
$data['payment_pp_express_refunded_status_id'] = isset( $payment_pp_express_config['payment_pp_express_refunded_status_id'] ) ? $payment_pp_express_config['payment_pp_express_refunded_status_id'] : '';
$data['payment_pp_express_reversed_status_id'] = isset( $payment_pp_express_config['payment_pp_express_reversed_status_id'] ) ? $payment_pp_express_config['payment_pp_express_reversed_status_id'] : '';
$data['payment_pp_express_voided_status_id'] = isset( $payment_pp_express_config['payment_pp_express_voided_status_id'] ) ? $payment_pp_express_config['payment_pp_express_voided_status_id'] : '';
$data['payment_pp_express_allow_note'] = isset( $payment_pp_express_config['payment_pp_express_allow_note'] ) ? $payment_pp_express_config['payment_pp_express_allow_note'] : '';
$data['payment_pp_express_page_colour'] = isset( $payment_pp_express_config['payment_pp_express_page_colour'] ) ? $payment_pp_express_config['payment_pp_express_page_colour'] : '';
$data['payment_pp_express_logo'] = isset( $payment_pp_express_config['payment_pp_express_logo'] ) ? $payment_pp_express_config['payment_pp_express_logo'] : '';
 
$getOrderStatus = getOrderStatus();
$currencyCodes = array( 
	'AUD', 'BRL', 'CAD', 'CZK', 'DKK',
	'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 
	'MYR', 'MXN', 'NOK', 'NZD', 'PHP',
	'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 
	'TWD', 'THB', 'TRY', 'USD',
);
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
	$data['payment_pp_express_username'] = $nv_Request->get_title( 'payment_pp_express_username', 'post', '' );
	$data['payment_pp_express_password'] = $nv_Request->get_title( 'payment_pp_express_password', 'post', '' );
	$data['payment_pp_express_signature'] = $nv_Request->get_title( 'payment_pp_express_signature', 'post', '' );
	$data['payment_pp_express_test'] = $nv_Request->get_int( 'payment_pp_express_test', 'post', 0 );
	$data['payment_pp_express_debug'] = $nv_Request->get_int( 'payment_pp_express_debug', 'post', 0 );
	$data['payment_pp_express_currency'] = $nv_Request->get_title( 'payment_pp_express_currency', 'post', '' );
	$data['payment_pp_express_recurring_cancel_status'] = $nv_Request->get_int( 'payment_pp_express_recurring_cancel_status', 'post', 0 );
	$data['payment_pp_express_method'] = $nv_Request->get_title( 'payment_pp_express_method', 'post', '' );
	$data['payment_pp_express_total'] = $nv_Request->get_float( 'payment_pp_express_total', 'post', '' );
	$data['payment_pp_express_sort_order'] = $nv_Request->get_int( 'payment_pp_express_sort_order', 'post', 0 );
	$data['payment_pp_express_geo_zone_id'] = $nv_Request->get_int( 'payment_pp_express_geo_zone_id', 'post', 0);
	$data['payment_pp_express_status'] = $nv_Request->get_int( 'payment_pp_express_status', 'post', 0 );
	$data['payment_pp_express_canceled_reversal_status_id'] = $nv_Request->get_int( 'payment_pp_express_canceled_reversal_status_id', 'post', 0 );
	$data['payment_pp_express_completed_status_id'] = $nv_Request->get_int( 'payment_pp_express_completed_status_id', 'post', 0 );
	$data['payment_pp_express_denied_status_id'] = $nv_Request->get_int( 'payment_pp_express_denied_status_id', 'post', 0 );
	$data['payment_pp_express_expired_status_id'] = $nv_Request->get_int( 'payment_pp_express_expired_status_id', 'post', 0 );
	$data['payment_pp_express_failed_status_id'] = $nv_Request->get_int( 'payment_pp_express_failed_status_id', 'post', 0 );
	$data['payment_pp_express_pending_status_id'] = $nv_Request->get_int( 'payment_pp_express_pending_status_id', 'post', 0 );
	$data['payment_pp_express_processed_status_id'] = $nv_Request->get_int( 'payment_pp_express_processed_status_id', 'post', 0 );
	$data['payment_pp_express_refunded_status_id'] = $nv_Request->get_int( 'payment_pp_express_refunded_status_id', 'post', 0 );
	$data['payment_pp_express_reversed_status_id'] = $nv_Request->get_int( 'payment_pp_express_reversed_status_id', 'post', 0 );
	$data['payment_pp_express_voided_status_id'] = $nv_Request->get_int( 'payment_pp_express_voided_status_id', 'post', 0 );
	$data['payment_pp_express_allow_note'] = $nv_Request->get_int( 'payment_pp_express_allow_note', 'post', 0 );
	$data['payment_pp_express_page_colour'] = $nv_Request->get_title( 'payment_pp_express_page_colour', 'post', '' );
	$data['payment_pp_express_logo'] = $nv_Request->get_title( 'payment_pp_express_logo', 'post', '' );
	
	
	if( empty( $data['payment_pp_express_username'] ) )
	{
		$error['username'] = $lang_plug['error_username'];
	}
	if( empty( $data['payment_pp_express_password'] ) )
	{
		$error['password'] = $lang_plug['error_password'];
	}
	if( empty( $data['payment_pp_express_signature'] ) )
	{
		$error['signature'] = $lang_plug['error_signature'];
	}
	
	if( empty( $error ) )
	{
 
		editSetting( 'payment_pp_express', $data );
 
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

		$ProductGeneral->deleteCache( 'payment_pp_express' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_pp_express' );
		
	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'pp_express.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_plug );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment' );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );
 
$xtpl->assign( 'DATA', $data );
 
 
$payment_pp_express_method = array( 'authorization'=> $lang_plug['text_authorization'], 'sale'=> $lang_plug['text_sale'] );

foreach( $payment_pp_express_method as $key => $name )
{
	$selected = ( $key == $data['payment_pp_express_method'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'METHOD', array(
		'selected' => $selected,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.method' );
	
}

foreach( $currencyCodes as $name )
{
	$selected = ( $name == $data['payment_pp_express_currency'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'CURRENCY', array(
		'selected' => $selected,
		'key' => $name,
		'name' => $name ) );
	$xtpl->parse( 'main.currency' );
	
}


foreach( $productArrayStatus as $status => $val )
{
	$selected = ( $status == $data['payment_pp_express_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.payment_status' );
	
	$selected = ( $status == $data['payment_pp_express_recurring_cancel_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.recurring_cancel_status' );	
}
$getGeoZones = getGeoZones();
foreach( $getGeoZones as $geo_zone_id => $value  )
{
	$selected = ( $geo_zone_id == $data['payment_pp_express_geo_zone_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'GEOZONE', array(
		'selected' => $selected,
		'key' => $geo_zone_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.geo_zone' );
}
 
foreach( $getOrderStatus as $order_status_id => $value  )
{
	$selected = ( $order_status_id == $data['payment_pp_express_canceled_reversal_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.canceled_reversal_status' );
	
	$selected = ( $order_status_id == $data['payment_pp_express_completed_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.completed_status' );
 
	$selected = ( $order_status_id == $data['payment_pp_express_denied_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.denied_status' );
	
	$selected = ( $order_status_id == $data['payment_pp_express_expired_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.expired_status' );
	
	$selected = ( $order_status_id == $data['payment_pp_express_failed_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.failed_status' );
	
	$selected = ( $order_status_id == $data['payment_pp_express_pending_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.pending_status' );
	
	$selected = ( $order_status_id == $data['payment_pp_express_processed_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.processed_status' );
	
	
	$selected = ( $order_status_id == $data['payment_pp_express_refunded_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.refunded_status' );
 
	$selected = ( $order_status_id == $data['payment_pp_express_reversed_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.reversed_status' );
 
	$selected = ( $order_status_id == $data['payment_pp_express_voided_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.voided_status' );
	
	
}

foreach( $productArrayYesNo as $key => $name )
{
	$selected = ( $key == $data['payment_pp_express_test'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'YESNO', array(
		'selected' => $selected,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.test' );	
	
	$selected = ( $key == $data['payment_pp_express_debug'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'YESNO', array(
		'selected' => $selected,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.debug' );	
	
	
	$selected = ( $key == $data['payment_pp_express_allow_note'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'YESNO', array(
		'selected' => $selected,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.allow_note' );	
}
 

// thông báo lỗi nếu có
if( isset( $error['username'] ) )
{
	$xtpl->assign( 'error_username', $error['username'] );
	$xtpl->parse( 'main.error_username' );
}
if( isset( $error['password'] ) )
{
	$xtpl->assign( 'error_password', $error['password'] );
	$xtpl->parse( 'main.error_password' );
}
if( isset( $error['signature'] ) )
{
	$xtpl->assign( 'error_signature', $error['signature'] );
	$xtpl->parse( 'main.error_signature' );
}
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';