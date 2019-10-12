<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
 
$onepay_atm_config = $ProductGeneral->getSetting( 'onepay_atm', $ProductGeneral->store_id );

function getMethod_onepay_atm( $address, $total )
{
	global $db, $ProductGeneral, $onepay_atm_config;
	
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'onepay_atm', 'payment' );

	if( $onepay_atm_config['onepay_atm_status'] )
	{

		if( ! $onepay_atm_config['onepay_atm_geo_zone_id'] )
		{
			$status = true;
		}
		elseif( $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$onepay_atm_config['onepay_atm_geo_zone_id'] . ' AND country_id = ' . ( int )$address['country_id'] . ' AND (zone_id = ' . ( int )$address['zone_id'] . ' OR zone_id = 0)' )->fetchColumn() )
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
			'code' => 'onepay_atm',
			'title' => $lang_plug['heading_title'],
			'sort_order' => $onepay_atm_config['onepay_atm_sort_order'] );
	}
	return $method_data;
}

function recurringPayments_onepay_atm() 
{
	/*
	* Used by the checkout to state the module
	* supports recurring recurrings.
	*/
	return true;
}

function null2unknown( $data )
{
	if( $data == "" )
	{
		return "No Value Returned";
	}
	else
	{
		return $data;
	}
}
if( ACTION_METHOD == 'checkout' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'onepay_atm', 'payment' );

	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();
	if( $ProductGeneral->config['config_currency'] != 'VND' )
	{
		$total_amount = $ProductCurrency->convert( $order_info['total'], $ProductGeneral->config['config_currency'], 'VND' );
		$total_amount = $ProductCurrency->format( $total_amount, 'VND', false, false );
	}
	else
	{
		$total_amount = $ProductCurrency->format( $order_info['total'], 'VND', false, false );
	}
	$lang = NV_LANG_DATA;
	if( $lang != 'en' )
	{
		$lang = 'vn';
	}

	//OnePAY create url
	$md5HashData = $onepay_atm_config['onepay_atm_hash_code'];
	$vpcURL = $onepay_atm_config['onepay_atm_url_paygate'] . '?';
	// Mảng các tham số gửi tới OnePAY
	$post_variables = array(
		//'AgainLink'				=>	'nukeviet.onepay.vn',
		'Title' => 'nukeviet.onepay.vn',
		'vpc_Locale' => $lang, //ngôn ngữ hiển thị trên cổng thanh toán
		'vpc_Version' => '2', //Phiên bản modul
		'vpc_Command' => 'pay', //tên hàm
		'vpc_Merchant' => $onepay_atm_config['onepay_atm_merchant_id'], //mã đơn vị(OP cung cấp)
		'vpc_AccessCode' => $onepay_atm_config['onepay_atm_access_code'], //mã truy nhập cổng thanh toán (OP cung cấp)
		'vpc_MerchTxnRef' => date( 'YmdHis' ) . rand(), //ID giao dịch (duy nhất)
		'vpc_OrderInfo' => $order_info['invoice_prefix'], //mã đơn hàng
		//'vpc_Amount'			=>	round( $total_amount*100),//số tiền thanh toán
		'vpc_Amount' => $total_amount * 100, //số tiền thanh toán
		'vpc_ReturnURL' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=checkoutreturn&method=onepay_atm&order_id='.$order_id.'&token=' . md5( $order_id . 'onepay_atm' . $global_config['sitekey'] . session_id() ),
		//'vpc_SHIP_Street01'		=>	html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8'),//html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8'),//địa chỉ ship
		//'vpc_SHIP_Provice'		=>	'',//html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'),//địa chỉ ship
		//'vpc_SHIP_City'			=>	'',//html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'),//địa chỉ ship
		//'vpc_SHIP_Country'		=>	'VNM',//$order_info['payment_iso_code_2'],//địa chỉ ship
		//'vpc_Customer_Phone'	=>	'',//số đt khách hàng
		//'vpc_Customer_Email'	=>	$order_info['email'],//email khách hàng
		//'vpc_userid'		=>	'',//mã khách hàng
		'vpc_TicketNo' => $client_info['ip'], //ip khách hàng
		'vpc_Currency' => $ProductGeneral->config['config_currency'] ); //VND
	ksort( $post_variables );
	$appendAmp = 0;
	$stringHashData = '';
	foreach( $post_variables as $key => $value )
	{
		// tạo chuỗi những tham số có dữ liệu
		if( strlen( $value ) > 0 )
		{
			if( $appendAmp == 0 )
			{
				$vpcURL .= urlencode( $key ) . '=' . urlencode( $value );
				$appendAmp = 1;
			}
			else
			{
				$vpcURL .= '&' . urlencode( $key ) . "=" . urlencode( $value );
			}
			//sử dụng cả tên và giá trị tham số để mã hóa
			if( ( strlen( $value ) > 0 ) && ( ( substr( $key, 0, 4 ) == "vpc_" ) || ( substr( $key, 0, 5 ) == "user_" ) ) )
			{
				$stringHashData .= $key . "=" . $value . "&";
			}
		}
	}
	//xóa ký tự & ở thừa ở cuối chuỗi dữ liệu mã hóa
	$stringHashData = rtrim( $stringHashData, "&" );
	// thêm giá trị chuỗi mã hóa dữ liệu được tạo ra ở trên vào cuối url
	if( strlen( $md5HashData ) > 0 )
	{
		//$vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($stringHashData));
		//Mã hóa dữ liệu
		$vpcURL .= "&vpc_SecureHash=" . strtoupper( hash_hmac( 'SHA256', $stringHashData, pack( 'H*', $md5HashData ) ) );
	}
	//var_dump($vpcURL);
	header( 'Location: '. $vpcURL );
	
	
	// $data['id'] = 'payment';
	// $data['continue'] = $vpcURL;
	
	// $xtpl = new XTemplate( "onepay_atm.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/payment' );
	// $xtpl->assign( 'LANG', $lang_module );
	// $xtpl->assign( 'LANGE', $lang_ext );
	// $xtpl->assign( 'LANGP', $lang_plug );
	// $xtpl->assign( 'DATA', $data );

	// $xtpl->parse( 'main' );
	// $contents = $xtpl->text( 'main' );

	// include NV_ROOTDIR . '/includes/header.php';
	// echo nv_site_theme( $contents );
	// include NV_ROOTDIR . '/includes/footer.php';

}

if( ACTION_METHOD == 'checkoutreturn' )
{
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'onepay_atm', 'payment' );
	
	//$SECURE_SECRET = $onepay_atm_config['onepay_atm_hash_code'];
	//$vpc_Txn_Secure_Hash = $_GET ["vpc_SecureHash"];
	//unset ( $_GET ["vpc_SecureHash"] );

	// Define Constants
	// ----------------
	// This is secret for encoding the MD5 hash
	// This secret will vary from merchant to merchant
	// To not create a secure hash, let SECURE_SECRET be an empty string - ""
	// $SECURE_SECRET = "secure-hash-secret";
	$SECURE_SECRET = $onepay_atm_config['onepay_atm_hash_code'];

	// get and remove the vpc_TxnResponseCode code from the response fields as we
	// do not want to include this field in the hash calculation
	$order_id = $nv_Request->get_int('order_id', 'get', 0 );
	$vpc_Txn_Secure_Hash = $nv_Request->get_string('vpc_SecureHash', 'get', '', 1);
	$vpc_MerchTxnRef = $nv_Request->get_string('vpc_MerchTxnRef', 'get', '', 1);
	$vpc_AcqResponseCode = $nv_Request->get_string('vpc_AcqResponseCode', 'get', '', 1);
	$vpc_TxnResponseCode = $nv_Request->get_string('vpc_TxnResponseCode', 'get', '', 1);
 
	//unset( $_GET["vpc_SecureHash"] );
	// set a flag to indicate if hash has been validated
	$errorExists = false;

	if( strlen( $SECURE_SECRET ) > 0 && $vpc_TxnResponseCode != "7" && $vpc_TxnResponseCode != "No Value Returned" )
	{

		ksort( $_GET );
		//$md5HashData = $SECURE_SECRET;
		//khởi tạo chuỗi mã hóa rỗng
		$md5HashData = "";
		// sort all the incoming vpc response fields and leave out any with no value
		foreach( $_GET as $key => $value )
		{
			//        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
			//            $md5HashData .= $value;
			//        }
			//      chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về
			if( $key != "vpc_SecureHash" && ( strlen( $value ) > 0 ) && ( ( substr( $key, 0, 4 ) == "vpc_" ) || ( substr( $key, 0, 5 ) == "user_" ) ) )
			{
				$md5HashData .= $key . "=" . $value . "&";
			}
		}
		//  Xóa dấu & thừa cuối chuỗi dữ liệu
		$md5HashData = rtrim( $md5HashData, "&" );

		//    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $md5HashData ) )) {
		//    Thay hàm tạo chuỗi mã hóa
		if( strtoupper( $vpc_Txn_Secure_Hash ) == strtoupper( hash_hmac( 'SHA256', $md5HashData, pack( 'H*', $SECURE_SECRET ) ) ) )
		{
			// Secure Hash validation succeeded, add a data field to be displayed
			// later.
			$hashValidated = "CORRECT";
		}
		else
		{
			// Secure Hash validation failed, add a data field to be displayed
			// later.
			$hashValidated = "INVALID HASH";
		}
	}
	else
	{
		// Secure Hash was not validated, add a data field to be displayed later.
		$hashValidated = "INVALID HASH";
	}
	//Lấy các tham số trả về
	$amount = null2unknown( $nv_Request->get_string('vpc_Amount', 'get', '', 1) );
	$locale = null2unknown( $nv_Request->get_string('vpc_Locale', 'get', '', 1) );
	$command = null2unknown( $nv_Request->get_string('vpc_Command', 'get', '', 1) );
	$version = null2unknown( $nv_Request->get_string('vpc_Version', 'get', '', 1) );
	$orderInfo = null2unknown( $nv_Request->get_string('vpc_OrderInfo', 'get', '', 1) );
	$merchantID = null2unknown( $nv_Request->get_string('vpc_Merchant', 'get', '', 1) );
	$merchTxnRef = null2unknown( $nv_Request->get_string('vpc_MerchTxnRef', 'get', '', 1) );
	$transactionNo = null2unknown ( $nv_Request->get_string('vpc_TransactionNo', 'get', '', 1) );
	$txnResponseCode = null2unknown( $nv_Request->get_string('vpc_TxnResponseCode', 'get', '', 1) );
 
	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();
	$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&failed';

	if( $order_info )
	{
		$order_status_id = $onepay_atm_config['config_order_status_id'];
		$transStatus = "";
		if( $hashValidated == "CORRECT" && $txnResponseCode == "0" )
		{
			$transStatus = "Giao dịch thành công";
			$order_status_id = $onepay_atm_config['onepay_atm_completed_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&success';
		}
		elseif( $txnResponseCode != "0" )
		{
			$transStatus = "Giao dịch thất bại";
			$order_status_id = $onepay_atm_config['onepay_atm_failed_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&failed';
		}
		elseif( $hashValidated == "INVALID HASH" )
		{
			$transStatus = "Giao dịch Pendding";
			$order_status_id = $onepay_atm_config['onepay_atm_pending_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&pending';
		}
		addOrderHistory($order_id, $order_status_id, $transStatus, true);
 
	}
	header( "Location: " . $url_redirect . "&response=" . $txnResponseCode );
}
