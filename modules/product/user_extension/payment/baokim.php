<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$baokim_config = $ProductGeneral->getSetting( 'payment_baokim', $ProductGeneral->store_id );


function getMethod_baokim( $address, $total )
{
	global $db, $ProductGeneral, $baokim_config ;
	
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' );
	
	if( $baokim_config['payment_baokim_status'] )
	{

		if( ! $baokim_config['payment_baokim_geo_zone_id'] )
		{
			$status = true;
		}
		elseif( $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$baokim_config['payment_baokim_geo_zone_id'] . ' AND country_id = ' . ( int )$address['country_id'] . ' AND (zone_id = ' . ( int )$address['zone_id'] . ' OR zone_id = 0)' )->fetchColumn() )
		{
			$status = true;
		}
		else
		{
			$status = false;
		}
	}
	else
	{
		$status = false;
	}

	$method_data = array();

	if( $status )
	{
		$method_data = array(
			'code' => 'baokim',
			'title' => $lang_plug['heading_title'],
			'sort_order' => $baokim_config['payment_baokim_sort_order'] );
	}
	return $method_data;
}

function recurringPayments_baokim() 
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' );
	/*
	* Used by the checkout to state the module
	* supports recurring recurrings.
	*/
	return true;
}
function createRequestUrl( $data, $redirect_url, $secure_pass )
{
	// Mảng các tham số chuyển tới baokim.vn
	$params = $data;
	ksort( $params );

	$params['checksum'] = hash_hmac( 'SHA1', implode( '', $params ), $secure_pass );

	//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
	if( strpos( $redirect_url, '?' ) === false )
	{
		$redirect_url .= '?';
	}
	else
		if( substr( $redirect_url, strlen( $redirect_url ) - 1, 1 ) != '?' && strpos( $redirect_url, '&' ) === false )
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';
		}

	// Tạo đoạn url chứa tham số
	$url_params = '';
	foreach( $params as $key => $value )
	{
		if( $url_params == '' ) $url_params .= $key . '=' . urlencode( $value );
		else  $url_params .= '&' . $key . '=' . urlencode( $value );
	}
	return $redirect_url . $url_params;
}

/**
 * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
 * @param $url_params       chứa tham số trả về trên url
 * @param $secure_pass      Mã bảo mật
 * @return true             True  -  thông tin là chính xác,
 *                          False -  thông tin không chính xác
 */
function verifyResponseUrl( $url_params = array(), $secure_pass )
{
	if( empty( $url_params['checksum'] ) )
	{
		echo "invalid parameters: checksum is missing";
		return false;
	}

	$checksum = $url_params['checksum'];
	unset( $url_params['checksum'] );
 
	ksort( $url_params );
 
	if( strcasecmp( $checksum, hash_hmac( 'SHA1', implode( '', $url_params ), $secure_pass ) ) === 0 ) return true;
	else  return false;
}

if( ACTION_METHOD == 'checkout' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' );

	$order_id = $_SESSION[$module_data . '_order_id'];

	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();

	$merchant_id = $baokim_config['payment_baokim_merchant'];
	$secure_pass = $baokim_config['payment_baokim_security'];
	$business = $baokim_config['payment_baokim_business'];
	$server = $baokim_config['payment_baokim_server'];

	$total_amount = $ProductCurrency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false );
	$shipping_fee = '';
	$tax_fee = '';
	$order_description = $order_info['comment'];
	$url_success = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=checkoutreturn&method=baokim&order_id=' . $order_id . '&token=' . md5( $order_id . 'baokim' . $global_config['sitekey'] . session_id() );
	$url_cancel = '';
	$url_detail = '';
	$payer_name = $order_info['last_name'] . ' ' . $order_info['first_name'];
	$payer_email = $order_info['email'];
	$payer_phone_no = $order_info['telephone'];
	$shipping_address = $order_info['payment_address_1'];

	/**
	 * @param $order_id            Mã đơn hàng
	 * @param $business            Email tài khoản người bán
	 * @param $total_amount        Giá trị đơn hàng
	 * @param $shipping_fee        Phí vận chuyển
	 * @param $tax_fee             Thuế
	 * @param $order_description   Mô tả đơn hàng
	 * @param $url_success         Url trả về khi thanh toán thành công
	 * @param $url_cancel          Url trả về khi hủy thanh toán
	 * @param $url_detail          Url chi tiết đơn hàng
	 */
	$data = array(
		'merchant_id' => strval( $merchant_id ),
		'order_id' => strval( time() . "-" . $order_id ),
		'business' => strval( $business ),
		'total_amount' => strval( $total_amount ),
		'shipping_fee' => strval( $shipping_fee ),
		'tax_fee' => strval( $tax_fee ),
		'order_description' => $order_description,
		'url_success' => strtolower( $url_success ),
		'url_cancel' => strtolower( $url_cancel ),
		'url_detail' => strtolower( $url_detail ),
		'payer_name' => strval( $payer_name ),
		'payer_email' => strval( $payer_email ),
		'payer_phone_no' => strval( $payer_phone_no ),
		'shipping_address' => strval( $shipping_address ),
		'currency' => strval( $order_info['currency_code'] ),
		);
	//$data['continue'] = HTTPS_SERVER . 'checkout_bk.php?email=' . $business . '&total_amount=' . $total_amount . '&order_id=' . $order_id . '&merchant_id=' . $merchant_id . '&secure_pass=' . $secure_pass . '&url_success=' . $url_success;
	$data['continue'] = createRequestUrl( $data, $server, $secure_pass );
	header( 'Location: ' . $data['continue'] );

	// $xtpl = new XTemplate( "baokim.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/payment' );
	// $xtpl->assign( 'LANG', $lang_module );
	// $xtpl->assign( 'LANGE', $lang_ext );
	// $xtpl->assign( 'LANGP', $lang_plug );
	// $xtpl->assign( 'DATA', $data );

	// $xtpl->parse( 'main' );
	// $contents = $xtpl->text( 'main' );
	// echo $contents;
	// exit();
	// include NV_ROOTDIR . '/includes/header.php';
	// echo nv_site_theme( $contents );
	// include NV_ROOTDIR . '/includes/footer.php';

}

if( ACTION_METHOD == 'checkoutreturn' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' );

	$secure_pass = $baokim_config['baokim_security'];
	$server = $baokim_config['baokim_server'];

	$message = '';
	$order_string_id = $nv_Request->get_string( 'order_id', 'get', '' );

	if( ! empty( $order_string_id ) )
	{
		$str_id = explode( '-', $order_string_id );  
		$order_id = intval( $str_id[1] );
	}
	else
	{
		$message = '';
		$order_id = 0;
	}

	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();
	$transStatus = '';
	if( verifyResponseUrl( $_GET, $secure_pass ) )
	{
		$status = true;
	}
	else
	{
		$status = false;
	}
// var_dump( $secure_pass );
// var_dump( $status );
// var_dump( $_GET );
 
	if( $order_info )
	{
		// 1: giao dịch chưa xác minh OTP
		// 2: giao dịch đã xác minh OTP
		// 4: giao dịch hoàn thành
		// 5: giao dịch bị hủy
		// 6: giao dịch bị từ chối nhận tiền
		// 7: giao dịch hết hạn
		// 8: giao dịch thất bại
		// 12: giao dịch bị đóng băng
		// 13: giao dịch bị tạm giữ (thanh toán an toàn)
		// X: các trạng thái giao dịch khác
		$transaction_status = $nv_Request->get_int( 'transaction_status', 'get', 0 );

		$order_status_id = $baokim_config['baokim_order_status_id'];

		if( $transaction_status == 1 )
		{
			$transStatus = 'giao dịch chưa xác minh OTP';
		}
		elseif( $transaction_status == 2 )
		{
			$transStatus = 'giao dịch đã xác minh OTP';
		}
		elseif( $transaction_status == 4 )
		{
			$transStatus = 'Giao dịch thành công';
		}
		elseif( $transaction_status == 5 )
		{
			$transStatus = 'giao dịch bị từ chối nhận tiền';
		}
		elseif( $transaction_status == 6 )
		{
			$transStatus = 'giao dịch bị hủy';
		}
		elseif( $transaction_status == 7 )
		{
			$transStatus = 'giao dịch hết hạn';
		}
		elseif( $transaction_status == 8 )
		{
			$transStatus = 'giao dịch thất bại';
		}
		elseif( $transaction_status == 12 )
		{
			$transStatus = "giao dịch bị đóng băng";
		}
		elseif( $transaction_status == 13 )
		{
			$transStatus = "giao dịch bị tạm giữ (thanh toán an toàn)";
		}
		else
		{
			$transStatus = "Giao dịch chưa xác định";
		}

		if( $status )
		{

			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=success&method=baokim&transaction_status=' . $transaction_status . '&order_id=' . $order_id . '&token=' . md5( $order_id . 'baokim' . $global_config['sitekey'] . session_id() );

		}
		else
		{
			$transStatus = "Dữ liệu từ Bảo Kim không hợp lệ !";
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=failure&method=baokim&transaction_status=' . $transaction_status . '&order_id=' . $order_id . '&token=' . md5( $order_id . 'baokim' . $global_config['sitekey'] . session_id() );
		}
		addOrderHistory( $order_id, $order_status_id, $transStatus, true );
		Header( 'Location: ' . $url_redirect );
		die();
	}
 
 
}

if( ACTION_METHOD == 'failure' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' ); 
	
	$data['title'] = sprintf( $lang_plug['heading_title'], $ProductGeneral->config['config_name'] );
	//$data['base'] = HTTP_SERVER;
	$data['heading_title'] = sprintf( $lang_plug['heading_title'], $ProductGeneral->config['config_name']);
	$data['text_response'] = $lang_plug['text_response'];
	$data['text_failure'] = $lang_plug['text_failure'];
	$data['text_failure_wait'] = sprintf( $lang_plug['text_failure_wait'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart');
	$data['continue'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart';
	$xtpl = new XTemplate( "baokim_failure.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/payment' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'LANGP', $lang_plug );
	$xtpl->assign( 'DATA', $data );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

if( ACTION_METHOD == 'success' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'baokim', 'payment' );

	$data['title'] = sprintf( $lang_plug['heading_title'], $ProductGeneral->config['config_name'] );
	//$data['base'] = HTTP_SERVER;
	$data['language'] = NV_LANG_DATA;
	$data['heading_title'] = sprintf( $lang_plug['heading_title'], $ProductGeneral->config['config_name']);
	$data['text_response'] = $lang_plug['text_response'];
	$data['text_success'] = $lang_plug['text_success'];
	$data['text_success_wait'] = sprintf( $lang_plug['text_success_wait'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&action=success' );
	$data['continue'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&action=success';
	
	
	$xtpl = new XTemplate( "baokim_success.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/payment' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'LANGP', $lang_plug );
	$xtpl->assign( 'DATA', $data );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

}
