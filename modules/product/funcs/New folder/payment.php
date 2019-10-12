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

if( ! defined( 'NV_IS_MOD_PRODUCT' ) ) die( 'Stop!!!' );

$page_title = $lang_module['order_view'];
 
$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
 
if( $order_id > 0 and ACTION_METHOD == 'checkout' && $nv_Request->isset_request( 'method', 'get' ) and $nv_Request->isset_request( 'token', 'get' ) )
{
	$token = $nv_Request->get_string( 'token', 'get' );
	$method = $nv_Request->get_string( 'method', 'get' );
 
	if( preg_match( '/^[a-zA-Z0-9_]+$/', $method ) and $token == md5( $order_id . $method . $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php';
	}
	

	 
	// Header( 'Location: ' . $url );
	// die();
}


if( $order_id > 0 and ACTION_METHOD == 'checkout' && $nv_Request->isset_request( 'method', 'get' ) and $nv_Request->isset_request( 'token', 'get' ) )
{
	$token = $nv_Request->get_string( 'token', 'get' );
	$method = $nv_Request->get_string( 'method', 'get' );
 
	if( preg_match( '/^[a-zA-Z0-9_]+$/', $method ) and $token == md5( $order_id . $method . $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php';
		
	}
 
}
elseif( ( ACTION_METHOD == 'checkoutreturn' || ACTION_METHOD == 'success' || ACTION_METHOD == 'failure' ) && $nv_Request->isset_request( 'token', 'get' ) )
{
	$token = $nv_Request->get_string( 'token', 'get' );
 	$method = $nv_Request->get_string( 'method', 'get' );
	if( preg_match( '/^[a-zA-Z0-9_]+$/', $method ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $method . '.php';
	}
	
	
}