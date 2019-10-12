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
	
	$ProductGeneral->deleteCache( 'payment_cod' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_cod' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction");
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order");

	$ProductGeneral->deleteCache( 'payment_cod' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_cod' );
	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$payment_cod_config = $ProductGeneral->getSetting( 'payment_cod', $ProductGeneral->store_id );

$data['payment_cod_geo_zone_id'] = isset( $payment_cod_config['payment_cod_geo_zone_id'] ) ? $payment_cod_config['payment_cod_geo_zone_id'] : 2;
$data['payment_cod_order_status_id'] = isset( $payment_cod_config['payment_cod_order_status_id'] ) ? $payment_cod_config['payment_cod_order_status_id'] : 0;
$data['payment_cod_total'] = isset( $payment_cod_config['payment_cod_total'] ) ? $payment_cod_config['payment_cod_total'] : '';
$data['payment_cod_sort_order'] = isset( $payment_cod_config['payment_cod_sort_order'] ) ? $payment_cod_config['payment_cod_sort_order'] : 0;
$data['payment_cod_status'] = isset( $payment_cod_config['payment_cod_status'] ) ? $payment_cod_config['payment_cod_status'] : 1;
 
$getOrderStatus = getOrderStatus();
$getGeoZones = getGeoZones();
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
 
	$data['payment_cod_geo_zone_id'] = $nv_Request->get_int( 'payment_cod_geo_zone_id', 'post', 0 );
	$data['payment_cod_order_status_id'] = $nv_Request->get_int( 'payment_cod_order_status_id', 'post', 0 );
	$data['payment_cod_total'] = $nv_Request->get_string( 'payment_cod_total', 'post', '' );
	$data['payment_cod_sort_order'] = $nv_Request->get_int( 'payment_cod_sort_order', 'post', 0 );
	$data['payment_cod_status'] = $nv_Request->get_int( 'payment_cod_status', 'post', 0 );
 
	// if( empty( $data['payment_cod_business'] ) )
	// {
		// $error['business'] = $lang_plug['error_business'];
	// }
	// if( empty( $data['payment_cod_username'] ) )
	// {
		// $error['username'] = $lang_plug['error_username'];
	// }
	// if( empty( $data['payment_cod_password'] ) )
	// {
		// $error['password'] = $lang_plug['error_password'];
	// }
	// if( empty( $data['payment_cod_signature'] ) )
	// {
		// $error['signature'] = $lang_plug['error_signature'];
	// }
	
	if( empty( $error ) )
	{
 
		editSetting( 'payment_cod', $data );
		
		$ProductGeneral->deleteCache( 'payment_cod' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_cod' );
		
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'cod.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
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
	$selected = ( $status == $data['payment_cod_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.status' );
}

$getGeoZones = getGeoZones();
 
foreach( $getGeoZones as $geo_zone_id => $value  )
{
	$selected = ( $geo_zone_id == $data['payment_cod_geo_zone_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'GEO_ZONE', array(
		'selected' => $selected,
		'key' => $geo_zone_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.geo_zone' );
}

foreach( $getOrderStatus as $order_status_id => $value  )
{
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => ( $order_status_id == $data['payment_cod_order_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.order_status' );

}
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';