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

	// $result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_cc'" );
	// if( ! $result->rowCount() )
	// {
		// $db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_cc VARCHAR( 100 ) NOT NULL; " );
	// }

	// $result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_name'" );
	// if( ! $result->rowCount() )
	// {
		// $db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_name VARCHAR( 100 ) NOT NULL ;" );
	// }

	// $result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_card_type'" );
	// if( ! $result->rowCount() )
	// {
		// $db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_card_type VARCHAR( 100 ) NOT NULL;" );
	// }
 	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	$ProductGeneral->deleteCache( 'payment_onepay_atm' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_onepay_atm' );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction");
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order");
 
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	$ProductGeneral->deleteCache( 'payment_onepay_atm' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_onepay_atm' );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$payment_onepay_atm_config = $ProductGeneral->getSetting( 'payment_onepay_atm', $ProductGeneral->store_id );
 
$data['payment_onepay_atm_url_paygate'] = isset( $payment_onepay_atm_config['payment_onepay_atm_url_paygate'] ) ? $payment_onepay_atm_config['payment_onepay_atm_url_paygate'] : '';
$data['payment_onepay_atm_merchant_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_merchant_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_merchant_id'] : '';
$data['payment_onepay_atm_access_code'] = isset( $payment_onepay_atm_config['payment_onepay_atm_access_code'] ) ? $payment_onepay_atm_config['payment_onepay_atm_access_code'] : '';
$data['payment_onepay_atm_hash_code'] = isset( $payment_onepay_atm_config['payment_onepay_atm_hash_code'] ) ? $payment_onepay_atm_config['payment_onepay_atm_hash_code'] : '';
$data['payment_onepay_atm_completed_status_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_completed_status_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_completed_status_id'] : 0;
$data['payment_onepay_atm_failed_status_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_failed_status_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_failed_status_id'] : 0;
$data['payment_onepay_atm_pending_status_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_pending_status_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_pending_status_id'] : 0;
$data['payment_onepay_atm_geo_zone_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_geo_zone_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_geo_zone_id'] : 0;
$data['payment_onepay_atm_order_status_id'] = isset( $payment_onepay_atm_config['payment_onepay_atm_order_status_id'] ) ? $payment_onepay_atm_config['payment_onepay_atm_order_status_id'] : 0;
$data['payment_onepay_atm_sort_order'] = isset( $payment_onepay_atm_config['payment_onepay_atm_sort_order'] ) ? $payment_onepay_atm_config['payment_onepay_atm_sort_order'] : 0;
$data['payment_onepay_atm_status'] = isset( $payment_onepay_atm_config['payment_onepay_atm_status'] ) ? $payment_onepay_atm_config['payment_onepay_atm_status'] : 0;
 
$getOrderStatus = getOrderStatus();
$getGeoZones = getGeoZones();
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
	$data['payment_onepay_atm_url_paygate'] = $nv_Request->get_string( 'payment_onepay_atm_url_paygate', 'post', '', 0 );
	$data['payment_onepay_atm_merchant_id'] = $nv_Request->get_string( 'payment_onepay_atm_merchant_id', 'post', '', 0 );
	$data['payment_onepay_atm_access_code'] = $nv_Request->get_string( 'payment_onepay_atm_access_code', 'post', '', 0 );
	$data['payment_onepay_atm_hash_code'] = $nv_Request->get_string( 'payment_onepay_atm_hash_code', 'post', '', 0 );
	$data['payment_onepay_atm_completed_status_id'] = $nv_Request->get_int( 'payment_onepay_atm_completed_status_id', 'post', 0 );
	$data['payment_onepay_atm_failed_status_id'] = $nv_Request->get_int( 'payment_onepay_atm_failed_status_id', 'post', 0 );
	$data['payment_onepay_atm_pending_status_id'] = $nv_Request->get_int( 'payment_onepay_atm_pending_status_id', 'post', 0 );
	$data['payment_onepay_atm_geo_zone_id'] = $nv_Request->get_int( 'payment_onepay_atm_geo_zone_id', 'post', 0 );
	$data['payment_onepay_atm_order_status_id'] = $nv_Request->get_int( 'payment_onepay_atm_order_status_id', 'post', 0 );
	$data['payment_onepay_atm_sort_order'] = $nv_Request->get_int( 'payment_onepay_atm_sort_order', 'post', 0 );
	$data['payment_onepay_atm_status'] = $nv_Request->get_int( 'payment_onepay_atm_status', 'post', 0 );
 
	if( empty( $data['payment_onepay_atm_url_paygate'] ) )
	{
		$error['url_paygate'] = $lang_plug['error_url_paygate'];
	}
	if( empty( $data['payment_onepay_atm_merchant_id'] ) )
	{
		$error['merchant_id'] = $lang_plug['error_merchant_id'];
	}
	if( empty( $data['payment_onepay_atm_access_code'] ) )
	{
		$error['access_code'] = $lang_plug['error_access_code'];
	}
	if( empty( $data['payment_onepay_atm_hash_code'] ) )
	{
		$error['hash_code'] = $lang_plug['error_hash_code'];
	}
	
	if( empty( $error ) )
	{
 
		editSetting( 'payment_onepay_atm', $data );
 
		$ProductGeneral->deleteCache( 'payment_onepay_atm' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_onepay_atm' );
		
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'onepay_atm.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGP', $lang_plug );
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
$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment' );
$xtpl->assign( 'DATA', $data );
 
foreach( $productArrayStatus as $status => $val )
{
	$selected = ( $status == $data['payment_onepay_atm_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.payment_onepay_atm_status' );
}
 
foreach( $getGeoZones as $geo_zone_id => $value  )
{
	$selected = ( $geo_zone_id == $data['payment_onepay_atm_geo_zone_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'GEOZONE', array(
		'selected' => $selected,
		'key' => $geo_zone_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.geo_zone' );
}
 
foreach( $getOrderStatus as $order_status_id => $value  )
{
	$xtpl->assign( 'COMPLETED_STATUS', array(
		'selected' => ( $order_status_id == $data['payment_onepay_atm_completed_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.completed_status' );

	$xtpl->assign( 'FAILED_STATUS', array(
		'selected' => ( $order_status_id == $data['payment_onepay_atm_failed_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.failed_status' );

	$xtpl->assign( 'PENDING_STATUS', array(
		'selected' => ( $order_status_id == $data['payment_onepay_atm_pending_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.pending_status' );

}
 
// thông báo lỗi nếu có
if( isset( $error['url_paygate'] ) )
{
	$xtpl->assign( 'error_url_paygate', $error['url_paygate'] );
	$xtpl->parse( 'main.error_url_paygate' );
}
if( isset( $error['merchant_id'] ) )
{
	$xtpl->assign( 'error_merchant_id', $error['merchant_id'] );
	$xtpl->parse( 'main.error_merchant_id' );
}
if( isset( $error['access_code'] ) )
{
	$xtpl->assign( 'error_access_code', $error['access_code'] );
	$xtpl->parse( 'main.error_access_code' );
}
if( isset( $error['hash_code'] ) )
{
	$xtpl->assign( 'error_hash_code', $error['hash_code'] );
	$xtpl->parse( 'main.error_hash_code' );
}
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';