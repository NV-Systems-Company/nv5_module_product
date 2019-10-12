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

if( $ProductGeneral->config['config_active_payment'] == "1" )
{
	// Ngan luong
	include_once NV_ROOTDIR . "/includes/class/nusoap.php";
	$secure_pass = $ProductGeneral->config['config_secure_pass'];
	// Mật khẩu giao tiếp API của Merchant với NgânLượng.vn

	function UpdateOrder( $transaction_info, $order_code, $payment_id, $payment_type, $secure_code )
	{
		global $secure_pass, $db, $db_config, $module_data;
 		// Kiểm tra chuỗi bảo mật
		$secure_code_new = md5( $transaction_info . ' ' . $order_code . ' ' . $payment_id . ' ' . $payment_type . ' ' . $secure_pass );
		if( $secure_code_new != $secure_code )
		{
			//return - 1; // Sai mã bảo mật
		}
		else// Thanh toán thành công
		{
			// Trường hợp là thanh toán tạm giữ. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
			if( $payment_type == 2 )
			{
				// Lập trình thông báo thành công và cập nhật hóa đơn
				$order_code = intval( $order_code );
				$re = $db->query( "UPDATE " . TABLE_PRODUCT_NAME . " SET payment_id=" . $payment_id . " , payment = 1 , payment_type = " . $payment_type . " WHERE id=" . $order_code );
			}
			// Trường hợp thanh toán ngay. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
			elseif( $payment_type == 1 )
			{
				$order_code = intval( $order_code );
				$re = $db->query( "UPDATE " . TABLE_PRODUCT_NAME . " SET payment_id=" . $payment_id . " , payment = 1 , payment_type = " . $payment_type . " WHERE id=" . $order_code );
				// Lập trình thông báo thành công và cập nhật hóa đơn
			}
		}
		return $re;
	}

	function RefundOrder( $transaction_info, $order_code, $payment_id, $refund_payment_id, $payment_type, $secure_code )
	{
		global $secure_pass, $db, $db_config, $module_data;

 
		// Kiểm tra chuỗi bảo mật
		$secure_code_new = md5( $transaction_info . ' ' . $order_code . ' ' . $payment_id . ' ' . $refund_payment_id . ' ' . $secure_pass );
		if( $secure_code_new != $secure_code )
		{
			return - 1;
			// Sai mã bảo mật
		}
		else// Trường hợp hòan trả thành công
		{
			// Lập trình thông báo hoàn trả thành công và cập nhật hóa đơn
			$order_code = intval( $order_code );
			$re = $db->query( "UPDATE " . TABLE_PRODUCT_NAME . " SET payment_id=" . $payment_id . " , payment = 2 , payment_type = " . $payment_type . " WHERE id=" . $order_code );
			// Set payment = 2 la da huy thanh toan
		}
	}

	// Khai bao chung WebService
	$server = new nusoap_server();
	$server->configureWSDL( 'WS_WITH_SMS', NS );
	$server->wsdl->schemaTargetNamespace = NS;

	// Khai bao cac Function
	$server->register( 'UpdateOrder', array(
		'transaction_info' => 'xsd:string',
		'order_code' => 'xsd:string',
		'payment_id' => 'xsd:int',
		'payment_type' => 'xsd:int',
		'secure_code' => 'xsd:string'
	), array( 'result' => 'xsd:int' ), NS );
	$server->register( 'RefundOrder', array(
		'transaction_info' => 'xsd:string',
		'order_code' => 'xsd:string',
		'payment_id' => 'xsd:int',
		'refund_payment_id' => 'xsd:int',
		'payment_type' => 'xsd:int',
		'secure_code' => 'xsd:string'
	), array( 'result' => 'xsd:int' ), NS );

	// Khoi tao Webservice
	$HTTP_RAW_POST_DATA = ( isset( $HTTP_RAW_POST_DATA ) ) ? $HTTP_RAW_POST_DATA : '';
	$server->service( $HTTP_RAW_POST_DATA );

}
else
{
	die( 'Thanh toan khong kich hoat' );
}