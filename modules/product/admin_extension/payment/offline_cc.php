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

	$result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_cc'" );
	if( ! $result->rowCount() )
	{
		$db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_cc VARCHAR( 100 ) NOT NULL; " );
	}

	$result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_name'" );
	if( ! $result->rowCount() )
	{
		$db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_name VARCHAR( 100 ) NOT NULL ;" );
	}

	$result = $db->query( "SHOW columns FROM " . TABLE_PRODUCT_NAME . "_order WHERE field='payment_card_type'" );
	if( ! $result->rowCount() )
	{
		$db->query( "ALTER TABLE " . TABLE_PRODUCT_NAME . "_order ADD payment_card_type VARCHAR( 100 ) NOT NULL;" );
	}
 	
	$ProductGeneral->deleteCache( 'payment_offline_cc' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_offline_cc' );
	
	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction");
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order");
 
	$ProductGeneral->deleteCache( 'payment_offline_cc' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_offline_cc' );
	
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$offline_config = $ProductGeneral->getSetting( 'payment_offline_cc', $ProductGeneral->store_id );
 
$data['payment_offline_cc_accept_visa'] = isset( $offline_config['payment_offline_cc_accept_visa'] ) ? $offline_config['payment_offline_cc_accept_visa'] : '';
$data['payment_offline_cc_accept_ae'] = isset( $offline_config['payment_offline_cc_accept_ae'] ) ? $offline_config['payment_offline_cc_accept_ae'] : '';
$data['payment_offline_cc_accept_cu'] = isset( $offline_config['payment_offline_cc_accept_cu'] ) ? $offline_config['payment_offline_cc_accept_cu'] : '';
$data['payment_offline_cc_accept_master'] = isset( $offline_config['payment_offline_cc_accept_master'] ) ? $offline_config['payment_offline_cc_accept_master'] : '';
$data['payment_offline_cc_accept_jcb'] = isset( $offline_config['payment_offline_cc_accept_jcb'] ) ? $offline_config['payment_offline_cc_accept_jcb'] : '';
$data['payment_offline_cc_use_cc_type'] = isset( $offline_config['payment_offline_cc_use_cc_type'] ) ? $offline_config['payment_offline_cc_use_cc_type'] : '';
$data['payment_offline_cc_use_cc_name'] = isset( $offline_config['payment_offline_cc_use_cc_name'] ) ? $offline_config['payment_offline_cc_use_cc_name'] : '';
$data['payment_offline_cc_encryption'] = isset( $offline_config['payment_offline_cc_encryption'] ) ? $offline_config['payment_offline_cc_encryption'] : '';
$data['payment_offline_cc_email'] = isset( $offline_config['payment_offline_cc_email'] ) ? $offline_config['payment_offline_cc_email'] : '';
$data['payment_offline_cc_geo_zone_id'] = isset( $offline_config['payment_offline_cc_geo_zone_id'] ) ? $offline_config['payment_offline_cc_geo_zone_id'] : 0;
$data['payment_offline_cc_order_status_id'] = isset( $offline_config['payment_offline_cc_order_status_id'] ) ? $offline_config['payment_offline_cc_order_status_id'] : 0;
$data['payment_offline_cc_total'] = isset( $offline_config['payment_offline_cc_total'] ) ? $offline_config['payment_offline_cc_total'] : '';
$data['payment_offline_cc_sort_order'] = isset( $offline_config['payment_offline_cc_sort_order'] ) ? $offline_config['payment_offline_cc_sort_order'] : 0; 
$data['payment_offline_cc_status'] = isset( $offline_config['payment_offline_cc_status'] ) ? $offline_config['payment_offline_cc_status'] : 1; 


$getOrderStatus = getOrderStatus();
 
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
	$data['payment_offline_cc_total'] = $nv_Request->get_float( 'payment_offline_cc_total', 'post', '' );
	$data['payment_offline_cc_accept_visa'] = $nv_Request->get_int( 'payment_offline_cc_accept_visa', 'post', 1 );
	$data['payment_offline_cc_accept_ae'] = $nv_Request->get_int( 'payment_offline_cc_accept_ae', 'post', 1 );
	$data['payment_offline_cc_accept_cu'] = $nv_Request->get_int( 'payment_offline_cc_accept_cu', 'post', 1 );
	$data['payment_offline_cc_accept_master'] = $nv_Request->get_int( 'payment_offline_cc_accept_master', 'post', 0 );
	$data['payment_offline_cc_accept_jcb'] = $nv_Request->get_int( 'payment_offline_cc_accept_jcb', 'post', 1 );
	$data['payment_offline_cc_use_cc_type'] = $nv_Request->get_int( 'payment_offline_cc_use_cc_type', 'post', 0 );
	$data['payment_offline_cc_use_cc_name'] = $nv_Request->get_int( 'payment_offline_cc_use_cc_name', 'post', 0 );
	$data['payment_offline_cc_encryption'] = $nv_Request->get_string( 'payment_offline_cc_encryption', 'post', '' );
	$data['payment_offline_cc_email'] = $nv_Request->get_string( 'payment_offline_cc_email', 'post', '' );
	$data['payment_offline_cc_geo_zone_id'] = $nv_Request->get_int( 'payment_offline_cc_geo_zone_id', 'post', 0 );
	$data['payment_offline_cc_order_status_id'] = $nv_Request->get_int( 'payment_offline_cc_order_status_id', 'post', 0 );
	$data['payment_offline_cc_sort_order'] = $nv_Request->get_int( 'payment_offline_cc_sort_order', 'post', 0 );
 	$data['payment_offline_cc_status'] = $nv_Request->get_int( 'payment_offline_cc_status', 'post', 0 );
 
	if( empty( $data['payment_offline_cc_total'] ) )
	{
		$error['total'] = $lang_plug['error_total'];
	}
	if( empty( $data['payment_offline_cc_order_status_id'] ) )
	{
		$error['order_status'] = $lang_plug['error_order_status'];
	}
	if( empty( $data['payment_offline_cc_email'] ) )
	{
		$error['email'] = $lang_plug['error_email'];
	}
	if( empty( $data['payment_offline_cc_encryption'] ) )
	{
		$error['encryption'] = $lang_plug['error_encryption'];
	}
	
	if( empty( $error ) )
	{
 
		editSetting( 'payment_offline_cc', $data );

		$ProductGeneral->deleteCache( 'payment_offline_cc' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_offline_cc' );
		
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'offline_cc.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
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
 
$xtpl->assign( 'DATA', $data );
 
foreach( $productArrayStatus as $status => $val )
{
	$selected = ( $status == $data['payment_offline_cc_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.payment_status' );
	
 
}

$getGeoZones = getGeoZones();
foreach( $getGeoZones as $geo_zone_id => $value  )
{
	$selected = ( $geo_zone_id == $data['payment_offline_cc_geo_zone_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'GEOZONE', array(
		'selected' => $selected,
		'key' => $geo_zone_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.geo_zone' );
}
 
foreach( $getOrderStatus as $order_status_id => $value  )
{
	$selected = ( $order_status_id == $data['payment_offline_cc_order_status_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => $selected,
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.order_status' );

}

foreach( $productArrayYesNo as $key => $name )
{
	$checked = ( $key == $data['payment_offline_cc_use_cc_name'] ) ? 'checked="checked"' : '';
	$xtpl->assign( 'CCNAME', array(
		'checked' => $checked,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.use_cc_name' );	
	
	$checked = ( $key == $data['payment_offline_cc_use_cc_type'] ) ? 'checked="checked"' : '';
	$xtpl->assign( 'CCTYPE', array(
		'checked' => $checked,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.use_cc_type' );
}
 
$check = ( $data['payment_offline_cc_accept_visa'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'payment_offline_cc_accept_visa', $check );

$check = ( $data['payment_offline_cc_accept_ae'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'payment_offline_cc_accept_ae', $check );

$check = ( $data['payment_offline_cc_accept_cu'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'payment_offline_cc_accept_cu', $check );

$check = ( $data['payment_offline_cc_accept_master'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'payment_offline_cc_accept_master', $check );

$check = ( $data['payment_offline_cc_accept_jcb'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'payment_offline_cc_accept_jcb', $check );


// thông báo lỗi nếu có
if( isset( $error['total'] ) )
{
	$xtpl->assign( 'error_total', $error['total'] );
	$xtpl->parse( 'main.error_total' );
}
if( isset( $error['order_status'] ) )
{
	$xtpl->assign( 'error_order_status', $error['order_status'] );
	$xtpl->parse( 'main.error_order_status' );
}
 
if( isset( $error['email'] ) )
{
	$xtpl->assign( 'error_email', $error['email'] );
	$xtpl->parse( 'main.error_email' );
}
if( isset( $error['encryption'] ) )
{
	$xtpl->assign( 'error_encryption', $error['encryption'] );
	$xtpl->parse( 'main.error_encryption' );
}
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';