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

if( ! defined( 'NV_IS_USER' ) )
{
	$redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cart";
	Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $redirect ) );
	die();
}

$data_content = array();

$sql = "SELECT order_id, order_code, order_note, user_id, unit_total, order_total, order_time, transaction_status, transaction_id, transaction_count FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE user_id = " . $user_info["userid"] . " ORDER BY order_id DESC";
$result = $db->query( $sql );

$link_module = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

while( list( $order_id, $order_code, $order_note, $user_id, $unit_total, $order_total, $order_time, $transaction_status, $transaction_id, $transaction_count ) = $result->fetch( 3 ) )
{
	$checkss = md5( $order_id . $global_config['sitekey'] . session_id() );
	$data_content[] = array(
		"order_id" => $order_id,
		"order_code" => $order_code,
		"transaction_status" => $transaction_status,
		"transaction_id" => $transaction_id,
		"transaction_count" => $transaction_count,
		"order_note" => $order_note,
		"user_id" => $user_id,
		"unit_total" => $unit_total,
		"order_total" => $order_total,
		"order_time" => $order_time,
		"link" => $link_module . "&amp;" . NV_OP_VARIABLE . "=payment&amp;order_id=" . $order_id . "&checkss=" . $checkss,
		"link_remove" => $link_module . "&amp;" . NV_OP_VARIABLE . "=delhis&amp;order_id=" . $order_id . "&checkss=" . $checkss
	);
}

$link_check_order = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkorder&checkss=" . md5( $user_info["userid"] . $global_config['sitekey'] . session_id() );
$contents = call_user_func( "history_order", $data_content, $link_check_order );

$page_title = $lang_module['history_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
$productCategory = null;
$global_product_group = null;
$global_stock_status = null;
$global_brand = null;
$global_customer_group = null;
$ProductGeneral->config = null;
$shops_cart= null;
$shops_coupon= null;
$ProductTax = null;
$ProductContent= null;
$data_content = null;
$ProductCurrency = null;
$ProductGeneral= null;
include NV_ROOTDIR . '/includes/footer.php';