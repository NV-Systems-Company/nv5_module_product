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

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$page_title = $lang_ext['heading_title'];
 
$lang_ext = $ProductGeneral->getLangSite( $array_op[1], 'checkout' ); 
 
if( isset( $_SESSION[$module_data . '_order_id'] ) )
{
	// $ProductCart = new NukeViet\Product\Cart( $productRegistry );


	unset( $_SESSION[$module_data . '_shipping_method'] );
	unset( $_SESSION[$module_data . '_shipping_methods'] );
	unset( $_SESSION[$module_data . '_payment_method'] );
	unset( $_SESSION[$module_data . '_payment_methods'] );
	unset( $_SESSION[$module_data . '_guest'] );
	unset( $_SESSION[$module_data . '_comment'] );
	unset( $_SESSION[$module_data . '_order_id'] );
	unset( $_SESSION[$module_data . '_coupon'] );
	unset( $_SESSION[$module_data . '_reward'] );
	unset( $_SESSION[$module_data . '_voucher'] );
	unset( $_SESSION[$module_data . '_vouchers'] );
	unset( $_SESSION[$module_data . '_totals'] );
}

 
if( $globalUserid > 0 )
{
	$dataContent['text_message'] = sprintf( $lang_ext['text_customer'], '#account/account', '#account/order', '#account/download', '#information/contact' );
}
else
{
	$dataContent['text_message'] = sprintf( $lang_ext['text_guest'], '#information/contact' );
}
	$dataContent['continue'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true );
 
$xtpl = new XTemplate( 'ThemeCheckoutSuccess.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );
 
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
