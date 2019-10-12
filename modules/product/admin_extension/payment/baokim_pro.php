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
	
	$ProductGeneral->deleteCache( 'payment_baokim_pro' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim_pro' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order_transaction");
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_paypal_order");

	$ProductGeneral->deleteCache( 'payment_baokim_pro' );
	$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim_pro' );
	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
}
 
 
$page_title = $lang_plug['heading_title']; 
 
$baokim_pro_config = $ProductGeneral->getSetting( 'payment_baokim_pro', $ProductGeneral->store_id );
 
$data['payment_baokim_pro_email'] = isset( $baokim_pro_config['payment_baokim_pro_email'] ) ? $baokim_pro_config['payment_baokim_pro_email'] : '';
$data['payment_baokim_pro_username'] = isset( $baokim_pro_config['payment_baokim_pro_username'] ) ? $baokim_pro_config['payment_baokim_pro_username'] : '';
$data['payment_baokim_pro_password'] = isset( $baokim_pro_config['payment_baokim_pro_password'] ) ? $baokim_pro_config['payment_baokim_pro_password'] : '';
$data['payment_baokim_pro_signature'] = isset( $baokim_pro_config['payment_baokim_pro_signature'] ) ? $baokim_pro_config['payment_baokim_pro_signature'] : '';
$data['payment_baokim_pro_server'] = isset( $baokim_pro_config['payment_baokim_pro_server'] ) ? $baokim_pro_config['payment_baokim_pro_server'] : 'http://kiemthu.baokim.vn';
$data['payment_baokim_pro_transaction'] = isset( $baokim_pro_config['payment_baokim_pro_transaction'] ) ? $baokim_pro_config['payment_baokim_pro_transaction'] : 2;
$data['payment_baokim_pro_order_status_id'] = isset( $baokim_pro_config['payment_baokim_pro_order_status_id'] ) ? $baokim_pro_config['payment_baokim_pro_order_status_id'] : 0;
$data['payment_baokim_pro_log_file'] = isset( $baokim_pro_config['payment_baokim_pro_log_file'] ) ? $baokim_pro_config['payment_baokim_pro_log_file'] : '';
$data['onepay_atm_sort_order'] = isset( $baokim_pro_config['payment_baokim_pro_sort_order'] ) ? $baokim_pro_config['payment_baokim_pro_sort_order'] : 0;
$data['payment_baokim_pro_status'] = isset( $baokim_pro_config['payment_baokim_pro_status'] ) ? $baokim_pro_config['payment_baokim_pro_status'] : 1;
 
$getOrderStatus = getOrderStatus();
$getGeoZones = getGeoZones();
$error = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	
	$data['payment_baokim_pro_email'] = $nv_Request->get_string( 'payment_baokim_pro_email', 'post', '', 0 );
	$data['payment_baokim_pro_username'] = $nv_Request->get_string( 'payment_baokim_pro_username', 'post', '', 0 );
	$data['payment_baokim_pro_password'] = $nv_Request->get_string( 'payment_baokim_pro_password', 'post', '', 0 );
	$data['payment_baokim_pro_signature'] = $nv_Request->get_string( 'payment_baokim_pro_signature', 'post', '', 0 );
	$data['payment_baokim_pro_server'] = $nv_Request->get_string( 'payment_baokim_pro_server', 'post', '' );
	$data['payment_baokim_pro_transaction'] = $nv_Request->get_int( 'payment_baokim_pro_transaction', 'post', 0 );
	$data['payment_baokim_pro_order_status_id'] = $nv_Request->get_int( 'payment_baokim_pro_order_status_id', 'post', 0 );
	$data['payment_baokim_pro_log_file'] = $nv_Request->get_string( 'payment_baokim_pro_log_file', 'post', '' );
	$data['payment_baokim_pro_sort_order'] = $nv_Request->get_int( 'payment_baokim_pro_sort_order', 'post', 0 );
	$data['payment_baokim_pro_status'] = $nv_Request->get_int( 'payment_baokim_pro_status', 'post', 0 );
 
	if( empty( $data['payment_baokim_pro_email'] ) )
	{
		$error['email'] = $lang_plug['error_email'];
	}
	if( empty( $data['payment_baokim_pro_username'] ) )
	{
		$error['username'] = $lang_plug['error_username'];
	}
	if( empty( $data['payment_baokim_pro_password'] ) )
	{
		$error['password'] = $lang_plug['error_password'];
	}
	if( empty( $data['payment_baokim_pro_signature'] ) )
	{
		$error['signature'] = $lang_plug['error_signature'];
	}
	
	if( empty( $error ) )
	{
 
		editSetting( 'payment_baokim_pro', $data );
		
		$ProductGeneral->deleteCache( 'payment_baokim_pro' );
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.payment_baokim_pro' );
		
		$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	}
 
	if( empty( $error ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
}

$xtpl = new XTemplate( 'baokim_pro.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/payment' );
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
	$selected = ( $status == $data['payment_baokim_pro_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.status' );
}
 
foreach( $getOrderStatus as $order_status_id => $value  )
{
	$xtpl->assign( 'ORDER_STATUS', array(
		'selected' => ( $order_status_id == $data['payment_baokim_pro_order_status_id'] ) ? 'selected="selected"' : '',
		'key' => $order_status_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.order_status' );

	 
}

$array_server = array('http://kiemthu.baokim.vn' => $lang_plug['text_url_test'], 'http://baokim.vn' => $lang_plug['text_url_real']);
foreach( $array_server as $key => $value  )
{
	$xtpl->assign( 'SERVER', array(
		'selected' => ( $key == $data['payment_baokim_pro_server'] ) ? 'selected="selected"' : '',
		'key' => $key,
		'name' => $value  ) );
	$xtpl->parse( 'main.server' );

	 
} 

$array_transaction = array( '1' => $lang_plug['text_immediate'], '2' => $lang_plug['text_safe'] );
foreach( $array_transaction as $key => $value  )
{
	$xtpl->assign( 'TRANSACTION', array(
		'selected' => ( $key == $data['payment_baokim_pro_transaction'] ) ? 'selected="selected"' : '',
		'key' => $key,
		'name' => $value  ) );
	$xtpl->parse( 'main.transaction' );
	 
} 
// thông báo lỗi nếu có
if( isset( $error['email'] ) )
{
	$xtpl->assign( 'error_email', $error['email'] );
	$xtpl->parse( 'main.error_email' );
}
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