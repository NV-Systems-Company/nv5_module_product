<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$pp_express_config = $ProductGeneral->getSetting( 'pp_express', $ProductGeneral->store_id );
 
function getMethod_pp_express( $address, $total )
{
	global $db, $ProductGeneral, $pp_express_config ;
	
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'pp_express', 'payment' );


	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$pp_express_config['pp_express_geo_zone_id'] . ' AND country_id = ' . ( int )$address['country_id'] . ' AND ( zone_id = ' . ( int )$address['zone_id'] . ' OR zone_id = 0 )' );

	if( $pp_express_config['pp_express_total'] > $total )
	{
		$status = false;
	}
	elseif( ! $pp_express_config['pp_express_geo_zone_id'] ) )
	{
		$status = true;
	}
	elseif( $query->rowCount() )
	{
		$status = true;
	}
	else
	{
		$status = false;
	}

	$method_data = array();

	if( $status )
	{
		$method_data = array(
			'code' => 'pp_express',
			'title' => $lang_plug['heading_title'],
			'terms' => '',
			'sort_order' => $pp_express_config['pp_express_sort_order'] );
	}

	return $method_data;
}
function recurringPayments_pp_express() 
{
	/*
	* Used by the checkout to state the module
	* supports recurring recurrings.
	*/
	return true;
}
function paypal_log( $data, $title = null )
{
	global $ProductGeneral, $pp_express_config;
	
	if( $pp_express_config['pp_express_debug'] )
	{
		file_put_contents( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/payPal_express.log', 'PayPal Express debug (' . $title . '): ' . json_encode( $data ) . " \r\n", FILE_APPEND );

	}
}

function cleanReturn( $data )
{
	$data = explode( '&', $data );

	$arr = array();

	foreach( $data as $k => $v )
	{
		$tmp = explode( '=', $v );
		$arr[$tmp[0]] = urldecode( $tmp[1] );
	}

	return $arr;
}

function paymentRequestInfo()
{
	global $db, $pp_express_config, $ProductGeneral, $productRegistry, $ProductCurrency, $global_config, $ProductContent, $ProductTax, $ProductGeneral->config, $module_data, $module_file, $productCategory, $module_name, $global_config, $lang_module;

	if( empty( $ProductCurrency ) ) $ProductCurrency = new shops_currency( $productRegistry );
	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );
	if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );
	if( empty( $shops_length ) ) $shops_length = new shops_length( $productRegistry );
	if( empty( $shops_weight ) ) $shops_weight = new shops_weight( $productRegistry );
	if( empty( $shops_encyption ) ) $shops_encyption = new shops_encyption( $global_config['sitekey'] );

	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'pp_express', 'payment' );


	$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';
	//*******************************************************************************************
	$currencynotsupported = false;
	$currencies = array(
		'AUD',
		'CAD',
		'EUR',
		'GBP',
		'JPY',
		'USD',
		'NZD',
		'CHF',
		'HKD',
		'SGD',
		'SEK',
		'DKK',
		'PLN',
		'NOK',
		'HUF',
		'CZK',
		'ILS',
		'MXN',
		'MYR',
		'BRL',
		'PHP',
		'TWD',
		'THB',
		'TRY' );
	if( ! in_array( $ProductCurrency->getCode(), $currencies ) )
	{
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
		$currencynotsupported = true;
	}
	else
	{
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $ProductCurrency->getCode();
	}
	//*******************************************************************************************
	$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $pp_express_config['pp_express_method']; // kiểm tra method

	$i = 0;
	$item_total = 0;

	foreach( $ProductContent->getProducts() as $item )
	{
		$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';

		$option_count = 0;
		foreach( $item['option'] as $option )
		{
			if( $option['type'] != 'file' )
			{
				$value = $option['value'];
			}
			else
			{
				$filename = $shops_encyption->decrypt( $option['value'] );
				$value = utf8_substr( $filename, 0, utf8_strrpos( $filename, '.' ) );
			}

			$data['L_PAYMENTREQUEST_0_DESC' . $i] .= ( $option_count > 0 ? ', ' : '' ) . $option['name'] . ':' . ( nv_strlen( $value ) > 20 ? utf8_substr( $value, 0, 20 ) . '..' : $value );

			$option_count++;
		}

		$data['L_PAYMENTREQUEST_0_DESC' . $i] = substr( $data['L_PAYMENTREQUEST_0_DESC' . $i], 0, 126 );

		//*******************************************************************************************
		if( $currencynotsupported )
		{
			$item_price = $ProductCurrency->format( $item['price'], 'USD', false, false );
		}
		else
		{
			$item_price = $ProductCurrency->format( $item['price'], false, false, false );
		}
		//*******************************************************************************************

		$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
		$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $item['model'];
		$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;

		$item_total += number_format( $item_price * $item['quantity'], 2 );

		$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];

		$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $product['link'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $productCategory[$item['category_id']]['alias'] . '/' . $item['alias'] . '-' . $item['product_id'] . $global_config['rewrite_exturl'], true );

		if( $ProductGeneral->config['config_cart_weight'] )
		{
			$weight = $shops_weight->convert( $item['weight'], $item['weight_class_id'], $ProductGeneral->config['config_weight_class_id'] );
			$data['L_PAYMENTREQUEST_0_ITEMWEIGHTVALUE' . $i] = number_format( $weight / $item['quantity'], 2 );
			$data['L_PAYMENTREQUEST_0_ITEMWEIGHTUNIT' . $i] = $shops_weight->getUnit( $ProductGeneral->config['config_weight_class_id'] );
		}

		if( $item['length'] > 0 || $item['width'] > 0 || $item['height'] > 0 )
		{
			$unit = $shops_length->getUnit( $item['length_class_id'] );
			$data['L_PAYMENTREQUEST_0_ITEMLENGTHVALUE' . $i] = $item['length'];
			$data['L_PAYMENTREQUEST_0_ITEMLENGTHUNIT' . $i] = $unit;
			$data['L_PAYMENTREQUEST_0_ITEMWIDTHVALUE' . $i] = $item['width'];
			$data['L_PAYMENTREQUEST_0_ITEMWIDTHUNIT' . $i] = $unit;
			$data['L_PAYMENTREQUEST_0_ITEMHEIGHTVALUE' . $i] = $item['height'];
			$data['L_PAYMENTREQUEST_0_ITEMHEIGHTUNIT' . $i] = $unit;
		}

		$i++;
	}

	if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
	{
		foreach( $_SESSION[$module_data . '_vouchers'] as $voucher )
		{
			$item_total += $ProductCurrency->format( $voucher['amount'], false, false, false );
			$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';
			$data['L_PAYMENTREQUEST_0_NAME' . $i] = $voucher['description'];
			$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = 'VOUCHER';
			$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
			//$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $voucher['amount'], false, false, false );
			//*******************************************************************************************
			if( $currencynotsupported )
			{
				$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $voucher['amount'], 'USD', false, false );
			}
			else
			{
				$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $voucher['amount'], false, false, false );
			}
			//*******************************************************************************************
			$i++;
		}
	}

	$total_data = array();
	$total = 0;
	$taxes = $ProductContent->getTaxes();

	// hiện giá
	if( ( $ProductGeneral->config['config_customer_price'] && defined( 'NV_IS_USER' ) ) || ! $ProductGeneral->config['config_customer_price'] )
	{

		$sort_order = array();
		$results = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'total' ) )->fetchAll();

		foreach( $results as $key => $value )
		{
			$total_config = $ProductGeneral->getSetting( $value['code'], $ProductGeneral->store_id );
			$sort_order[$key] = isset( $total_config[$value['code'] . '_sort_order'] ) ? $total_config[$value['code'] . '_sort_order'] : 0;

		}

		array_multisort( $sort_order, SORT_ASC, $results );

		$array_class = array();

		foreach( $results as $result )
		{
			$total_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );
			if( isset( $total_config[$result['code'] . '_status'] ) && $total_config[$result['code'] . '_status'] )
			{
				$array_class[] = $result['code'];
			}
		}

		foreach( $array_class as $key => &$class )
		{
			${$class} = new $class( $productRegistry );
			${$class}->getTotal( $total_data, $total, $taxes );

		}
		$sort_order = array();

		foreach( $total_data as $key => $value )
		{

			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort( $sort_order, SORT_ASC, $total_data );

	}

	foreach( $total_data as $total_row )
	{
		if( ! in_array( $total_row['code'], array( 'totals', 'sub_total' ) ) )
		{
			if( $total_row['value'] != 0 )
			{
				//$item_price = $ProductCurrency->format( $total_row['value'], false, false, false );
				if( $currencynotsupported )
				{
					$item_price = $ProductCurrency->format( $total_row['value'], 'USD', false, false );
				}
				else
				{
					$item_price = $ProductCurrency->format( $total_row['value'], false, false, false );
				}
				$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $total_row['code'];
				$data['L_PAYMENTREQUEST_0_NAME' . $i] = $total_row['title'];
				//$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $total_row['value'], false, false, false );
				if( $currencynotsupported )
				{
					$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $total_row['value'], 'USD', false, false );
				}
				else
				{
					$data['L_PAYMENTREQUEST_0_AMT' . $i] = $ProductCurrency->format( $total_row['value'], false, false, false );
				}
				$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;

				$item_total = $item_total + $item_price;
				$i++;
			}
		}
	}

	$data['PAYMENTREQUEST_0_ITEMAMT'] = number_format( $item_total, 2, '.', '' );
	$data['PAYMENTREQUEST_0_AMT'] = number_format( $item_total, 2, '.', '' );

	$z = 0;

	$recurring_products = $ProductContent->getRecurringProducts();

	if( $recurring_products )
	{

		foreach( $recurring_products as $item )
		{
			$data['L_BILLINGTYPE' . $z] = 'RecurringPayments';

			if( $item['recurring']['trial'] )
			{
				//$trial_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['trial_price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();

				if( $currencynotsupported )
				{
					$trial_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['trial_price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), 'USD', false, false ) * $item['quantity'] . ' ' . 'USD';
				}
				else
				{
					$trial_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['trial_price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
				}

				$trial_text = sprintf( $lang_ext['text_trial'], $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration'] );
			}
			else
			{
				$trial_text = '';
			}

			//$recurring_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
			if( $currencynotsupported )
			{
				$recurring_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), 'USD', false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
			}
			else
			{
				$recurring_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
			}

			$recurring_description = $trial_text . sprintf( $lang_ext['text_recurring'], $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency'] );

			if( $item['recurring']['duration'] > 0 )
			{
				$recurring_description .= sprintf( $lang_ext['text_length'], $item['recurring']['duration'] );
			}

			$data['L_BILLINGAGREEMENTDESCRIPTION' . $z] = $recurring_description;
			$z++;
		}
	}

	// $ProductCurrency->clear();
	// $ProductContent->clear();
	// $ProductTax->clear();
	// $shops_length->clear();
	// $shops_weight->clear();
	var_dump( $data );
	die();
	return $data;
}

function paymentcall( $data )
{
	global $pp_express_config;

	if( $pp_express_config['pp_express_test'] == 1 )
	{
		$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
	}
	else
	{
		$api_endpoint = 'https://api-3t.paypal.com/nvp';
	}

	$settings = array(
		'USER' => $pp_express_config['pp_express_username'],
		'PWD' => $pp_express_config['pp_express_password'],
		'SIGNATURE' => $pp_express_config['pp_express_signature'],
		'VERSION' => '109.0',
		'BUTTONSOURCE' => 'Nukeviet 4x',
		);

	paypal_log( $data, 'Call data' );

	$defaults = array(
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_URL => $api_endpoint,
		CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
		CURLOPT_FRESH_CONNECT => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FORBID_REUSE => 1,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_POSTFIELDS => http_build_query( array_merge( $data, $settings ), '', "&" ) );

	$ch = curl_init();

	curl_setopt_array( $ch, $defaults );

	if( ! $result = curl_exec( $ch ) )
	{
		paypal_log( array( 'error' => curl_error( $ch ), 'errno' => curl_errno( $ch ) ), 'cURL failed' );
	}

	paypal_log( $result, 'Result' );

	curl_close( $ch );

	return cleanReturn( $result );
}

function addPaypalOrder( $order_data )
{
	global $db;

	$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_paypal_order SET
		order_id = ' . ( int )$order_data['order_id'] . ',
		date_added = ' . NV_CURRENTTIME . ',
		date_modified = ' . NV_CURRENTTIME . ',
		capture_status = ' . $db->quote( $order_data['capture_status'] ) . ',
		currency_code = ' . $db->quote( $order_data['currency_code'] ) . ',
		total = ' . ( float )$order_data['total'] . ',
		authorization_id = ' . $db->quote( $order_data['authorization_id'] ) );

	return $db->lastInsertId();
}

function addTransaction( $transaction_data )
{
	global $db;
	/**
	 * 1 to many relationship with paypal order table, many transactions per 1 order
	 */

	$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_paypal_order_transaction SET
		paypal_order_id = ' . ( int )$transaction_data['paypal_order_id'] . ',
		transaction_id = ' . $db->quote( $transaction_data['transaction_id'] ) . ',
		parent_transaction_id = ' . $db->quote( $transaction_data['parent_transaction_id'] ) . ',
		date_added = ' . NV_CURRENTTIME . ',
		note = ' . $db->quote( $transaction_data['note'] ) . ',
		msgsubid = ' . $db->quote( $transaction_data['msgsubid'] ) . ',
		receipt_id = ' . $db->quote( $transaction_data['receipt_id'] ) . ',
		payment_type = ' . $db->quote( $transaction_data['payment_type'] ) . ',
		payment_status = ' . $db->quote( $transaction_data['payment_status'] ) . ',
		pending_reason = ' . $db->quote( $transaction_data['pending_reason'] ) . ',
		transaction_entity = ' . $db->quote( $transaction_data['transaction_entity'] ) . ',
		amount = ' . ( float )$transaction_data['amount'] . ',
		debug_data = ' . $db->quote( $transaction_data['debug_data'] ) );
}

function create( $item, $order_id, $description )
{
	global $db;

	$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_recurring SET 
		order_id = ' . ( int )$order_id . ', 
		date_added = ' . NV_CURRENTTIME . ', 
		status = 6, 
		product_id = ' . ( int )$item['product_id'] . ', 
		product_name = ' . $db->quote( $item['name'] ) . ', 
		product_quantity = ' . $db->quote( $item['quantity'] ) . ', 
		recurring_id = ' . ( int )$item['recurring_id'] . ', 
		recurring_name = ' . $db->quote( $item['recurring_name'] ) . ', 
		recurring_description = ' . $db->quote( $description ) . ', 
		recurring_frequency = ' . $db->quote( $item['recurring_frequency'] ) . ', 
		recurring_cycle = ' . ( int )$item['recurring_cycle'] . ', 
		recurring_duration = ' . ( int )$item['recurring_duration'] . ', 
		recurring_price = ' . ( float )$item['recurring_price'] . ', 
		trial = ' . ( int )$item['recurring_trial'] . ', 
		trial_frequency = ' . $db->quote( $item['recurring_trial_frequency'] ) . ', 
		trial_cycle = ' . ( int )$item['recurring_trial_cycle'] . ', 
		trial_duration = ' . ( int )$item['recurring_trial_duration'] . ', 
		trial_price = ' . ( float )$item['recurring_trial_price'] . ', 
		reference = \'\' ' );

	return $db->lastInsertId();
}

function addReference( $recurring_id, $ref )
{
	global $db;

	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_order_recurring SET reference = ' . $db->quote( $ref ) . ' WHERE order_recurring_id = ' . ( int )$recurring_id );

	if( $db->rowCount() > 0 )
	{
		return true;
	}
	else
	{
		return false;

	}
}

if( ACTION_METHOD == 'checkout' )
{

	//*******************************************************************************************
	$currencynotsupported = false;
	$currencies = array(
		'AUD',
		'CAD',
		'EUR',
		'GBP',
		'JPY',
		'USD',
		'NZD',
		'CHF',
		'HKD',
		'SGD',
		'SEK',
		'DKK',
		'PLN',
		'NOK',
		'HUF',
		'CZK',
		'ILS',
		'MXN',
		'MYR',
		'BRL',
		'PHP',
		'TWD',
		'THB',
		'TRY' );

	if( ! in_array( $ProductCurrency->getCode(), $currencies ) )
	{
		$currencynotsupported = true;
	}
	//*******************************************************************************************

	$order_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id=' . $order_id )->fetch();

	$max_amount = $ProductCurrency->convert( $order_info['total'], $ProductGeneral->config['config_currency'], 'USD' );

	$max_amount = min( $max_amount * 1.25, 10000 );

	if( $currencynotsupported )
	{
		$max_amount = $ProductCurrency->format( $max_amount, 'USD', false, false );
	}
	else
	{
		$max_amount = $ProductCurrency->format( $max_amount, $ProductCurrency->getCode(), '', false );
	}
	//$max_amount = $ProductCurrency->format( $max_amount, $ProductCurrency->getCode(), '', false );

	$data = array(
		'METHOD' => 'SetExpressCheckout',
		'MAXAMT' => $max_amount,
		'RETURNURL' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=checkoutReturn&method=pp_express',
		'CANCELURL' => NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true ),
		'REQCONFIRMSHIPPING' => '0',
		'NOSHIPPING' => 1,
		'LOCALECODE' => 'EN',
		'LANDINGPAGE' => 'Login',
		'HDRIMG' => $pp_express_config['pp_express_logo'],
		'PAYFLOWCOLOR' => $pp_express_config['pp_express_page_colour'],
		'CHANNELTYPE' => 'Merchant',
		'ALLOWNOTE' => $pp_express_config['pp_express_allow_note'] );

	//$test = sprintf( $lang_global['memory_time_usage'] , nv_convertfromBytes( memory_get_usage() ), number_format( ( microtime( true ) - NV_START_TIME ), 3, '.', '' ) );
	// var_dump($data);
	$data = array_merge( $data, paymentRequestInfo() );

	$result = paymentcall( $data );

	// var_dump($result);
	// die();
	/**
	 * If a failed PayPal setup happens, handle it.
	 */
	if( ! isset( $result['TOKEN'] ) )
	{
		$_SESSION[$module_data . '_error'] = $result['L_LONGMESSAGE0'];

		/**
		 * Unable to add error message to user as the session errors/success are not
		 * used on the cart or checkout pages - need to be added?
		 * If PayPal debug log is off then still log error to normal error log.
		 */
		if( $pp_express_config['pp_express_debug'] == 1 )
		{
			file_put_contents( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/PayPal_Express_debug.log', "" . serialize( $result ) . " \r\n", FILE_APPEND );
		}

		//header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout' );

	}

	$_SESSION[$module_data . '_paypal']['token'] = $result['TOKEN'];

	if( $pp_express_config['pp_express_test'] == 1 )
	{
		header( 'Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'] . '&useraction=commit' );
	}
	else
	{
		header( 'Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'] . '&useraction=commit' );
	}
	exit();
}

if( ACTION_METHOD == 'checkoutReturn' )
{
	$data = array( 'METHOD' => 'GetExpressCheckoutDetails', 'TOKEN' => $_SESSION[$module_data . '_paypal']['token'] );

	$result = paymentcall( $data );

	$_SESSION[$module_data . '_paypal']['payerid'] = $result['PAYERID'];
	$_SESSION[$module_data . '_paypal']['result'] = $result;

	$order_id = $_SESSION[$module_data . '_order_id'];

	$paypal_data = array(
		'TOKEN' => $_SESSION[$module_data . '_paypal']['token'],
		'PAYERID' => $_SESSION[$module_data . '_paypal']['payerid'],
		'METHOD' => 'DoExpressCheckoutPayment',
		'PAYMENTREQUEST_0_NOTIFYURL' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=ipn&method=pp_express',
		'RETURNFMFDETAILS' => 1 );

	$paypal_data = array_merge( $paypal_data, paymentRequestInfo() );

	$result = paymentcall( $paypal_data );

	if( $result['ACK'] == 'Success' )
	{
		//handle order status
		switch( $result['PAYMENTINFO_0_PAYMENTSTATUS'] )
		{
			case 'Canceled_Reversal':
				$order_status_id = $pp_express_config['pp_express_canceled_reversal_status_id'];
				break;
			case 'Completed':
				$order_status_id = $pp_express_config['pp_express_completed_status_id'];
				break;
			case 'Denied':
				$order_status_id = $pp_express_config['pp_express_denied_status_id'];
				break;
			case 'Expired':
				$order_status_id = $pp_express_config['pp_express_expired_status_id'];
				break;
			case 'Failed':
				$order_status_id = $pp_express_config['pp_express_failed_status_id'];
				break;
			case 'Pending':
				$order_status_id = $pp_express_config['pp_express_pending_status_id'];
				break;
			case 'Processed':
				$order_status_id = $pp_express_config['pp_express_processed_status_id'];
				break;
			case 'Refunded':
				$order_status_id = $pp_express_config['pp_express_refunded_status_id'];
				break;
			case 'Reversed':
				$order_status_id = $pp_express_config['pp_express_reversed_status_id'];
				break;
			case 'Voided':
				$order_status_id = $pp_express_config['pp_express_voided_status_id'];
				break;
		}

		addOrderHistory( $order_id, $order_status_id );

		//add order to paypal table
		$paypal_order_data = array(
			'order_id' => $order_id,
			'capture_status' => ( $pp_express_config['pp_express_method'] == 'Sale' ? 'Complete' : 'NotComplete' ),
			'currency_code' => $result['PAYMENTINFO_0_CURRENCYCODE'],
			'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
			'total' => $result['PAYMENTINFO_0_AMT'] );

		$paypal_order_id = addPaypalOrder( $paypal_order_data );

		//add transaction to paypal transaction table
		$paypal_transaction_data = array(
			'paypal_order_id' => $paypal_order_id,
			'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
			'parent_transaction_id' => '',
			'note' => '',
			'msgsubid' => '',
			'receipt_id' => ( isset( $result['PAYMENTINFO_0_RECEIPTID'] ) ? $result['PAYMENTINFO_0_RECEIPTID'] : '' ),
			'payment_type' => $result['PAYMENTINFO_0_PAYMENTTYPE'],
			'payment_status' => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
			'pending_reason' => $result['PAYMENTINFO_0_PENDINGREASON'],
			'transaction_entity' => ( $pp_express_config['pp_express_method'] == 'Sale' ? 'payment' : 'auth' ),
			'amount' => $result['PAYMENTINFO_0_AMT'],
			'debug_data' => json_encode( $result ) );

		addTransaction( $paypal_transaction_data );

		$recurring_products = $ProductContent->getRecurringProducts();

		//loop through any products that are recurring items
		if( $recurring_products )
		{

			$billing_period = array(
				'day' => 'Day',
				'week' => 'Week',
				'semi_month' => 'SemiMonth',
				'month' => 'Month',
				'year' => 'Year' );

			foreach( $recurring_products as $item )
			{
				$data = array(
					'METHOD' => 'CreateRecurringPaymentsProfile',
					'TOKEN' => $_SESSION[$module_data . '_paypal']['token'],
					'PROFILESTARTDATE' => gmdate( "Y-m-d\TH:i:s\Z", mktime( gmdate( 'H' ), gmdate( 'i' ) + 5, gmdate( 's' ), gmdate( 'm' ), gmdate( 'd' ), gmdate( 'y' ) ) ),
					'BILLINGPERIOD' => $billing_period[$item['recurring']['frequency']],
					'BILLINGFREQUENCY' => $item['recurring']['cycle'],
					'TOTALBILLINGCYCLES' => $item['recurring']['duration'],
					'AMT' => $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'],
					'CURRENCYCODE' => $ProductCurrency->getCode() );

				//trial information
				if( $item['recurring']['trial'] == 1 )
				{
					$data_trial = array(
						'TRIALBILLINGPERIOD' => $billing_period[$item['recurring']['trial_frequency']],
						'TRIALBILLINGFREQUENCY' => $item['recurring']['trial_cycle'],
						'TRIALTOTALBILLINGCYCLES' => $item['recurring']['trial_duration'],
						'TRIALAMT' => $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get( 'config_tax' ) ), false, false, false ) * $item['quantity'] );

					$trial_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get( 'config_tax' ) ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
					$trial_text = sprintf( $lang_ext['text_trial'], $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration'] );

					$data = array_merge( $data, $data_trial );
				}
				else
				{
					$trial_text = '';
				}

				$recurring_amt = $ProductCurrency->format( $ProductTax->calculate( $item['recurring']['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), false, false, false ) * $item['quantity'] . ' ' . $ProductCurrency->getCode();
				$recurring_description = $trial_text . sprintf( $lang_ext['text_recurring'], $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency'] );

				if( $item['recurring']['duration'] > 0 )
				{
					$recurring_description .= sprintf( $lang_ext['text_length'], $item['recurring']['duration'] );
				}

				//create new recurring and set to pending status as no payment has been made yet.
				$recurring_id = create( $item, $order_id, $recurring_description );

				$data['PROFILEREFERENCE'] = $recurring_id;
				$data['DESC'] = $recurring_description;

				$result = paymentcall( $data );

				if( isset( $result['PROFILEID'] ) )
				{
					addReference( $recurring_id, $result['PROFILEID'] );
				}
				else
				{
					// there was an error creating the recurring, need to log and also alert admin / user

				}
			}
		}

		if( isset( $result['REDIRECTREQUIRED'] ) && $result['REDIRECTREQUIRED'] == true )
		{
			//- handle german redirect here
			header( 'Location: https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token=' . $_SESSION[$module_data . '_paypal']['token'] );

		}
		else
		{
			$link_checkout_success = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&success';

			header( 'Location: ' . $link_checkout_success );

		}
	}
	else
	{

		if( $result['L_ERRORCODE0'] == '10486' )
		{
			if( isset( $_SESSION[$module_data . '_paypal_redirect_count'] ) )
			{

				if( $_SESSION[$module_data . '_paypal_redirect_count'] == 2 )
				{
					$_SESSION[$module_data . '_paypal_redirect_count'] = 0;
					$_SESSION[$module_data . '_error'] = $lang_ext['error_too_many_failures'];

					$link_checkout = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );

					header( 'Location: ' . $link_checkout );

				}
				else
				{
					++$_SESSION[$module_data . '_paypal_redirect_count'];
				}
			}
			else
			{
				$_SESSION[$module_data . '_paypal_redirect_count'] = 1;
			}

			if( $ProductGeneral->config['pp_express_test'] == 1 )
			{
				header( 'Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $_SESSION[$module_data . '_paypal']['token'] );
			}
			else
			{
				header( 'Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $_SESSION[$module_data . '_paypal']['token'] );

			}
		}

		$data['heading_title'] = $lang_ext['error_heading_title'];

		$data['text_error'] = '<div class="warning">' . $result['L_ERRORCODE0'] . ' : ' . $result['L_LONGMESSAGE0'] . '</div>';

		$data['button_continue'] = $lang_ext['button_continue'];

		$data['continue'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );

		unset( $_SESSION[$module_data . '_success'] );

		$contents = call_user_func( 'payment_not_found', $data );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';

	}
}
