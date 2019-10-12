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
 
	// tạo thông báo thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	$ProductGeneral->deleteCache( 'tax' );
	$ProductGeneral->deleteCache( 'setting.'. $ProductGeneral->store_id .'.tax' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// gỡ bỏ các bảng csdl liên quan tới tiện ích nếu có
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_flat");
  
	// tạo thông báo thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
	
	$ProductGeneral->deleteCache( 'tax' );
	$ProductGeneral->deleteCache( 'setting.'. $ProductGeneral->store_id .'.tax' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
} 
 
 
$page_title = $lang_plug['heading_title']; 
 
$data = $ProductGeneral->getSetting( 'tax', $ProductGeneral->store_id );  
 
$data['tax_status'] = isset( $data['tax_status'] ) ? $data['tax_status'] : null;
$data['tax_sort_order'] = isset( $data['tax_sort_order'] ) ? $data['tax_sort_order'] : '';

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{

	$data['tax_status'] = $nv_Request->get_int( 'tax_status', 'post', 0 );
	$data['tax_sort_order'] = $nv_Request->get_int( 'tax_sort_order', 'post', 0 );
	
	editSetting( 'tax', $data );
	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	$ProductGeneral->deleteCache( 'tax' );
	$ProductGeneral->deleteCache( 'setting.'. $ProductGeneral->store_id .'.tax' );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

$xtpl = new XTemplate( 'tax.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/total' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGP', $lang_plug );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'AddMenu', AddMenu( ) );
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
$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=shipping' );
 
$xtpl->assign( 'DATA', $data );
 
foreach( $productArrayStatus as $status => $val )
{
	$selected = ( $status == $data['tax_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.tax_status' );
}

 
if( isset( $error['warning'] ) )
{
	$xtpl->assign( 'WARNING', $error['warning'] );
	$xtpl->parse( 'main.warning' );
}
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
