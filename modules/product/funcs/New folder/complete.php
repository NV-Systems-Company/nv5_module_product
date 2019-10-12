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

$contents = "";
$payment = $nv_Request->get_string( 'payment', 'get', '' );

// Kiem tra su ton tai cua cong thanh toan.
if( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php" ) )
{
	// Lay thong tin config neu cong thanh toan duoc kich hoat.
	$stmt = $db->prepare( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE active=1 and payment= :payment" );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();
	if( $stmt->rowCount() )
	{
		$row = $stmt->fetch();
		$payment_config = unserialize( nv_base64_decode( $row['config'] ) );
		$payment_config['paymentname'] = $row['paymentname'];
		$payment_config['domain'] = $row['domain'];

		// Xu ly thong tin
		require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php";
	}
}

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