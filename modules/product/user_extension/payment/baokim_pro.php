<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_MOD_PRODUCT' ) ) die( 'Stop!!!' );

$baokim_pro_config = $ProductGeneral->getSetting( 'baokim_pro', $ProductGeneral->store_id );
$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
$lang_plug = $ProductGeneral->getLangSite( 'baokim_pro', 'payment' );
function send()
{
	global $baokim_pro_config;
		$transaction_mode_id = $this->config->get('baokim_pro_transaction');
		$bank_payment_method_id = $this->request->post['baokim_bank_payment_method_id'];
		$business = $this->config->get('baokim_pro_business');

		//$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();
		$order_id = $this->session->data['order_id'];
		$total_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$currency = $order_info['currency_code'];
		$shipping_fee = '';
		$tax_fee = '';
		$order_description = $order_info['comment'];
		$url_success = $this->url->link('payment/baokim_pro/confirm?order_id='.strval(time() . "-" . $order_id), '', 'SSL');
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
			'order_id' => strval(time() . "-" . $order_id),
			'business' => strval($business),
			'total_amount' => strval($total_amount),
			'shipping_fee' => strval($shipping_fee),
			'tax_fee' => strval($tax_fee),
			'order_description' => $order_description,
			'url_success' => strtolower($url_success),
			'url_cancel' => strtolower($url_cancel),
			'url_detail' => strtolower($url_detail),
			'payer_name' => strval($payer_name),
			'payer_email' => strval($payer_email),
			'payer_phone_no' => strval($payer_phone_no),
			'payer_address' => strval($shipping_address),
			'currency_code' =>  strval($currency),
			'bank_payment_method_id' => strval($bank_payment_method_id),
			'transaction_mode_id'=> strval($transaction_mode_id),
			'escrow_timeout' => 3,
		);
		$result = $this->call_API("POST", $data, self::API_PAYMENT_PRO);
		$this->response->setOutput($result);
	}

	public function confirm()
	{
		$message = '';
		$order_id = 0;
		if (isset($this->request->get['order_id'])) {
			$str_id = explode("-", $this->request->get['order_id']);
			$order_id = $str_id[1];
		} else {
			$message = '';
			$order_id = 0;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$this->language->load('payment/baokim_pro');
			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = HTTP_SERVER;
			} else {
				$data['base'] = HTTPS_SERVER;
			}

			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$data['text_response'] = $this->language->get('text_response');
			$data['text_success'] = $this->language->get('text_success');
			$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$data['text_failure'] = $this->language->get('text_failure');
			$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

			if (!empty($order_info)) {
				$this->load->model('checkout/order');
				$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
				$this->model_checkout_order->update($order_id, $this->config->get('baokim_pro_order_status_id'), $message, false);
				$data['continue'] = $this->url->link('checkout/success');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/baokim_success.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/baokim_success.tpl';
				} else {
					$this->template = 'default/template/payment/baokim_success.tpl';
				}

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);

				$this->response->setOutput($this->render());
			} else {
				$data['continue'] = $this->url->link('checkout/cart');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/baokim_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/baokim_failure.tpl';
				} else {
					$this->template = 'default/template/payment/baokim_failure.tpl';
				}

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				$this->response->setOutput($this->render());
			}
		}
	}
function baokim_listener()
{
	global $productRegistry, $ProductGeneral, $module_file;
	include ( NV_ROOTDIR . '/modules/' . $module_file . '/payment/baokim_listener.php' );
	$baokim_listener = new BaokimListener( $productRegistry );
	$baokim_listener->index();
}
/**
	 * Gọi API Bảo Kim thực hiện thanh toán với thẻ ngân hàng
	 *
	 * @param $method Sử dụng phương thức GET, POST cho với từng API
	 * @param $data Dữ liệu gửi đên Bảo Kim
	 * @param $api API được gọi sang Bảo Kim
	 * @return mixed
	 */
	private function call_API($method, $data, $api)
	{
		$business = $this->config->get('baokim_pro_business');
		$username = $this->config->get('baokim_pro_username');
		$password = $this->config->get('baokim_pro_password');
		$private_key = $this->config->get('baokim_pro_signature');
		$server = $this->config->get('baokim_pro_server');
		$arrayPost = array();
		$arrayGet = array();

		ksort($data);
		if ($method == 'GET') {
			$arrayGet = $data;
		} else {
			$arrayPost = $data;
		}

		$signature = $this->makeBaoKimAPISignature($method, $api, $arrayGet, $arrayPost, $private_key);
		$url = $server . $api . '?' . 'signature=' . $signature . (($method == "GET") ? $this->createRequestUrl($data) : '');
		$curl = curl_init($url);

		//	Form
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST | CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->httpBuildQuery(ksort($arrayPost)));
		}

		$result = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$error = curl_error($curl);
		if(empty($result)){
			return array(
				'status'=>$status,
				'error'=>$error
			);
		}

		return $result;
	}
	
/**
	 * Hàm thực hiện việc tạo chữ ký với dữ liệu gửi đến Bảo Kim
	 *
	 * @param $method
	 * @param $url
	 * @param array $getArgs
	 * @param array $postArgs
	 * @param $priKeyFile
	 * @return string
	 */
function makeBaoKimAPISignature( $method, $url, $getArgs = array(), $postArgs = array(), $priKeyFile )
{
	if( strpos( $url, '?' ) !== false )
	{
		list( $url, $get ) = explode( '?', $url );
		parse_str( $get, $get );
		$getArgs = array_merge( $get, $getArgs );
	}
	ksort( $getArgs );
	ksort( $postArgs );
	$method = strtoupper( $method );

	$data = $method . '&' . urlencode( $url ) . '&' . urlencode( http_build_query( $getArgs ) ) . '&' . urlencode( http_build_query( $postArgs ) );

	$priKey = openssl_get_privatekey( $priKeyFile );
	assert( '$priKey !== false' );

	$x = openssl_sign( $data, $signature, $priKey, OPENSSL_ALGO_SHA1 );
	assert( '$x !== false' );
	return urlencode( base64_encode( $signature ) );
}
/**
	 *
	 * @param $formData
	 * @param string $numericPrefix
	 * @param string $argSeparator
	 * @param string $arrName
	 * @return string
	 */	
function httpBuildQuery( $formData, $numericPrefix = '', $argSeparator = '&', $arrName = '' )
{
	$query = array();
	foreach( $formData as $k => $v )
	{
		if( is_int( $k ) ) $k = $numericPrefix . $k;
		if( is_array( $v ) ) $query[] = httpBuildQuery( $v, $numericPrefix, $argSeparator, $k );
		else  $query[] = rawurlencode( empty( $arrName ) ? $k : ( $arrName . '[' . $k . ']' ) ) . '=' . rawurlencode( $v );
	}

	return implode( $argSeparator, $query );
}

function createRequestUrl( $data )
{
	$params = $data;
	ksort( $params );
	$url_params = '';
	foreach( $params as $key => $value )
	{
		if( $url_params == '' ) $url_params .= $key . '=' . urlencode( $value );
		else  $url_params .= '&' . $key . '=' . urlencode( $value );
	}
	return "&" . $url_params;
}

if( ACTION_METHOD == 'checkout' )
{
	
	
	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();
	
	
	$business = $ProductGeneral->config( 'baokim_pro_business', $ProductGeneral->);
	$param = array(
		'business' => $business,
	);
		$call_API = $this->call_API('GET', $param, self::API_SELLER_INFO);

		//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		//echo '<pre>error'.print_r($call_API['error'], true).'</pre>';
		//echo '<pre>tmp'.print_r(isset($call_API['error']), true).'</pre>';
		
		if(isset($call_API['error']) && strlen($call_API['error']) > 2){
			echo "<strong style='color:red'>call_API : ".$call_API['error']."</strong> - " . $this->language->get('error_api');die;
		}
		$seller_info = json_decode($call_API, true);
		if(isset($seller_info['error'])){
			echo "<strong style='color:red'>seller_info: ".$seller_info['error']."</strong> - " . $this->language->get('error_api');die;
		}
		$data['banks'] = $seller_info['bank_payment_methods'];
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_start_date'] = $this->language->get('text_start_date');
		$data['text_issue'] = $this->language->get('text_issue');
		$data['text_wait'] = $this->language->get('text_wait');

		$data['button_confirm'] = $this->language->get('button_confirm');
 

}

if( ACTION_METHOD == 'checkoutReturn' )
{
	//$SECURE_SECRET = $baokim_pro_config['baokim_pro_hash_code'];
	//$vpc_Txn_Secure_Hash = $_GET ["vpc_SecureHash"];
	//unset ( $_GET ["vpc_SecureHash"] );

	// Define Constants
	// ----------------
	// This is secret for encoding the MD5 hash
	// This secret will vary from merchant to merchant
	// To not create a secure hash, let SECURE_SECRET be an empty string - ""
	// $SECURE_SECRET = "secure-hash-secret";
	$SECURE_SECRET = $baokim_pro_config['baokim_pro_hash_code'];

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
		$order_status_id = $baokim_pro_config['config_order_status_id'];
		$transStatus = "";
		if( $hashValidated == "CORRECT" && $txnResponseCode == "0" )
		{
			$transStatus = "Giao dịch thành công";
			$order_status_id = $baokim_pro_config['baokim_pro_completed_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&success';
		}
		elseif( $txnResponseCode != "0" )
		{
			$transStatus = "Giao dịch thất bại";
			$order_status_id = $baokim_pro_config['baokim_pro_failed_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&failed';
		}
		elseif( $hashValidated == "INVALID HASH" )
		{
			$transStatus = "Giao dịch Pendding";
			$order_status_id = $baokim_pro_config['baokim_pro_pending_status_id'];
			$url_redirect = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&pending';
		}
		addOrderHistory($order_id, $order_status_id, $transStatus, true);
 
	}
	header( "Location: " . $url_redirect . "&response=" . $txnResponseCode );
}
