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

$lang_ext = getLangAdmin( $op, 'extension' );

$page_title = $lang_ext['heading_title'];

if( ACTION_METHOD == 'install' || ACTION_METHOD == 'uninstall' || ACTION_METHOD == 'edit' )
{
	$extension = $nv_Request->get_title( 'extension', 'get', '' );

	// chuyển hướng nếu không tồn tại tiện ích mở rộng
	if( ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_extension/total/' . $extension . '.php' ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=total' );
		die();
	}

	// gọi ngôn ngữ total extension
	$lang_plug = getLangAdmin( $extension, 'total' );

	if( ACTION_METHOD == 'install' )
	{
		// Đăng ký tiện ích mở rộng vào csdl extension
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_extension SET type = ' . $db->quote( $op ) . ', code = ' . $db->quote( $extension ) );
	}
	elseif( ACTION_METHOD == 'uninstall' )
	{
		// Xóa bỏ tiện ích mở rộng trong csdl extension
		$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( $op ) . ' AND code =' . $db->quote( $extension ) );
		$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_setting WHERE groups = ' . $db->quote( $extension ) );
		
		$ProductGeneral->deleteCache( 'setting.' . $ProductGeneral->store_id . '.' . $extension );
	}

	// kết nối tới file xử lý cài đặt tiện ích mở rộng tạo csdl mới, xóa, cập nhật thông tin cho tiện ích
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin_extension/total/' . $extension . '.php';

	// tạo thông báo đăng ký thành công
	$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );
	// thoát
	exit();

}

/*show list total*/
$extension_data = array();
$queryext = $db->query( 'SELECT code FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( $op ) )->fetchAll();
foreach( $queryext as $result )
{
	foreach( $queryext as $result )
	{
		if( ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_extension/total/' . $result['code'] . '.php' ) )
		{
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( $op ) . ' AND code =' . $db->quote( $result['code'] ) );
		}
		else
		{
			$extension_data[] = $result['code'];
		}
	}
}

$data['extensions'] = array();

$token = md5( $global_config['sitekey'] . session_id() );

$files = glob( NV_ROOTDIR . '/modules/' . $module_file . '/admin_extension/total/*.php' );

if( ! empty( $files ) )
{
	foreach( $files as $file )
	{
		$extension = basename( $file, '.php' );

		// gọi ngôn ngữ total extension
		$lang_plug = getLangAdmin( $extension, 'total' );

		$extension_config = $ProductGeneral->getSetting( $extension, $ProductGeneral->store_id );
 
		$extension_config[$extension . '_status'] = isset( $extension_config[$extension . '_status'] ) ? $extension_config[$extension . '_status'] : null;
		$extension_config[$extension . '_sort_order'] = isset( $extension_config[$extension . '_sort_order'] ) ? $extension_config[$extension . '_sort_order'] : null;

		$data['extensions'][] = array(
			'name' => $lang_plug['heading_title'],
			'status' => $extension_config[$extension . '_status'] ? $lang_module['enable'] : $lang_module['disabled'],
			'sort_order' => $extension_config[$extension . '_sort_order'],
			'install' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=total&action=install&extension=' . $extension . '&token=' . $token,
			'uninstall' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=total&action=uninstall&extension=' . $extension . '&token=' . $token,
			'installed' => in_array( $extension, $extension_data ),
			'edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=total&action=edit&extension=' . $extension . '&token=' . $token );
	
	}
}
 
$xtpl = new XTemplate( 'total.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
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
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', $token );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}


if( ! empty( $data['extensions'] ) )
{
	foreach( $data['extensions'] as $extension )
	{
		$xtpl->assign( 'LOOP', $extension );

		if( ! $extension['installed'] )
		{
			$xtpl->parse( 'main.loop.install' );
			$xtpl->parse( 'main.loop.install2' );
		}
		else
		{
			$xtpl->parse( 'main.loop.uninstall' );
			$xtpl->parse( 'main.loop.uninstall2' );
		}

		$xtpl->parse( 'main.loop' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
