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

$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );

if( $nv_Request->isset_request( 'login', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/login.php';
}
elseif( $nv_Request->isset_request( 'register', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/register.php';
}
elseif( $nv_Request->isset_request( 'guest', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/guest.php';
}
elseif( $nv_Request->isset_request( 'guest_shipping', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/guest_shipping.php';
}
elseif( $nv_Request->isset_request( 'shipping', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/shipping.php';
}
elseif( $nv_Request->isset_request( 'payment_address', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/payment_address.php';
}
elseif( $nv_Request->isset_request( 'payment_method', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/payment_method.php';
}
elseif( $nv_Request->isset_request( 'shipping_address', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/shipping_address.php';
}
elseif( $nv_Request->isset_request( 'shipping_method', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/shipping_method.php';
}
elseif( $nv_Request->isset_request( 'confirm', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/confirm.php';
}
elseif( $nv_Request->isset_request( 'success', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/success.php';
}
elseif( $nv_Request->isset_request( 'failed', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/failed.php';
}
elseif( $nv_Request->isset_request( 'pending', 'get,post' ) )
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/pending.php';
}

$page_title = $lang_module['cart_check_cart'];

$contents = uers_checkout( $lang_ext );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
