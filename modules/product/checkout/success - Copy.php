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

if( isset( $_SESSION[$module_data . '_order_id'] ) )
{
	$_SESSION[$module_data . '_cart'] = array();

	if( defined( 'NV_IS_USER' ) )
	{
 
		$activity_data = array(
			'userid' => $user_info['userid'],
			'name' => $user_info['full_name'],
			'order_id' => $_SESSION[$module_data . '_order_id'] );

		addActivity( 'order_account', $activity_data );
	}
	else
	{
		$activity_data = array( 'name' => $_SESSION[$module_data . '_guest']['first_name'] . ' ' . $_SESSION[$module_data . '_guest']['last_name'], 'order_id' => $_SESSION[$module_data . '_order_id'] );
		addActivity( 'order_guest', $activity_data );

	}

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

$data['heading_title'] = $lang_ext['heading_title'];

$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart';

$base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );

if( defined( 'NV_IS_USER' ) )
{
	$account = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=account';
	$account_order = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=account/order';
	$account_download = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=account/download';
	$contact = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact';

	$data['text_message'] = sprintf( $lang_module['success_text_customer'], $account, $account_order, $account_download, $contact );
}
else
{
	$data['text_message'] = sprintf( $lang_module['success_text_guest'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact' );
}

$data['button_continue'] = $lang_module['button_continue'];

$data['continue'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
 
$contents = checkout_success( $data, $lang_ext );
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
 
