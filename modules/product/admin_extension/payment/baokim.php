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
	
	$ProductGeneral->deleteCache( 'payment_baokim' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim' );
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
	
	$ProductGeneral->deleteCache( 'payment_baokim' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim' );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$baokim_config = $ProductGeneral->getSetting( 'payment_baokim', $ProductGeneral->store_id );
 
$data['payment_baokim_merchant'] = isset( $baokim_config['payment_baokim_merchant'] ) ? $baokim_config['payment_baokim_merchant'] : '';
$data['payment_baokim_security'] = isset( $baokim_config['payment_baokim_security'] ) ? $baokim_config['payment_baokim_security'] : '';
$data['payment_baokim_business'] = isset( $baokim_config['payment_baokim_business'] ) ? $baokim_config['payment_baokim_business'] : '';
$data['payment_baokim_server'] = isset( $baokim_config['payment_baokim_server'] ) ? $baokim_config['payment_baokim_server'] : 'http://sandbox.baokim.vn/payment/order/version11';
$data['payment_baokim_order_status_id'] = isset( $baokim_config['baokim_order_status_id'] ) ? $baokim_config['payment_baokim_order_status_id'] : 0;
$data['payment_baokim_sort_order'] = isset( $baokim_config['payment_baokim_sort_order'] ) ? $baokim_config['payment_baokim_sort_order'] : 0;
$data['payment_baokim_status'] = isset( $baokim_config['payment_baokim_status'] ) ? $baokim_config['payment_baokim_status'] : 1;
$data['payment_baokim_geo_zone_id'] = isset( $baokim_config['payment_baokim_geo_zone_id'] ) ? $baokim_config['payment_baokim_geo_zone_id'] : 0;
 
$getOrderStatus = getOrderStatus();
$getGeoZones = getGeoZones();
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
	$data['payment_baokim_merchant'] = $nv_Request->get_string( 'payment_baokim_merchant', 'post', '', 0 );
	$data['payment_baokim_security'] = $nv_Request->get_string( 'payment_baokim_security', 'post', '', 0 );
	$data['payment_baokim_business'] = $nv_Request->get_string( 'payment_baokim_business', 'post', '', 0 );
	$data['payment_baokim_server'] = $nv_Request->get_string( 'payment_baokim_server', 'post', '' );
	$data['payment_baokim_order_status_id'] = $nv_Request->get_int( 'payment_baokim_order_status_id', 'post', 0 );
	$data['payment_baokim_sort_order'] = $nv_Request->get_int( 'payment_baokim_sort_order', 'post', 0 );
	$data['payment_baokim_status'] = $nv_Request->get_int( 'payment_baokim_status', 'post', 0 );
	$data['payment_baokim_geo_zone_id'] = $nv_Request->get_int( 'payment_baokim_geo_zone_id', 'post', 0 );
 
	if( empty( $data['payment_baokim_merchant'] ) )
	{
		$error['merchant'] = $lang_plug['error_merchant'];
	}
	if( empty( $data['payment_baokim_security'] ) )
	{
		$error['security'] = $lang_plug['error_security'];
	}
	if( empty( $data['payment_baokim_business'] ) )
	{
		$error['business'] = $lang_plug['error_business'];
	}
 
	if( empty( $error ) )
	{
 
		editSetting( 'payment_baokim', $data );
 
		$ProductGeneral->deleteCache( 'payment_baokim' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim' );
		
		// tạo thông báo đăng ký thành công
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'baokim.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
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
$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment' );
$xtpl->assign( 'DATA', $data );
 
foreach( $productArrayStatus as $status => $val )
{
	$selected = ( $status == $data['payment_baokim_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.status' );
}
 
foreach( $getOrderStatus as $order_status_id => $value  )
{
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => ( $order_status_id == $data['baokim_order_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.order_status' );

	 
}

$array_server = array('http://kiemthu.baokim.vn/payment/order/version11' => $lang_plug['text_url_test'], 'https://www.baokim.vn/payment/order/version11' => $lang_plug['text_url_real']);
foreach( $array_server as $key => $value  )
{
	$xtpl->assign( 'SERVER', array(
		'selected' => ( $key == $data['baokim_server'] ) ? 'selected="selected"' : '',
		'key' => $key,
		'name' => $value  ) );
	$xtpl->parse( 'main.server' );
  
} 

// thông báo lỗi nếu có
if( isset( $error['merchant'] ) )
{
	$xtpl->assign( 'error_merchant', $error['merchant'] );
	$xtpl->parse( 'main.error_merchant' );
}
if( isset( $error['security'] ) )
{
	$xtpl->assign( 'error_security', $error['security'] );
	$xtpl->parse( 'main.error_security' );
}
if( isset( $error['business'] ) )
{
	$xtpl->assign( 'error_business', $error['business'] );
	$xtpl->parse( 'main.error_business' );
}
 
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';