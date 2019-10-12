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

	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
 
}
// gỡ bỏ cài đặt
if( ACTION_METHOD =='uninstall' )
{
	// gỡ bỏ các bảng csdl liên quan tới tiện ích nếu có
	// $db->query("DROP TABLE IF EXISTS " .TABLE_PRODUCT_NAME . "_free");
  
	// tạo thông báo thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );
 
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . "&rand=" . nv_genpass() );
	exit();
} 
 
 
$page_title = $lang_plug['heading_title']; 
 
$free_config =  $ProductGeneral->getSetting( 'shipping_free', $ProductGeneral->store_id ); 
 
$data['shipping_free_total'] = isset( $data['shipping_free_total'] ) ? $data['shipping_free_total'] : $free_config['shipping_free_total'];
$data['shipping_free_geo_zone_id'] = isset( $data['shipping_free_geo_zone_id'] ) ? $data['shipping_free_geo_zone_id'] : $free_config['shipping_free_geo_zone_id'];
$data['shipping_free_status'] = isset( $data['shipping_free_status'] ) ? $data['shipping_free_status'] : $free_config['shipping_free_status'];
$data['shipping_free_sort_order'] = isset( $data['shipping_free_sort_order'] ) ? $data['shipping_free_sort_order'] : $free_config['shipping_free_sort_order'];

if( $nv_Request->get_int( 'save', 'post', 0 ) )
{
	$data['shipping_free_total'] = $nv_Request->get_float( 'shipping_free_total', 'post', '' );
	$data['shipping_free_geo_zone_id'] = $nv_Request->get_int( 'shipping_free_geo_zone_id', 'post', 0 );
 	$data['shipping_free_status'] = $nv_Request->get_int( 'shipping_free_status', 'post', 0 );
	$data['shipping_free_sort_order'] = $nv_Request->get_int( 'shipping_free_sort_order', 'post', 0 );
 
	editSetting( 'shipping_free', $data );
	
	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_plug['text_success'] );

	$nv_Cache->delMod($module_name);
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	die();
}

$xtpl = new XTemplate( 'free.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/shipping' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANGP', $lang_plug );
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
	$selected = ( $status == $data['shipping_free_status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.free_status' );
}

$getGeoZones = getGeoZones(); 
foreach( $getGeoZones as $geo_zone_id => $value  )
{
	$selected = ( $geo_zone_id == $data['shipping_free_geo_zone_id'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'GEOZONE', array(
		'selected' => $selected,
		'key' => $geo_zone_id,
		'name' => $value['name'] ) );
	$xtpl->parse( 'main.geo_zone' );
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