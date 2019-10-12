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

function addOrder( $data )
{
	global $db, $ProductCurrency, $user_info, $productRegistry, $module_name, $lang_module;

	try
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order SET 
		invoice_prefix = ' . $db->quote( $data['invoice_prefix'] ) . ', 
		store_id = ' . ( int )$data['store_id'] . ', 
		store_name = ' . $db->quote( $data['store_name'] ) . ', 
		store_url = ' . $db->quote( $data['store_url'] ) . ', 
		userid = ' . ( int )$data['userid'] . ', 
		customer_group_id = ' . ( int )$data['customer_group_id'] . ', 
		first_name = ' . $db->quote( $data['first_name'] ) . ', 
		last_name = ' . $db->quote( $data['last_name'] ) . ', 
		email = ' . $db->quote( $data['email'] ) . ', 
		telephone = ' . $db->quote( $data['telephone'] ) . ', 
		fax = ' . $db->quote( $data['fax'] ) . ', 
		custom_field = ' . $db->quote( isset( $data['custom_field'] ) ? serialize( $data['custom_field'] ) : '' ) . ', 
		payment_first_name = ' . $db->quote( $data['payment_first_name'] ) . ', 
		payment_last_name = ' . $db->quote( $data['payment_last_name'] ) . ', 
		payment_company = ' . $db->quote( $data['payment_company'] ) . ', 
		payment_address_1 = ' . $db->quote( $data['payment_address_1'] ) . ', 
		payment_address_2 = ' . $db->quote( $data['payment_address_2'] ) . ', 
		payment_city = ' . $db->quote( $data['payment_city'] ) . ', 
		payment_postcode = ' . $db->quote( $data['payment_postcode'] ) . ', 
		payment_country = ' . $db->quote( $data['payment_country'] ) . ', 
		payment_country_id = ' . ( int )$data['payment_country_id'] . ', 
		payment_zone = ' . $db->quote( $data['payment_zone'] ) . ', 
		payment_zone_id = ' . ( int )$data['payment_zone_id'] . ',
		payment_address_format = ' . $db->quote( $data['payment_address_format'] ) . ', 
		payment_custom_field = ' . $db->quote( isset( $data['payment_custom_field'] ) ? serialize( $data['payment_custom_field'] ) : '' ) . ', 
		payment_method = ' . $db->quote( $data['payment_method'] ) . ', 
		payment_code = ' . $db->quote( $data['payment_code'] ) . ', 
		shipping_first_name = ' . $db->quote( $data['shipping_first_name'] ) . ', 
		shipping_last_name = ' . $db->quote( $data['shipping_last_name'] ) . ', 
		shipping_company = ' . $db->quote( $data['shipping_company'] ) . ', 
		shipping_address_1 = ' . $db->quote( $data['shipping_address_1'] ) . ', 
		shipping_address_2 = ' . $db->quote( $data['shipping_address_2'] ) . ',
		shipping_city = ' . $db->quote( $data['shipping_city'] ) . ', 
		shipping_postcode = ' . $db->quote( $data['shipping_postcode'] ) . ', 
		shipping_country = ' . $db->quote( $data['shipping_country'] ) . ', 
		shipping_country_id = ' . ( int )$data['shipping_country_id'] . ', 
		shipping_zone = ' . $db->quote( $data['shipping_zone'] ) . ', 
		shipping_zone_id = ' . ( int )$data['shipping_zone_id'] . ', 
		shipping_address_format = ' . $db->quote( $data['shipping_address_format'] ) . ', 
		shipping_custom_field = ' . $db->quote( isset( $data['shipping_custom_field'] ) ? serialize( $data['shipping_custom_field'] ) : '' ) . ', 
		shipping_method = ' . $db->quote( $data['shipping_method'] ) . ', 
		shipping_code = ' . $db->quote( $data['shipping_code'] ) . ', 
		comment = ' . $db->quote( $data['comment'] ) . ', 
		total = ' . ( float )$data['total'] . ', 
		affiliate_id = ' . ( int )$data['affiliate_id'] . ', 
		commission = ' . ( float )$data['commission'] . ', 
		marketing_id = ' . ( int )$data['marketing_id'] . ', 
		tracking = ' . $db->quote( $data['tracking'] ) . ', 
		language_id = ' . ( int )$data['language_id'] . ', 
		currency_id = ' . ( int )$data['currency_id'] . ', 
		currency_code = ' . $db->quote( $data['currency_code'] ) . ', 
		currency_value = ' . ( float )$data['currency_value'] . ', 
		ip = ' . $db->quote( $data['ip'] ) . ', 
		forwarded_ip = ' . $db->quote( $data['forwarded_ip'] ) . ', 
		user_agent = ' . $db->quote( $data['user_agent'] ) . ', 
		accept_language = ' . $db->quote( $data['accept_language'] ) . ', 
		date_added = ' . NV_CURRENTTIME . ', 
		date_modified = ' . NV_CURRENTTIME );
	}
	catch ( PDOException $e )
	{
		trigger_error( "Error! " . $e, E_USER_ERROR );

	}
	$order_id = $db->lastInsertId();

	// Products
	foreach( $data['products'] as $product )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_product SET 
		order_id = ' . ( int )$order_id . ',
		product_id = ' . ( int )$product['product_id'] . ', 
		name = ' . $db->quote( $product['name'] ) . ', 
		model = ' . $db->quote( $product['model'] ) . ', 
		quantity = ' . ( int )$product['quantity'] . ', 
		price = ' . ( float )$product['price'] . ', 
		total = ' . ( float )$product['total'] . ', 
		tax = ' . ( float )$product['tax'] . ', 
		reward = ' . ( int )$product['reward'] );

		$order_product_id = $db->lastInsertId();

		foreach( $product['option'] as $option )
		{
			$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_option SET 
			order_id = ' . ( int )$order_id . ', 
			order_product_id = ' . ( int )$order_product_id . ', 
			product_option_id = ' . ( int )$option['product_option_id'] . ', 
			product_option_value_id = ' . ( int )$option['product_option_value_id'] . ', 
			name = ' . $db->quote( $option['name'] ) . ', 
			value = ' . $db->quote( $option['value'] ) . ', 
			type = ' . $db->quote( $option['type'] ) );
		}
	}

	// Vouchers
	foreach( $data['vouchers'] as $voucher )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_voucher SET order_id = ' . ( int )$order_id . ', description = ' . $db->quote( $voucher['description'] ) . ', code = ' . $db->quote( $voucher['code'] ) . ', from_name = ' . $db->quote( $voucher['from_name'] ) . ', from_email = ' . $db->quote( $voucher['from_email'] ) . ', to_name = ' . $db->quote( $voucher['to_name'] ) . ', to_email = ' . $db->quote( $voucher['to_email'] ) . ', voucher_theme_id = ' . ( int )$voucher['voucher_theme_id'] . ', message = ' . $db->quote( $voucher['message'] ) . ', amount = ' . ( float )$voucher['amount'] . '' );

		$order_voucher_id = $db->lastInsertId();

		addVoucher( $order_id, $voucher );

		$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_order_voucher SET voucher_id = ' . ( int )$voucher_id . ' WHERE order_voucher_id = ' . ( int )$order_voucher_id . '' );
	}

	// Totals
	foreach( $data['totals'] as $total )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_total SET order_id = ' . ( int )$order_id . ', code = ' . $db->quote( $total['code'] ) . ', title = ' . $db->quote( $total['title'] ) . ', value = ' . ( float )$total['value'] . ', sort_order = ' . ( int )$total['sort_order'] . '' );
	}

	return $order_id;
}

function editOrder( $order_id, $data )
{
	global $db, $ProductCurrency,  $user_info, $productRegistry, $module_name, $lang_module;

	// Void the order first
	addOrderHistory( $order_id, 0 );

	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_order SET 
	invoice_prefix = ' . $db->quote( $data['invoice_prefix'] ) . ', 
	store_id = ' . ( int )$data['store_id'] . ', 
	store_name = ' . $db->quote( $data['store_name'] ) . ', 
	store_url = ' . $db->quote( $data['store_url'] ) . ', 
	userid = ' . ( int )$data['userid'] . ', 
	customer_group_id = ' . ( int )$data['customer_group_id'] . ', 
	first_name = ' . $db->quote( $data['first_name'] ) . ', 
	last_name = ' . $db->quote( $data['last_name'] ) . ', 
	email = ' . $db->quote( $data['email'] ) . ', 
	telephone = ' . $db->quote( $data['telephone'] ) . ', 
	fax = ' . $db->quote( $data['fax'] ) . ', 
	custom_field = ' . $db->quote( serialize( $data['custom_field'] ) ) . ', 
	payment_first_name = ' . $db->quote( $data['payment_first_name'] ) . ', 
	payment_last_name = ' . $db->quote( $data['payment_last_name'] ) . ', 
	payment_company = ' . $db->quote( $data['payment_company'] ) . ', 
	payment_address_1 = ' . $db->quote( $data['payment_address_1'] ) . ', 
	payment_address_2 = ' . $db->quote( $data['payment_address_2'] ) . ', 
	payment_city = ' . $db->quote( $data['payment_city'] ) . ', 
	payment_postcode = ' . $db->quote( $data['payment_postcode'] ) . ', 
	payment_country = ' . $db->quote( $data['payment_country'] ) . ', 
	payment_country_id = ' . ( int )$data['payment_country_id'] . ',
	payment_zone = ' . $db->quote( $data['payment_zone'] ) . ', 
	payment_zone_id = ' . ( int )$data['payment_zone_id'] . ',
	payment_address_format = ' . $db->quote( $data['payment_address_format'] ) . ', 
	payment_custom_field = ' . $db->quote( serialize( $data['payment_custom_field'] ) ) . ', 
	payment_method = ' . $db->quote( $data['payment_method'] ) . ',
	payment_code = ' . $db->quote( $data['payment_code'] ) . ', 
	shipping_first_name = ' . $db->quote( $data['shipping_first_name'] ) . ', 
	shipping_last_name = ' . $db->quote( $data['shipping_last_name'] ) . ',
	shipping_company = ' . $db->quote( $data['shipping_company'] ) . ', 
	shipping_address_1 = ' . $db->quote( $data['shipping_address_1'] ) . ',
	shipping_address_2 = ' . $db->quote( $data['shipping_address_2'] ) . ', 
	shipping_city = ' . $db->quote( $data['shipping_city'] ) . ', 
	shipping_postcode = ' . $db->quote( $data['shipping_postcode'] ) . ', 
	shipping_country = ' . $db->quote( $data['shipping_country'] ) . ', 
	shipping_country_id = ' . ( int )$data['shipping_country_id'] . ', 
	shipping_zone = ' . $db->quote( $data['shipping_zone'] ) . ', 
	shipping_zone_id = ' . ( int )$data['shipping_zone_id'] . ', 
	shipping_address_format = ' . $db->quote( $data['shipping_address_format'] ) . ', 
	shipping_custom_field = ' . $db->quote( serialize( $data['shipping_custom_field'] ) ) . ', 
	shipping_method = ' . $db->quote( $data['shipping_method'] ) . ', 
	shipping_code = ' . $db->quote( $data['shipping_code'] ) . ', 
	comment = ' . $db->quote( $data['comment'] ) . ', 
	total = ' . ( float )$data['total'] . ', 
	affiliate_id = ' . ( int )$data['affiliate_id'] . ', 
	commission = ' . ( float )$data['commission'] . ', 
	date_modified = ' . NV_CURRENTTIME . ' 
	WHERE order_id = ' . ( int )$order_id );

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . ( int )$order_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id );

	// Products
	foreach( $data['products'] as $product )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_product SET order_id = ' . ( int )$order_id . ', product_id = ' . ( int )$product['product_id'] . ', name = ' . $db->quote( $product['name'] ) . ', model = ' . $db->quote( $product['model'] ) . ', quantity = ' . ( int )$product['quantity'] . ', price = ' . ( float )$product['price'] . ', total = ' . ( float )$product['total'] . ', tax = ' . ( float )$product['tax'] . ', reward = ' . ( int )$product['reward'] . '' );

		$order_product_id = $db->lastInsertId();

		foreach( $product['option'] as $option )
		{
			$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_option SET order_id = ' . ( int )$order_id . ', order_product_id = ' . ( int )$order_product_id . ', product_option_id = ' . ( int )$option['product_option_id'] . ', product_option_value_id = ' . ( int )$option['product_option_value_id'] . ', name = ' . $db->quote( $option['name'] ) . ', value = ' . $db->quote( $option['value'] ) . ', type = ' . $db->quote( $option['type'] ) . '' );
		}
	}

	// disable voucher
	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_voucher SET status = 0 WHERE order_id = ' . ( int )$order_id );

	// Vouchers
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_voucher WHERE order_id = ' . ( int )$order_id );

	foreach( $data['vouchers'] as $voucher )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_voucher SET 
			order_id = ' . ( int )$order_id . ', description = ' . $db->quote( $voucher['description'] ) . ', 
			code = ' . $db->quote( $voucher['code'] ) . ', 
			from_name = ' . $db->quote( $voucher['from_name'] ) . ', 
			from_email = ' . $db->quote( $voucher['from_email'] ) . ', 
			to_name = ' . $db->quote( $voucher['to_name'] ) . ', 
			to_email = ' . $db->quote( $voucher['to_email'] ) . ', 
			voucher_theme_id = ' . ( int )$voucher['voucher_theme_id'] . ', 
			message = ' . $db->quote( $voucher['message'] ) . ', 
			amount = ' . ( float )$voucher['amount'] );

		$order_voucher_id = $db->lastInsertId();

		$voucher_id = addVoucher( $order_id, $voucher );

		$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_order_voucher SET 
		voucher_id = ' . ( int )$voucher_id . ' 
		WHERE order_voucher_id = ' . ( int )$order_voucher_id );

	}

	// Totals
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_total WHERE order_id = ' . ( int )$order_id . '' );

	foreach( $data['totals'] as $total )
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_total SET 
		order_id = ' . ( int )$order_id . ', 
		code = ' . $db->quote( $total['code'] ) . ', 
		title = ' . $db->quote( $total['title'] ) . ', 
		value = ' . ( float )$total['value'] . ', 
		sort_order = ' . ( int )$total['sort_order'] );
	}

}

function deleteOrder( $order_id )
{
	global $db,  $ProductCurrency, $user_info, $productRegistry, $module_name, $lang_module;

	// Void the order first
	addOrderHistory( $order_id, 0 );

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_voucher WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_total WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_history WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_fraud WHERE order_id = ' . ( int )$order_id . '' );
	$db->query( 'DELETE or, ort FROM ' . TABLE_PRODUCT_NAME . '_order_recurring or, ' . TABLE_PRODUCT_NAME . '_order_recurring_transaction ort WHERE order_id = ' . ( int )$order_id . ' AND ort.order_recurring_id = or.order_recurring_id' );

	// xoa don hang tu dai ly
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_affiliate_transaction WHERE order_id = ' . ( int )$order_id . '' );

	// disable voucher
	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_voucher SET status = 0 WHERE order_id = ' . ( int )$order_id );

}

function getOrder( $order_id )
{
	global $db, $user_info, $productRegistry, $module_name, $lang_module;
	
	$order_query = $db->query( 'SELECT *, (SELECT os.name FROM ' . TABLE_PRODUCT_NAME . '_order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM ' . TABLE_PRODUCT_NAME . '_order o WHERE o.order_id = ' . ( int )$order_id )->fetch();

	if( $order_query )
	{
		$country_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_country WHERE country_id = ' . ( int )$order_query['payment_country_id'] )->fetch();

		if( $country_query )
		{
			$payment_iso_code_2 = $country_query['iso_code_2'];
			$payment_iso_code_3 = $country_query['iso_code_3'];
		}
		else
		{
			$payment_iso_code_2 = '';
			$payment_iso_code_3 = '';
		}

		$zone_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE zone_id = ' . ( int )$order_query['payment_zone_id'] )->fetch();

		if( $zone_query )
		{
			$payment_zone_code = $zone_query['code'];
		}
		else
		{
			$payment_zone_code = '';
		}

		$country_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_country WHERE country_id = ' . ( int )$order_query['shipping_country_id'] )->fetch();

		if( $country_query )
		{
			$shipping_iso_code_2 = $country_query['iso_code_2'];
			$shipping_iso_code_3 = $country_query['iso_code_3'];
		}
		else
		{
			$shipping_iso_code_2 = '';
			$shipping_iso_code_3 = '';
		}

		$zone_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE zone_id = ' . ( int )$order_query['shipping_zone_id'] )->fetch();

		if( $zone_query )
		{
			$shipping_zone_code = $zone_query['code'];
		}
		else
		{
			$shipping_zone_code = '';
		}
 
		return array(
			'order_id' => $order_query['order_id'],
			'invoice_no' => $order_query['invoice_no'],
			'invoice_prefix' => $order_query['invoice_prefix'],
			'store_id' => $order_query['store_id'],
			'store_name' => $order_query['store_name'],
			'store_url' => $order_query['store_url'],
			'userid' => $order_query['userid'],
			'first_name' => $order_query['first_name'],
			'last_name' => $order_query['last_name'],
			'email' => $order_query['email'],
			'telephone' => $order_query['telephone'],
			'fax' => $order_query['fax'],
			'custom_field' => unserialize( $order_query['custom_field'] ),
			'payment_first_name' => $order_query['payment_first_name'],
			'payment_last_name' => $order_query['payment_last_name'],
			'payment_company' => $order_query['payment_company'],
			'payment_address_1' => $order_query['payment_address_1'],
			'payment_address_2' => $order_query['payment_address_2'],
			'payment_postcode' => $order_query['payment_postcode'],
			'payment_city' => $order_query['payment_city'],
			'payment_zone_id' => $order_query['payment_zone_id'],
			'payment_zone' => $order_query['payment_zone'],
			'payment_zone_code' => $payment_zone_code,
			'payment_country_id' => $order_query['payment_country_id'],
			'payment_country' => $order_query['payment_country'],
			'payment_iso_code_2' => $payment_iso_code_2,
			'payment_iso_code_3' => $payment_iso_code_3,
			'payment_address_format' => $order_query['payment_address_format'],
			'payment_custom_field' => unserialize( $order_query['payment_custom_field'] ),
			'payment_method' => $order_query['payment_method'],
			'payment_code' => $order_query['payment_code'],
			'shipping_first_name' => $order_query['shipping_first_name'],
			'shipping_last_name' => $order_query['shipping_last_name'],
			'shipping_company' => $order_query['shipping_company'],
			'shipping_address_1' => $order_query['shipping_address_1'],
			'shipping_address_2' => $order_query['shipping_address_2'],
			'shipping_postcode' => $order_query['shipping_postcode'],
			'shipping_city' => $order_query['shipping_city'],
			'shipping_zone_id' => $order_query['shipping_zone_id'],
			'shipping_zone' => $order_query['shipping_zone'],
			'shipping_zone_code' => $shipping_zone_code,
			'shipping_country_id' => $order_query['shipping_country_id'],
			'shipping_country' => $order_query['shipping_country'],
			'shipping_iso_code_2' => $shipping_iso_code_2,
			'shipping_iso_code_3' => $shipping_iso_code_3,
			'shipping_address_format' => $order_query['shipping_address_format'],
			'shipping_custom_field' => unserialize( $order_query['shipping_custom_field'] ),
			'shipping_method' => $order_query['shipping_method'],
			'shipping_code' => $order_query['shipping_code'],
			'comment' => $order_query['comment'],
			'total' => $order_query['total'],
			'order_status_id' => $order_query['order_status_id'],
			'order_status' => $order_query['order_status'],
			'affiliate_id' => $order_query['affiliate_id'],
			'commission' => $order_query['commission'],
			'language_id' => $order_query['language_id'],
			'currency_id' => $order_query['currency_id'],
			'currency_code' => $order_query['currency_code'],
			'currency_value' => $order_query['currency_value'],
			'ip' => $order_query['ip'],
			'forwarded_ip' => $order_query['forwarded_ip'],
			'user_agent' => $order_query['user_agent'],
			'accept_language' => $order_query['accept_language'],
			'date_modified' => $order_query['date_modified'],
			'date_added' => $order_query['date_added'] );
	}
	else
	{
		return false;
	}
}

function addOrderHistory( $order_id, $order_status_id, $comment = '', $notify = false )
{
	global $db, $global_config, $ProductGeneral, $ProductCurrency, $user_info, $productRegistry, $module_name, $lang_module;
	
	$lang_ext = $ProductGeneral->getLangSite('order', 'mail');
	
	$order_info = getOrder( $order_id );

	if( $order_info )
	{

		$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_order SET order_status_id = ' . ( int )$order_status_id . ', date_modified = ' . NV_CURRENTTIME . ' WHERE order_id = ' . ( int )$order_id );

		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_history SET order_id = ' . ( int )$order_id . ', order_status_id = ' . ( int )$order_status_id . ', notify = ' . ( int )$notify . ', comment = ' . $db->quote( $comment ) . ', date_added =' . NV_CURRENTTIME );

		// If current order status is not processing or complete but new status is processing or complete then commence completing the order
		if( ! in_array( $order_info['order_status_id'], array_merge(  $ProductGeneral->config['config_processing_status'],  $ProductGeneral->config['config_complete_status'] ) ) || in_array( $order_status_id, array_merge(  $ProductGeneral->config['config_processing_status'],  $ProductGeneral->config['config_complete_status'] ) ) )
		{
			// Stock subtraction
			$order_product_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . ( int )$order_id )->fetchAll();

			foreach( $order_product_query as $order_product )
			{
				$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET quantity = (quantity - ' . ( int )$order_product['quantity'] . ') WHERE product_id = ' . ( int )$order_product['product_id'] . ' AND subtract = 1 ' );
				$order_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id = ' . ( int )$order_product['order_product_id'] )->fetchAll();

				foreach( $order_option_query as $option )
				{
					$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_option_value SET quantity = (quantity - ' . ( int )$order_product['quantity'] . ') WHERE product_option_value_id = ' . ( int )$option['product_option_value_id'] . ' AND subtract = 1' );

				}
			}

			// Redeem coupon, vouchers and reward points
			$order_total_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_total WHERE order_id = ' . ( int )$order_id . ' ORDER BY sort_order ASC' )->fetchAll();
			$order_total = array();
			
			foreach( $order_total_query as $result )
			{
				$array_class[] = $result['code'];
				$order_total[] = $result;
			}
			$a = 0;
			foreach( $array_class as $key => &$class )
			{
				$classMap = 'NukeViet\Product\Total\\' . $class;
				${$class} = new $classMap( $productRegistry );
				if( method_exists( ${$class}, 'confirm' ) )
				{
					${$class}->confirm( $order_info, $order_total[$a] );
				}
				++$a;
			}

			// chính sach đại lý chưa phát triển
			// Add commission if sale is linked to affiliate referral.
			// if( $order_info['affiliate_id'] &&  $ProductGeneral->config['config_affiliate_auto'] )
			// {

			// $this->model_affiliate_affiliate->addTransaction( $order_info['affiliate_id'], $order_info['commission'], $order_id );
			// }
		}

		// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
		if( in_array( $order_info['order_status_id'], array_merge(  $ProductGeneral->config['config_processing_status'],  $ProductGeneral->config['config_complete_status'] ) ) && ! in_array( $order_status_id, array_merge(  $ProductGeneral->config['config_processing_status'],  $ProductGeneral->config['config_complete_status'] ) ) )
		{
			// Restock
			$product_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . ( int )$order_id )->fetchAll();

			foreach( $product_query as $product )
			{
				$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET quantity = (quantity + ' . ( int )$product['quantity'] . ') WHERE product_id = ' . ( int )$product['product_id'] . ' AND subtract = 1' );

				$option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id = ' . ( int )$product['order_product_id'] )->fetchAll();

				foreach( $option_query as $option )
				{
					$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_option_value SET quantity = (quantity + ' . ( int )$product['quantity'] . ') WHERE product_option_value_id = ' . ( int )$option['product_option_value_id'] . ' AND subtract = 1' );
				}
			}

			// Remove coupon, vouchers and reward points history

			$order_totals = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_total WHERE order_id = ' . ( int )$order_id . ' ORDER BY sort_order' )->fetchAll();

			foreach( $order_totals as $result )
			{
				$array_class[] = $result['code'];
			}

			foreach( $array_class as $key => &$class )
			{
				${$class} = new $class( $productRegistry );
				if( method_exists( ${$class}, 'unconfirm' ) )
				{
					${$class}->unconfirm( $order_id );
				}

			}
			// chính sách đại lý
			// Remove commission if sale is linked to affiliate referral.
			// if( $order_info['affiliate_id'] )
			// {
			// $this->model_affiliate_affiliate->deleteTransaction( $order_id );
			// }
		}

		//nv_del_moduleCache( $module_name );

		// If order status is 0 then becomes greater than 0 send main html email
		if( ! $order_info['order_status_id'] && $order_status_id )
		{
			$download_status = false;

			$order_status_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id = ' . ( int )$order_status_id . ' AND language_id = ' . ( int )$order_info['language_id'] )->fetch();

			if( $order_status_query )
			{
				$order_status = $order_status_query['name'];
			}
			else
			{
				$order_status = '';
			}

			$order_info['store_name'] = $global_config['site_name'];
			$order_info['store_url'] = $global_config['site_url'];
			$subject = sprintf( $lang_ext['text_new_subject'], $order_info['store_name'], $order_id );

	 
			$data = array();
			$data['title'] = sprintf( $lang_ext['text_new_subject'], html_entity_decode( $order_info['store_name'], ENT_QUOTES, 'UTF-8' ), $order_id );
			$data['text_greeting'] = sprintf( $lang_ext['text_new_greeting'], html_entity_decode( $order_info['store_name'], ENT_QUOTES, 'UTF-8' ) );
			$data['text_link'] = $lang_ext['text_new_link'];
			$data['text_download'] = $lang_ext['text_new_download'];
			$data['text_order_detail'] = $lang_ext['text_new_order_detail'];
			$data['text_instruction'] = $lang_ext['text_new_instruction'];
			$data['text_order_id'] = $lang_ext['text_new_order_id'];
			$data['text_date_added'] = $lang_ext['text_new_date_added'];
			$data['text_payment_method'] = $lang_ext['text_new_payment_method'];
			$data['text_shipping_method'] = $lang_ext['text_new_shipping_method'];
			$data['text_email'] = $lang_ext['text_new_email'];
			$data['text_telephone'] = $lang_ext['text_new_telephone'];
			$data['text_ip'] = $lang_ext['text_new_ip'];
			$data['text_order_status'] = $lang_ext['text_new_order_status'];
			$data['text_payment_address'] = $lang_ext['text_new_payment_address'];
			$data['text_shipping_address'] = $lang_ext['text_new_shipping_address'];
			$data['text_product'] = $lang_ext['text_new_product'];
			$data['text_model'] = $lang_ext['text_new_model'];
			$data['text_quantity'] = $lang_ext['text_new_quantity'];
			$data['text_price'] = $lang_ext['text_new_price'];
			$data['text_total'] = $lang_ext['text_new_total'];
			$data['text_footer'] = $lang_ext['text_new_footer'];

			$data['logo'] = $global_config['site_url'] . NV_BASE_SITEURL . $global_config['site_logo'];
			$data['store_name'] = $order_info['store_name'];
			$data['store_url'] = $order_info['store_url'];
			$data['userid'] = $order_info['userid'];
			$data['link'] = $global_config['site_url'] . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=account/order/info&order_id=' . $order_id;

			$data['order_id'] = $order_id;
			$data['date_added'] = nv_date( 'd/m/Y', $order_info['date_added'] );
			$data['payment_method'] = $order_info['payment_method'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['ip'] = $order_info['ip'];
			$data['order_status'] = $order_status;

			if( $comment && $notify )
			{
				$data['comment'] = nv_nl2br( $comment );
			}
			else
			{
				$data['comment'] = '';
			}

			if( $order_info['payment_address_format'] )
			{
				$format = $order_info['payment_address_format'];
			}
			else
			{
				$format = '{first_name} {last_name}' . '\n' . '{company}' . '\n' . '{address_1}' . '\n' . '{address_2}' . '\n' . '{city} {postcode}' . '\n' . '{zone}' . '\n' . '{country}';
			}

			$find = array(
				'{first_name}',
				'{last_name}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}' );

			$replace = array(
				'first_name' => $order_info['payment_first_name'],
				'last_name' => $order_info['payment_last_name'],
				'company' => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city' => $order_info['payment_city'],
				'postcode' => $order_info['payment_postcode'],
				'zone' => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country' => $order_info['payment_country'] );

			$data['payment_address'] = str_replace( array(
				'\r\n',
				'\r',
				'\n' ), '<br />', preg_replace( array(
				'/\s\s+/',
				'/\r\r+/',
				'/\n\n+/' ), '<br />', trim( str_replace( $find, $replace, $format ) ) ) );

			if( $order_info['shipping_address_format'] )
			{
				$format = $order_info['shipping_address_format'];
			}
			else
			{
				$format = '{first_name} {last_name}' . '\n' . '{company}' . '\n' . '{address_1}' . '\n' . '{address_2}' . '\n' . '{city} {postcode}' . '\n' . '{zone}' . '\n' . '{country}';
			}

			$find = array(
				'{first_name}',
				'{last_name}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}' );

			$replace = array(
				'first_name' => $order_info['shipping_first_name'],
				'last_name' => $order_info['shipping_last_name'],
				'company' => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city' => $order_info['shipping_city'],
				'postcode' => $order_info['shipping_postcode'],
				'zone' => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country' => $order_info['shipping_country'] );

			$data['shipping_address'] = str_replace( array(
				'\r\n',
				'\r',
				'\n' ), '<br />', preg_replace( array(
				'/\s\s+/',
				'/\r\r+/',
				'/\n\n+/' ), '<br />', trim( str_replace( $find, $replace, $format ) ) ) );

			// Products
			$data['products'] = array();

			foreach( $order_product_query as $product )
			{
				$option_data = array();

				$order_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id = ' . ( int )$product['order_product_id'] )->fetchAll();

				foreach( $order_option_query as $option )
				{
					if( $option['type'] != 'file' )
					{
						$value = $option['value'];
					}
					else
					{
						//$upload_info = $this->model_tool_upload->getUploadByCode( $option['value'] );
						$upload_info = array();
						if( $upload_info )
						{
							$value = $upload_info['name'];
						}
						else
						{
							$value = '';
						}
					}

					$option_data[] = array( 'name' => $option['name'], 'value' => ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ) );
				}

				$data['products'][] = array(
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'quantity' => $product['quantity'],
					'price' => $ProductCurrency->format( $product['price'] + (  $ProductGeneral->config['config_tax'] ? $product['tax'] : 0 ), $order_info['currency_code'], $order_info['currency_value'] ),
					'total' => $ProductCurrency->format( $product['total'] + (  $ProductGeneral->config['config_tax'] ? ( $product['tax'] * $product['quantity'] ) : 0 ), $order_info['currency_code'], $order_info['currency_value'] ) );
			}

			// Vouchers
			$data['vouchers'] = array();

			$order_voucher_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_voucher WHERE order_id = ' . ( int )$order_id )->fetchAll();

			foreach( $order_voucher_query as $voucher )
			{
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount' => $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] ),
					);
			}

			// Order Totals
			foreach( $order_total_query as $total )
			{
				$data['totals'][] = array(
					'title' => $total['title'],
					'text' => $ProductCurrency->format( $total['value'], $order_info['currency_code'], $order_info['currency_value'] ),
					);
			}

			$text = sprintf( $lang_ext['text_new_greeting'], html_entity_decode( $order_info['store_name'], ENT_QUOTES, 'UTF-8' ) ) . "<br /><br />";
			$text .= $lang_ext['text_new_order_id'] . ' ' . $order_id . "\n";
			$text .= $lang_ext['text_new_date_added'] . ' ' . date( 'd/m/Y H:i', $order_info['date_added'] ) . "\n";
			$text .= $lang_ext['text_new_order_status'] . ' ' . $order_status . "<br /><br />";

			if( $comment && $notify )
			{
				$text .= $lang_ext['text_new_instruction'] . "<br /><br />";
				$text .= $comment . "<br /><br />";
			}

			// Products
			$text .= $lang_ext['text_new_products'] . "\n";

			foreach( $order_product_query as $product )
			{
				$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode( $ProductCurrency->format( $product['total'] + (  $ProductGeneral->config['config_tax'] ? ( $product['tax'] * $product['quantity'] ) : 0 ), $order_info['currency_code'], $order_info['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "\n";

				$order_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id = ' . $product['order_product_id'] )->fetchAll();

				foreach( $order_option_query as $option )
				{
					if( $option['type'] != 'file' )
					{
						$value = $option['value'];
					}
					else
					{
						//$upload_info = $this->model_tool_upload->getUploadByCode( $option['value'] );
						$upload_info = array();
						if( $upload_info )
						{
							$value = $upload_info['name'];
						}
						else
						{
							$value = '';
						}
					}

					$text .= chr( 9 ) . '-' . $option['name'] . ' ' . ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ) . "\n";
				}
			}

			foreach( $order_voucher_query as $voucher )
			{
				$text .= '1x ' . $voucher['description'] . ' ' . $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] );
			}

			$text .= "<br />";

			$text .= $lang_ext['text_new_order_total'] . "\n";

			foreach( $order_total_query as $total )
			{
				$text .= $total['title'] . ': ' . html_entity_decode( $ProductCurrency->format( $total['value'], $order_info['currency_code'], $order_info['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "<br />";
			}

			$text .= "<br />";

			if( $order_info['userid'] )
			{
				$text .= $lang_ext['text_new_link'] . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "<br /><br />";
			}

			// Comment
			if( $order_info['comment'] )
			{
				$text .= $lang_ext['text_new_comment'] . "<br /><br />";
				$text .= $order_info['comment'] . "<br /><br />";
			}

			$text .= $lang_ext['text_new_footer'] . "<br /><br />";

			// gọi template order email
			$email_contents = call_user_func( 'email_new_order', $data );

			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $order_info['email'], $subject, $email_contents . $text );

			// Admin Alert Mail
			if(  $ProductGeneral->config['config_order_mail'] )
			{
				$subject = sprintf( $lang_ext['text_new_subject'], html_entity_decode( $global_config['site_name'], ENT_QUOTES, 'UTF-8' ), $order_id );

				// HTML Mail
				$data['text_greeting'] = $lang_ext['text_new_received'];
				if( $comment )
				{
					if( $order_info['comment'] )
					{
						$data['comment'] = nv_nl2br( $comment ) . '<br /><br />' . $order_info['comment'];
					}
					else
					{
						$data['comment'] = nv_nl2br( $comment );
					}
				}
				else
				{
					if( $order_info['comment'] )
					{
						$data['comment'] = $order_info['comment'];
					}
					else
					{
						$data['comment'] = '';
					}
				}
				$data['text_download'] = '';
				$data['text_footer'] = '';
				$data['text_link'] = '';
				$data['link'] = '';
				$data['download'] = '';

				// Text
				$text = $lang_ext['text_new_received'] . '<br /><br />';
				$text .= $lang_ext['text_new_order_id'] . ' ' . $order_id . '<br />';
				$text .= $lang_ext['text_new_date_added'] . ' ' . date( 'd/m/Y', $order_info['date_added'] ) . '<br />';
				$text .= $lang_ext['text_new_order_status'] . ' ' . $order_status . '<br /><br />';
				$text .= $lang_ext['text_new_products'] . '<br />';

				foreach( $order_product_query as $product )
				{
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode( $ProductCurrency->format( $product['total'] + (  $ProductGeneral->config['config_tax'] ? ( $product['tax'] * $product['quantity'] ) : 0 ), $order_info['currency_code'], $order_info['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . '<br />';

					$order_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id = ' . $product['order_product_id'] )->fetchAll();

					foreach( $order_option_query as $option )
					{
						if( $option['type'] != 'file' )
						{
							$value = $option['value'];
						}
						else
						{
							$value = nv_substr( $option['value'], 0, utf8_strrpos( $option['value'], '.' ) );
						}

						$text .= chr( 9 ) . '-' . $option['name'] . ' ' . ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ) . '<br />';
					}
				}

				foreach( $order_voucher_query as $voucher )
				{
					$text .= '1x ' . $voucher['description'] . ' ' . $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] );
				}

				$text .= "\n";

				$text .= $lang_ext['text_new_order_total'] . "\n";

				foreach( $order_total_query as $total )
				{
					$text .= $total['title'] . ': ' . html_entity_decode( $ProductCurrency->format( $total['value'], $order_info['currency_code'], $order_info['currency_value'] ), ENT_NOQUOTES, 'UTF-8' ) . "<br />";
				}

				$text .= "\n";

				if( $order_info['comment'] )
				{
					$text .= $lang_ext['text_new_comment'] . "<br /><br />";
					$text .= $order_info['comment'] . "<br /><br />";
				}

				$email_contents = call_user_func( 'email_new_order', $data );

				nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $global_config['site_email'], $subject, $email_contents . $text );

			}
		}

		// If order status is not 0 then send update text email
		if( $order_info['order_status_id'] && $order_status_id )
		{

			$subject = sprintf( $lang_ext['text_update_subject'], html_entity_decode( $order_info['store_name'], ENT_QUOTES, 'UTF-8' ), $order_id );

			$message = $lang_ext['text_update_order'] . ' ' . $order_id . '<br />';
			$message .= $lang_ext['text_update_date_added'] . ' ' . date( 'd/m/Y', $order_info['date_added'] ) . '<br /><br />';

			$order_status_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id = ' . ( int )$order_status_id . ' AND language_id = ' . ( int )$order_info['language_id'] )->fetch();

			if( $order_status_query )
			{
				$message .= $lang_ext['text_update_order_status'] . "<br /><br />";
				$message .= $order_status_query['name'] . "<br /><br />";
			}

			if( $order_info['userid'] )
			{
				$message .= $lang_ext['text_update_link'] . "\n";
				$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "<br /><br />";
			}

			if( $notify && $comment )
			{
				$message .= $lang_ext['text_update_comment'] . "<br /><br />";
				$message .= $comment . "<br /><br />";
			}

			$message .= $lang_ext['text_update_footer'];

			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $order_info['email'], $subject, $message );

		}

		// If order status in the complete range create any vouchers that where in the order need to be made available.
		if( in_array( $order_info['order_status_id'],  $ProductGeneral->config['config_complete_status'] ) )
		{
			// Send out any gift voucher mails
			$order_info = getOrder( $order_id );

			if( $order_info )
			{
				$voucher_query = $db->query( 'SELECT *, vtd.name AS theme FROM ' . TABLE_PRODUCT_NAME . '_voucher v 
				LEFT JOIN ' . TABLE_PRODUCT_NAME . '_voucher_theme vt ON (v.voucher_theme_id = vt.voucher_theme_id) 
				LEFT JOIN ' . TABLE_PRODUCT_NAME . '_voucher_theme_description vtd 
				ON (vt.voucher_theme_id = vtd.voucher_theme_id) AND vtd.language_id = ' . ( int )$order_info['language_id'] . ' 
				WHERE v.order_id = ' . ( int )$order_id )->fetchAll();

				foreach( $voucher_query as $voucher )
				{
					// HTML Mail
					$data = array();

					$data['title'] = sprintf( $lang_ext['text_subject'], $voucher['from_name'] );

					$data['text_greeting'] = sprintf( $lang_ext['text_greeting'], $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] ) );
					$data['text_from'] = sprintf( $lang_ext['text_from'], $voucher['from_name'] );
					$data['text_message'] = $lang_ext['text_message'];
					$data['text_redeem'] = sprintf( $lang_ext['text_redeem'], $voucher['code'] );
					$data['text_footer'] = $lang_ext['text_footer'];

					if( ! empty( $voucher['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $voucher['image'] ) )
					{
						$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $voucher['image'];
					}

					$data['store_name'] = $order_info['store_name'];
					$data['store_url'] = $order_info['store_url'];
					$data['message'] = nv_nl2br( $voucher['message'] );

					// gọi template order voucher
					$email_contents = call_user_func( 'email_new_order_voucher', $data );

					nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $voucher['to_email'], sprintf( $lang_ext['text_subject'], $voucher['from_name'] ), $email_contents );

				}
			}
		}
	}

}

function addVoucher( $order_id, $data )
{
	global $db;

	try
	{
		$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher SET 
			order_id = ' . ( int )$order_id . ', 
			code = ' . $db->quote( $voucher['code'] ) . ', 
			from_name = ' . $db->quote( $voucher['from_name'] ) . ', 
			from_email = ' . $db->quote( $voucher['from_email'] ) . ', 
			to_name = ' . $db->quote( $voucher['to_name'] ) . ', 
			to_email = ' . $db->quote( $voucher['to_email'] ) . ', 
			voucher_theme_id = ' . ( int )$voucher['voucher_theme_id'] . ', 
			message = ' . $db->quote( $voucher['message'] ) . ', 
			amount = ' . ( float )$voucher['amount'] . ', 
			status = 1, date_added = ' . NV_CURRENTTIME );
		return $db->lastInsertId();
	}
	catch ( PDOException $e )
	{
		trigger_error( "Error! " . $e, E_USER_ERROR );

	}

}
