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

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$lang_ext = getLangAdmin( 'order', 'sale' );

if( ACTION_METHOD == 'get_address' )
{

	$address_id = $nv_Request->get_int( 'address_id', 'get', 0 );

	$json = array();

	if( ! empty( $address_id ) )
	{

		$json = getSaleAddress( $address_id );
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'get_zone' )
{
	$getCountry = getCountry();

	$json = array();

	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );

	$json = $getCountry[$country_id];

	$sql = 'SELECT zone_id, code, status, name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id=' . $country_id;
	$result = $db->query( $sql );

	while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
	{
		$json['zone'][] = array(
			'code' => $code,
			'country_id' => $country_id,
			'status' => $status,
			'name' => nv_htmlspecialchars( $name ),
			'zone_id' => $_zone_id );

	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'get_customer' )
{
	$json = array();
	$implode = array();

	$data['sort'] = 'name';
	$data['order'] = 'ASC';
	$data['start'] = 0;
	$data['limit'] = 5;
	$data['filter_name'] = $nv_Request->get_string( 'filter_name', 'get', '' );
	$data['filter_email'] = $nv_Request->get_string( 'filter_email', 'get', '' );

	$sql = 'SELECT *, CONCAT(u.first_name, " ", u.last_name) name, cgd.name customer_group FROM ' . NV_USERS_GLOBALTABLE . ' u LEFT JOIN ' . TABLE_PRODUCT_NAME . '_group_description cgd ON (u.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = ' . ( int )$ProductGeneral->current_language_id;

	if( ! empty( $data['filter_name'] ) )
	{
		$implode[] = ' ( u.username LIKE :username OR CONCAT(u.first_name, \' \', u.last_name) LIKE :full_name1 OR CONCAT(u.last_name, \' \', u.first_name) LIKE :full_name2 )';
	}

	if( ! empty( $data['filter_email'] ) )
	{
		$implode[] = 'u.email LIKE :email';
	}

	if( $implode )
	{
		$sql .= ' AND ' . implode( ' AND ', $implode );
	}

	$sort_data = array(
		'name',
		'c.email',
		'customer_group',
		'c.status',
		'c.approved',
		'c.ip',
		'c.date_added' );

	if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
	{
		$sql .= ' ORDER BY ' . $data['sort'];
	}
	else
	{
		$sql .= ' ORDER BY name';
	}

	if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
	{
		$sql .= ' DESC';
	}
	else
	{
		$sql .= ' ASC';
	}

	if( isset( $data['start'] ) || isset( $data['limit'] ) )
	{
		if( $data['start'] < 0 )
		{
			$data['start'] = 0;
		}

		if( $data['limit'] < 1 )
		{
			$data['limit'] = 20;
		}

		$sql .= ' LIMIT ' . ( int )$data['start'] . ',' . ( int )$data['limit'];
	}

	$sth = $db->prepare( $sql );

	if( ! empty( $data['filter_name'] ) )
	{
		$sth->bindValue( ':username', '%' . $data['filter_name'] . '%' );
		$sth->bindValue( ':full_name1', '%' . $data['filter_name'] . '%' );
		$sth->bindValue( ':full_name2', '%' . $data['filter_name'] . '%' );
	}
	if( ! empty( $data['filter_email'] ) )
	{
		$sth->bindValue( ':email', '%' . $data['filter_email'] . '%' );
	}
	$sth->execute();

	require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/address.php';

	while( $result = $sth->fetch() )
	{
		$json[] = array(
			'userid' => $result['userid'],
			'customer_group_id' => $result['customer_group_id'],
			'name' => strip_tags( html_entity_decode( $result['name'], ENT_QUOTES, 'UTF-8' ) ),
			'customer_group' => $result['customer_group'],
			'first_name' => $result['first_name'],
			'last_name' => $result['last_name'],
			'email' => $result['email'],
			'telephone' => $result['telephone'],
			'fax' => $result['fax'],
			'address' => getAddresses( $result['userid'] ) );
	}

	$sort_order = array();

	foreach( $json as $key => $value )
	{
		$sort_order[$key] = $value['name'];
	}

	array_multisort( $sort_order, SORT_ASC, $json );

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'get_affiliate' )
{
	$json = array();
	
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}
 
if( ACTION_METHOD == 'cart_add' )
{

	$json = array();

	$products = $nv_Request->get_typed_array( 'product', 'post', array() );

	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$quantity = $nv_Request->get_int( 'quantity', 'post', 0 );
	$option = $nv_Request->get_typed_array( 'option', 'post', 'int', array() );
	$option = array_filter( $option );

	if( ! empty( $products ) )
	{
		unset( $_SESSION[$module_data . '_cart'] );

		foreach( $products as $product )
		{
			if( isset( $product['option'] ) )
			{
				$option = $product['option'];
			}
			else
			{
				$option = array();
			}

			cartAdd( $product['product_id'], $product['quantity'], $option );
		}
	}

	if( ! empty( $product_id ) )
	{

		$product_info = getProduct( $product_id );

		if( $product_info )
		{
			if( empty( $quantity ) )
			{
				$quantity = 1;
			}

			$product_options = getProductOptions( $product_id );

			foreach( $product_options as $product_option )
			{
				if( $product_option['required'] && empty( $option[$product_option['product_option_id']] ) )
				{
					$json['error']['option'][$product_option['product_option_id']] = sprintf( $lang_ext['error_required'], $product_option['name'] );
				}
			}

			if( ! isset( $json['error']['option'] ) )
			{
				cartAdd( $product_id, $quantity, $option );

				$json['success'] = $lang_ext['text_success_cart'];

				unset( $_SESSION[$module_data . '_shipping_method'] );
				unset( $_SESSION[$module_data . '_shipping_methods'] );
				unset( $_SESSION[$module_data . '_payment_method'] );
				unset( $_SESSION[$module_data . '_payment_methods'] );

			}
		}
		else
		{
			$json['error']['store'] = $lang_ext['error_store'];
		}
		
	}else
	{
		$json['error']['store'] = $lang_ext['error_store'];
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'cart_products' )
{
	$json = array();

	$ProductContent = new shops_product( $productRegistry );
	$ProductTax = new shops_tax( $productRegistry );
	// Stock
	if( ! $ProductContent->hasStock() && ( ! $ProductGeneral->config['config_stock_checkout'] || $ProductGeneral->config['config_stock_warning'] ) )
	{
		$json['error']['stock'] = $lang_ext['error_stock'];
	}

	// Products
	$json['products'] = array();

	$products = $ProductContent->getProducts();
	if( !empty( $products ) )
	{
		foreach( $products as $product )
		{
			$product_total = 0;

			foreach( $products as $product_2 )
			{
				if( $product_2['product_id'] == $product['product_id'] )
				{
					$product_total += $product_2['quantity'];
				}
			}

			if( $product['minimum'] > $product_total )
			{
				$json['error']['minimum'][] = sprintf( $lang_ext['error_minimum'], $product['name'], $product['minimum'] );
			}

			$option_data = array();

			foreach( $product['option'] as $option )
			{
				$option_data[] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'name' => $option['name'],
					'value' => $option['value'],
					'type' => $option['type'] );
			}

			$json['products'][] = array(
				'key' => $product['key'],
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'model' => $product['model'],
				'option' => $option_data,
				'quantity' => $product['quantity'],
				'stock' => $product['stock'] ? true : ! ( ! $ProductGeneral->config['config_stock_checkout'] || $ProductGeneral->config['config_stock_warning'] ),
				'shipping' => $product['shipping'],
				'price' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) ),
				'total' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) * $product['quantity'] ),
				'reward' => $product['reward'] );
		}
	}
	// Voucher
	$json['vouchers'] = array();

	if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
	{
		foreach( $_SESSION[$module_data . '_vouchers'] as $key => $voucher )
		{
			$json['vouchers'][] = array(
				'code' => $voucher['code'],
				'description' => $voucher['description'],
				'from_name' => $voucher['from_name'],
				'from_email' => $voucher['from_email'],
				'to_name' => $voucher['to_name'],
				'to_email' => $voucher['to_email'],
				'voucher_theme_id' => $voucher['voucher_theme_id'],
				'message' => $voucher['message'],
				'amount' => $ProductCurrency->format( $voucher['amount'] ) );
		}
	}

	// Totals

	$total_data = array();
	$total = 0;
	$taxes = $ProductContent->getTaxes();
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

	$json['totals'] = array();

	foreach( $total_data as $total )
	{
		$json['totals'][] = array( 'title' => $total['title'], 'text' => $ProductCurrency->format( $total['value'] ) );
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'cart_remove' )
{
	$json = array();
	$key = $nv_Request->get_string( 'key', 'post', '' );
	
	if( ! empty( $key ) )
	{
		unset( $_SESSION[$module_data . '_cart'][$key] );
		unset( $_SESSION[$module_data . '_vouchers'][$key] );
		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
		unset( $_SESSION[$module_data . '_payment_method'] );
		unset( $_SESSION[$module_data . '_payment_methods'] );
		unset( $_SESSION[$module_data . '_reward'] );

		$json['success'] = $lang_ext['text_success_cart'];

	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'get_product' )
{

	$json = array();
	$filter_data['filter_name'] = $nv_Request->get_string( 'filter_name', 'get', '' );
	$filter_data['filter_model'] = $nv_Request->get_string( 'filter_model', 'get', '' );
	$filter_data['sort'] = 'name';
	$filter_data['order'] = 'ASC';
	$filter_data['start'] = 0;
	$filter_data['limit'] = 5;

	$ProductContent = new shops_product( $productRegistry );

	$results = getProducts( $filter_data );

	foreach( $results as $result )
	{
		$option_data = array();

		$product_options = $ProductContent->getProductOptions( $result['product_id'] );

		foreach( $product_options as $product_option )
		{
			$option_info = getOption( $product_option['option_id'] );

			if( $option_info )
			{
				$product_option_value_data = array();

				foreach( $product_option['product_option_value'] as $product_option_value )
				{
					$option_value_info = getOptionValue( $product_option_value['option_value_id'] );

					if( $option_value_info )
					{
						$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id' => $product_option_value['option_value_id'],
							'name' => $option_value_info['name'],
							'price' => ( float )$product_option_value['price'] ? $ProductCurrency->format( $product_option_value['price'], $ProductGeneral->config['config_currency'] ) : false,
							'price_prefix' => $product_option_value['price_prefix'] );
					}
				}

				$option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id' => $product_option['option_id'],
					'name' => $option_info['name'],
					'type' => $option_info['type'],
					'value' => $product_option['value'],
					'required' => $product_option['required'] );
			}
		}

		$json[] = array(
			'product_id' => $result['product_id'],
			'name' => strip_tags( html_entity_decode( $result['name'], ENT_QUOTES, 'UTF-8' ) ),
			'model' => $result['model'],
			'option' => $option_data,
			'price' => $result['price'] );
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'add_voucher' )
{
	$json = array();

	$data['from_name'] = $nv_Request->get_string( 'from_name', 'post', '' );
	$data['from_email'] = $nv_Request->get_string( 'from_email', 'post', '' );
	$data['to_name'] = $nv_Request->get_string( 'to_name', 'post', '' );
	$data['to_email'] = $nv_Request->get_string( 'to_email', 'post', '' );
	$data['voucher_theme_id'] = $nv_Request->get_int( 'voucher_theme_id', 'post', 0 );
	$data['message'] = $nv_Request->get_string( 'message', 'post', '' );
	$data['amount'] = $nv_Request->get_string( 'amount', 'post', '' );
	$data['voucher'] = $nv_Request->get_typed_array( 'voucher', 'post', array() );

	if( ! empty( $data['voucher'] ) )
	{
		$_SESSION[$module_data . '_vouchers'] = array();

		foreach( $data['voucher'] as $voucher )
		{
			if( ! empty( $voucher['code'] ) && ! empty( $voucher['to_name'] ) && ! empty( $voucher['to_email'] ) && ! empty( $voucher['from_name'] ) && ! empty( $voucher['from_email'] ) && ! empty( $voucher['voucher_theme_id'] ) && ! empty( $voucher['message'] ) && ! empty( $voucher['amount'] ) )
			{
				$_SESSION[$module_data . '_vouchers'][$voucher['code']] = array(
					'code' => $voucher['code'],
					'description' => sprintf( $lang_ext['text_for'], $ProductCurrency->format( $ProductCurrency->convert( $voucher['amount'], $ProductCurrency->getCode(), $ProductGeneral->config['config_currency'] ) ), $voucher['to_name'] ),
					'to_name' => $voucher['to_name'],
					'to_email' => $voucher['to_email'],
					'from_name' => $voucher['from_name'],
					'from_email' => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message' => $voucher['message'],
					'amount' => $ProductCurrency->convert( $voucher['amount'], $ProductCurrency->getCode(), $ProductGeneral->config['config_currency'] ) );
			}
		}
	}

	// Add a new voucher if set
	if( ( nv_strlen( $data['from_name'] ) < 1 ) || ( nv_strlen( $data['from_name'] ) > 64 ) )
	{
		$json['error']['from_name'] = $lang_ext['error_from_name'];
	}

	if( ( nv_strlen( $data['from_email'] ) > 96 ) || ! preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $data['from_email'] ) )
	{
		$json['error']['from_email'] = $lang_ext['error_email'];
	}

	if( ( nv_strlen( $data['to_name'] ) < 1 ) || ( nv_strlen( $data['to_name'] ) > 64 ) )
	{
		$json['error']['to_name'] = $lang_ext['error_to_name'];
	}

	if( ( nv_strlen( $data['to_email'] ) > 96 ) || ! preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $data['to_email'] ) )
	{
		$json['error']['to_email'] = $lang_ext['error_email'];
	}

	if( ( $data['amount'] < $ProductGeneral->config['config_voucher_min'] ) || ( $data['amount'] > $ProductGeneral->config['config_voucher_max'] ) )
	{
		$json['error']['amount'] = sprintf( $lang_ext['error_amount'], $ProductCurrency->format( $ProductGeneral->config['config_voucher_min'] ), $ProductCurrency->format( $ProductGeneral->config['config_voucher_max'] ) );
	}

	if( ! $json )
	{
		$code = mt_rand();

		$_SESSION[$module_data . '_vouchers'][$code] = array(
			'code' => $code,
			'description' => sprintf( $lang_ext['text_for'], $ProductCurrency->format( $ProductCurrency->convert( $data['amount'], $ProductCurrency->getCode(), $ProductGeneral->config['config_currency'] ) ), $data['to_name'] ),
			'to_name' => $data['to_name'],
			'to_email' => $data['to_email'],
			'from_name' => $data['from_name'],
			'from_email' => $data['from_email'],
			'voucher_theme_id' => $data['voucher_theme_id'],
			'message' => $data['message'],
			'amount' => $ProductCurrency->convert( $data['amount'], $ProductCurrency->getCode(), $ProductGeneral->config['config_currency'] ) );

		$json['success'] = $lang_ext['text_cart'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_customer' )
{
	$json = array();

	$data['customer'] = $nv_Request->get_string( 'customer', 'post', '' );
	$data['email'] = $nv_Request->get_string( 'email', 'post', '' );
	$data['fax'] = $nv_Request->get_string( 'fax', 'post', '' );
	$data['first_name'] = $nv_Request->get_string( 'first_name', 'post', '' );
	$data['last_name'] = $nv_Request->get_string( 'last_name', 'post', '' );
	$data['telephone'] = $nv_Request->get_string( 'telephone', 'post', '' );
	$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	$data['userid'] = $nv_Request->get_int( 'userid', 'post', 0 );
	$data['store_id'] = $nv_Request->get_int( 'store_id', 'post', 0 );

	$_SESSION[$module_data . '_customer'] = $data;

	$json['success'] = $lang_ext['text_success_customer'];

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}
 
if( ACTION_METHOD == 'set_payment_address' )
{

	$data['first_name'] = $nv_Request->get_string( 'first_name', 'post', '' );
	$data['last_name'] = $nv_Request->get_string( 'last_name', 'post', '' );
	$data['address_1'] = $nv_Request->get_string( 'address_1', 'post', '' );
	$data['address_2'] = $nv_Request->get_string( 'address_2', 'post', '' );
	$data['city'] = $nv_Request->get_string( 'city', 'post', '' );
	$data['company'] = $nv_Request->get_string( 'company', 'post', '' );
	$data['postcode'] = $nv_Request->get_string( 'poscountry_idtcode', 'post', '' );
	$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
	$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$data['payment_address'] = $nv_Request->get_int( 'payment_address', 'post', 0 );

	$json = array();

	unset( $_SESSION[$module_data . '_payment_address'] );
	unset( $_SESSION[$module_data . '_payment_methods'] );
	unset( $_SESSION[$module_data . '_payment_method'] );

	if( ( nv_strlen( trim( $data['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['first_name'] ) ) > 32 ) )
	{
		$json['error']['first_name'] = $lang_ext['error_payment_first_name'];
	}

	if( ( nv_strlen( trim( $data['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['last_name'] ) ) > 32 ) )
	{
		$json['error']['last_name'] = $lang_ext['error_payment_last_name'];
	}

	if( ( nv_strlen( trim( $data['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $data['address_1'] ) ) > 128 ) )
	{
		$json['error']['address_1'] = $lang_ext['error_payment_address_1'];
	}

	if( ( nv_strlen( $data['city'] ) < 2 ) || ( nv_strlen( $data['city'] ) > 32 ) )
	{
		$json['error']['city'] = $lang_ext['error_payment_city'];
	}

	$getCountry = getCountry( );
	
	$country_info = $getCountry[$data['country_id']];
	
	if( $country_info && $country_info['postcode_required'] && ( nv_strlen( trim( $data['postcode'] ) ) < 2 || nv_strlen( trim( $data['postcode'] ) ) > 10 ) )
	{
		$json['error']['postcode'] = $lang_ext['error_payment_postcode'];
	}

	if( empty( $data['country_id'] ) )
	{
		$json['error']['country'] = $lang_ext['error_payment_country'];
	}

	if( ! isset( $data['zone_id'] ) || $data['zone_id'] == '' )
	{
		$json['error']['zone'] = $lang_ext['error_payment_zone'];
	}

	// $custom_fields = getCustomFields( $ProductGeneral->config['config_customer_group_id'] );

	// foreach( $custom_fields as $custom_field )
	// {
	// if( ( $custom_field['location'] == 'address' ) && $custom_field['required'] && empty( $data['custom_field'][$custom_field['custom_field_id']] ) )
	// {
	// $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf( $lang_ext['error_payment_custom_field'], $custom_field['name'] );
	// }
	// }

	if( ! $json )
	{
		$getCountry = getCountry();

		$country_info = $getCountry[$data['country_id']];

		if( $country_info )
		{
			$country = $country_info['name'];
			$iso_code_2 = $country_info['iso_code_2'];
			$iso_code_3 = $country_info['iso_code_3'];
			$address_format = $country_info['address_format'];
		}
		else
		{
			$country = '';
			$iso_code_2 = '';
			$iso_code_3 = '';
			$address_format = '';
		}

		$zone_info = getZoneName( $data['zone_id'] );

		if( $zone_info )
		{
			$zone = $zone_info['name'];
			$zone_code = $zone_info['code'];
		}
		else
		{
			$zone = '';
			$zone_code = '';
		}

		$_SESSION[$module_data . '_payment_address'] = array(
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'company' => $data['company'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'postcode' => $data['postcode'],
			'city' => $data['city'],
			'zone_id' => $data['zone_id'],
			'zone' => $zone,
			'zone_code' => $zone_code,
			'country_id' => $data['country_id'],
			'country' => $country,
			'iso_code_2' => $iso_code_2,
			'iso_code_3' => $iso_code_3,
			'address_format' => $address_format,
			'custom_field' => isset( $data['custom_field'] ) ? $data['custom_field'] : array() );

		$json['success'] = $lang_ext['text_payment_address'];

		unset( $_SESSION[$module_data . '_payment_method'] );
		unset( $_SESSION[$module_data . '_payment_methods'] );
 
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_payment_methods' )
{

	// Delete past shipping methods and method just in case there is an error
	unset( $_SESSION[$module_data . '_payment_methods'] );
	unset( $_SESSION[$module_data . '_payment_method'] );

	$json = array();

	// Payment Address
	if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
	{
		$json['error'] = $lang_ext['error_payment_address'];
	}

	if( ! $json )
	{

		$ProductContent = new shops_product( $productRegistry );
		// Totals
		$total_data = array();
		$total = 0;
		$taxes = $ProductContent->getTaxes();

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

		foreach( $array_class as &$class )
		{
			${$class} = new $class( $productRegistry );
			${$class}->getTotal( $total_data, $total, $taxes );

		}

		// Payment Methods
		$json['payment_methods'] = array();

		$results = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'payment' ) )->fetchAll();

		$recurring = $ProductContent->hasRecurringProducts();

		foreach( $results as $key => $result )
		{
			$payment_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );

			if( $payment_config[$result['code'] . '_status'] )
			{
				require_once NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $result['code'] . '.php';
				
				$method = call_user_func( 'getMethod_' . $result['code'], $_SESSION[$module_data . '_payment_address'], $total );
 
				if( $method )
				{
					if( $recurring )
					{
						if( nv_function_exists( 'recurringPayments_' . $result['code'] ) && call_user_func( 'recurringPayments_' . $result['code'] ) )
						{
							$json['payment_methods'][$result['code']] = $method;
						}
					}
					else
					{
						$json['payment_methods'][$result['code']] = $method;
					}
				}
			}
		}

		$sort_order = array();

		foreach( $json['payment_methods'] as $key => $value )
		{
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort( $sort_order, SORT_ASC, $json['payment_methods'] );

		if( $json['payment_methods'] )
		{
			$_SESSION[$module_data . '_payment_methods'] = $json['payment_methods'];
		}
		else
		{
			$json['error'] = $lang_ext['error_payment_no_payment'];
		}
		 
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_payment_method' )
{

	// Delete old payment method so not to cause any issues if there is an error
	unset( $_SESSION[$module_data . '_payment_method'] );

	$json = array();

	$data['payment_method'] = $nv_Request->get_string( 'payment_method', 'post', '' );

	// Payment Address
	if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
	{
		$json['error'] = $lang_ext['error_payment_address'];
	}

	// Payment Method
	if( empty( $_SESSION[$module_data . '_payment_methods'] ) )
	{
		$json['error'] = $lang_ext['error_payment_no_payment'];
	}
	elseif( empty( $data['payment_method'] ) )
	{
		$json['error'] = $lang_ext['error_payment_method'];
	}
	elseif( ! isset( $_SESSION[$module_data . '_payment_methods'][$data['payment_method']] ) )
	{
		$json['error'] = $lang_ext['error_payment_method'];
	}

	if( ! $json )
	{
		$_SESSION[$module_data . '_payment_method'] = $_SESSION[$module_data . '_payment_methods'][$data['payment_method']];

		$json['success'] = $lang_ext['text_payment_method'];
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_shipping_address' )
{
	
	$getCountry = getCountry( );
	
	$data['first_name'] = $nv_Request->get_string( 'first_name', 'post', '' );
	$data['last_name'] = $nv_Request->get_string( 'last_name', 'post', '' );
	$data['address_1'] = $nv_Request->get_string( 'address_1', 'post', '' );
	$data['address_2'] = $nv_Request->get_string( 'address_2', 'post', '' );
	$data['city'] = $nv_Request->get_string( 'city', 'post', '' );
	$data['company'] = $nv_Request->get_string( 'company', 'post', '' );
	$data['postcode'] = $nv_Request->get_string( 'poscountry_idtcode', 'post', '' );
	$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
	$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$data['shipping_address'] = $nv_Request->get_int( 'shipping_address', 'post', 0 );

	$json = array();

	unset( $_SESSION[$module_data . '_shipping_address'] );
	unset( $_SESSION[$module_data . '_shipping_methods'] );
	unset( $_SESSION[$module_data . '_shipping_method'] );

	if( ( nv_strlen( trim( $data['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['first_name'] ) ) > 32 ) )
	{
		$json['error']['first_name'] = $lang_ext['error_shipping_first_name'];
	}

	if( ( nv_strlen( trim( $data['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['last_name'] ) ) > 32 ) )
	{
		$json['error']['last_name'] = $lang_ext['error_shipping_last_name'];
	}

	if( ( nv_strlen( trim( $data['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $data['address_1'] ) ) > 128 ) )
	{
		$json['error']['address_1'] = $lang_ext['error_shipping_address_1'];
	}

	if( ( nv_strlen( $data['city'] ) < 2 ) || ( nv_strlen( $data['city'] ) > 32 ) )
	{
		$json['error']['city'] = $lang_ext['error_shipping_city'];
	}

	
	
	$country_info = $getCountry[$data['country_id']];
	
	if( $country_info && $country_info['postcode_required'] && ( nv_strlen( trim( $data['postcode'] ) ) < 2 || nv_strlen( trim( $data['postcode'] ) ) > 10 ) )
	{
		$json['error']['postcode'] = $lang_ext['error_shipping_postcode'];
	}

	if( empty( $data['country_id'] ) )
	{
		$json['error']['country'] = $lang_ext['error_shipping_country'];
	}

	if( ! isset( $data['zone_id'] ) || $data['zone_id'] == '' )
	{
		$json['error']['zone'] = $lang_ext['error_shipping_zone'];
	}

	// $custom_fields = getCustomFields( $ProductGeneral->config['config_customer_group_id'] );

	// foreach( $custom_fields as $custom_field )
	// {
	// if( ( $custom_field['location'] == 'address' ) && $custom_field['required'] && empty( $data['custom_field'][$custom_field['custom_field_id']] ) )
	// {
	// $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf( $lang_ext['error_payment_custom_field'], $custom_field['name'] );
	// }
	// }

	if( ! $json )
	{
 
		$country_info = $getCountry[$data['country_id']];

		if( $country_info )
		{
			$country = $country_info['name'];
			$iso_code_2 = $country_info['iso_code_2'];
			$iso_code_3 = $country_info['iso_code_3'];
			$address_format = $country_info['address_format'];
		}
		else
		{
			$country = '';
			$iso_code_2 = '';
			$iso_code_3 = '';
			$address_format = '';
		}

		$zone_info = getZoneName( $data['zone_id'] );

		if( $zone_info )
		{
			$zone = $zone_info['name'];
			$zone_code = $zone_info['code'];
		}
		else
		{
			$zone = '';
			$zone_code = '';
		}

		$_SESSION[$module_data . '_shipping_address'] = array(
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'company' => $data['company'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'postcode' => $data['postcode'],
			'city' => $data['city'],
			'zone_id' => $data['zone_id'],
			'zone' => $zone,
			'zone_code' => $zone_code,
			'country_id' => $data['country_id'],
			'country' => $country,
			'iso_code_2' => $iso_code_2,
			'iso_code_3' => $iso_code_3,
			'address_format' => $address_format,
			'custom_field' => isset( $data['custom_field'] ) ? $data['custom_field'] : array() );

		$json['success'] = $lang_ext['text_shipping_address'];

		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
 
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_shipping_methods' )
{

	// Delete past shipping methods and method just in case there is an error
	unset( $_SESSION[$module_data . '_shipping_methods'] );
	unset( $_SESSION[$module_data . '_shipping_method'] );

	$json = array();
	
	$ProductContent = new shops_product( $productRegistry );
	
	if( $ProductContent->hasShipping() )
	{
		if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
		{
			$json['error'] = $lang_ext['error_shipping_address'];
		}

		if( ! $json )
		{
			// Shipping Methods
			$json['shipping_methods'] = array();
			
			$sort_order = array();

			$results = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'shipping' ) )->fetchAll();

			foreach( $results as $key => $value )
			{
				$shipping_config = $ProductGeneral->getSetting( $value['code'], $ProductGeneral->store_id );

				$sort_order[$key] = isset( $shipping_config[$value['code'] . '_sort_order'] ) ? $shipping_config[$value['code'] . '_sort_order'] : 0;

			}

			array_multisort( $sort_order, SORT_ASC, $results );

			$array_class = array();

			foreach( $results as $result )
			{
				$shipping_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );

				if( isset( $shipping_config[$result['code'] . '_status'] ) && $shipping_config[$result['code'] . '_status'] )
				{
					$array_class[] = $result['code'];
				}
			}
			
			foreach( $array_class as $key => &$class )
			{
				${$class} = new $class( $productRegistry );
				$quote = ${$class}->getQuote( $_SESSION[$module_data . '_shipping_address'] );
				if ($quote) 
				{
					$json['shipping_methods'][$class] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
 
			$sort_order = array();

			foreach( $json['shipping_methods'] as $key => $value )
			{
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort( $sort_order, SORT_ASC, $json['shipping_methods'] );

			if( $json['shipping_methods'] )
			{
				$_SESSION[$module_data . '_shipping_methods'] = $json['shipping_methods'];
			}
			else
			{
				$json['error'] = $lang_ext['error_shipping_no_shipping'];
			}
		}
	}
	else
	{
		$json['shipping_methods'] = array();
	}
 
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}
 
if( ACTION_METHOD == 'set_shipping_method' )
{
	$shipping_method = $nv_Request->get_string( 'shipping_method', 'post', '' );
	
	// Delete old shipping method so not to cause any issues if there is an error
	unset( $_SESSION[$module_data . '_shipping_method'] );

	$json = array();
	$ProductContent = new shops_product( $productRegistry );
	if( $ProductContent->hasShipping() )
	{
		// Shipping Address
		if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
		{
			$json['error'] = $lang_ext['error_shipping_address'];
		}

		// Shipping Method
		if( empty( $_SESSION[$module_data . '_shipping_methods'] ) )
		{
			$json['error'] = $lang_ext['error_shipping_no_shipping'];
		}
		elseif( empty( $shipping_method ) )
		{
			$json['error'] = $lang_ext['error_shipping_method'];
		}
		else
		{
			$shipping = explode( '.', $shipping_method );

			if( ! isset( $shipping[0] ) || ! isset( $shipping[1] ) || ! isset( $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]] ) )
			{
				$json['error'] = $lang_ext['error_shipping_method'];
			}
		}

		if( ! $json )
		{
			$_SESSION[$module_data . '_shipping_method'] = $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$json['success'] = $lang_ext['text_shipping_method'];
		}
	}
	else
	{
		unset( $_SESSION[$module_data . '_shipping_address'] );
		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_coupon' )
{

	$coupon = $nv_Request->get_string( 'coupon', 'post', '' );
	
	// Delete past coupon in case there is an error
	unset( $_SESSION[$module_data . '_coupon'] );
 
	$json = array();
	
	$shops_coupon = new shops_coupon( $productRegistry );
	
	$coupon_info = $shops_coupon->getCoupon( $coupon ); 
	
	if ( $coupon_info ) 
	{
		$_SESSION[$module_data . '_coupon'] = $coupon;

		$json['success'] = $lang_ext['text_coupon_success'];
		
	} else 
	{
		$json['error'] = $lang_ext['error_coupon'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_voucher' )
{
 
	// Delete past voucher in case there is an error
	unset( $_SESSION[$module_data . '_voucher'] );

	$json = array();
	
	$voucher = $nv_Request->get_string('voucher', 'post', '');
 

	$voucher_info = getVoucher($voucher);

	if ( $voucher_info ) 
	{
		$_SESSION[$module_data . '_voucher'] = $voucher;

		$json['success'] = $lang_ext['text_voucher_success'];
	} else 
	{
		$json['error'] = $lang_ext['error_voucher'];
	}
	 
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'set_reward' )
{
	$reward = $nv_Request->get_string( 'reward', 'post', '' );
 
	$json = array();
	
	$points = $db->query('SELECT SUM(points) FROM ' . TABLE_PRODUCT_NAME . '_reward WHERE userid = ' . (int)$admin_info['userid'])->fetch();

	$points_total = 0;
	
	$ProductContent = new shops_product( $productRegistry );
	
	$getProducts = getProducts();
	
	foreach( $getProducts as $product )
	{
		if( $product['points'] )
		{
			$points_total += $product['points'];
		}
	}

	if( empty( $reward ) )
	{
		$json['error'] = $lang_ext['error_reward'];
	}

	if( $reward > $points )
	{
		$json['error'] = sprintf( $lang_ext['error_points'], $reward );
	}

	if( $reward > $points_total )
	{
		$json['error'] = sprintf( $lang_ext['error_maximum'], $points_total );
	}

	if( ! $json )
	{
		$_SESSION[$module_data . '_reward'] = abs( $reward );

		$json['success'] = $lang_ext['text_reward_success'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'order_add' )
{
	$json = array();
	
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'order_edit' )
{
	$json = array();
	$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
	$affiliate_id = $nv_Request->get_int( 'affiliate_id', 'post', 0 );
	$order_status_id = $nv_Request->get_int( 'order_status_id', 'post', 0 );
	$comment = $nv_Request->get_string( 'comment', 'post', '' );
	$payment_method = $nv_Request->get_string( 'payment_method', 'post', '' );
	$shipping_method = $nv_Request->get_string( 'shipping_method', 'post', '' );
	
	$ProductContent = new shops_product( $productRegistry );
	
	// Call function getOrder, editOrder 
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/function_order.php';
	
	$order_info = getOrder( $order_id );
	
	if( $order_info )
	{
		// Customer
		if( ! isset( $_SESSION[$module_data . '_customer'] ) )
		{
			$json['error'] = $lang_ext['error_order_customer'];
		}

		// Payment Address
		if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
		{
			$json['error'] = $lang_ext['error_payment_address'];
		}
 
		// Payment Method
		if( ! isset( $_SESSION[$module_data . '_payment_method'] ) )
		{
			$json['error'] = $lang_ext['error_payment_method'];
		}

		// Shipping
		if( $ProductContent->hasShipping() )
		{
			// Shipping Address
			if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
			{
				$json['error'] = $lang_ext['error_shipping_address'];
			}

			// Shipping Method
			if( ! isset( $_SESSION[$module_data . '_shipping_method'] ) )
			{
				$json['error'] = $lang_ext['error_shipping_method'];
			}
		}
		else
		{
			unset( $_SESSION[$module_data . '_shipping_address'] );
			unset( $_SESSION[$module_data . '_shipping_method'] );
			unset( $_SESSION[$module_data . '_shipping_methods'] );
		}

		// Cart
		if( ( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
		{
			$json['error'] = $lang_ext['error_stock'];
		}

		// Validate minimum quantity requirements.
		$products = $ProductContent->getProducts();

		foreach( $products as $product )
		{
			$product_total = 0;

			foreach( $products as $product_2 )
			{
				if( $product_2['product_id'] == $product['product_id'] )
				{
					$product_total += $product_2['quantity'];
				}
			}

			if( $product['minimum'] > $product_total )
			{
				$json['error'] = sprintf( $lang_ext['error_minimum'], $product['name'], $product['minimum'] );

				break;
			}
		}

		if( ! $json )
		{
			$order_data = array();
			
			$ProductTax = new shops_tax( $productRegistry );
			$getStores = getStores();
			$getStores[0] = array(
			'store_id' => 0,
			'name' => $ProductGeneral->config['config_name'] . '<b> (' . $lang_module['text_default'] . ')</b>',
			'url' => $ProductGeneral->config['config_url'] );
			// Store Details
			$order_data['invoice_prefix'] = $order_info['invoice_prefix'];
			$order_data['store_id'] = $ProductGeneral->store_id;
			$order_data['store_name'] = $getStores[$ProductGeneral->store_id]['name'];
			$order_data['store_url'] = $getStores[$ProductGeneral->store_id]['url'];

			// Customer Details
			$order_data['userid'] = $_SESSION[$module_data . '_customer']['userid'];
			$order_data['customer_group_id'] = $_SESSION[$module_data . '_customer']['customer_group_id'];
			$order_data['first_name'] = $_SESSION[$module_data . '_customer']['first_name'];
			$order_data['last_name'] = $_SESSION[$module_data . '_customer']['last_name'];
			$order_data['email'] = $_SESSION[$module_data . '_customer']['email'];
			$order_data['telephone'] = $_SESSION[$module_data . '_customer']['telephone'];
			$order_data['fax'] = $_SESSION[$module_data . '_customer']['fax'];
			//$order_data['custom_field'] = $_SESSION[$module_data . '_customer']['custom_field'];

			// Payment Details
			$order_data['payment_first_name'] = $_SESSION[$module_data . '_payment_address']['first_name'];
			$order_data['payment_last_name'] = $_SESSION[$module_data . '_payment_address']['last_name'];
			$order_data['payment_company'] = $_SESSION[$module_data . '_payment_address']['company'];
			$order_data['payment_address_1'] = $_SESSION[$module_data . '_payment_address']['address_1'];
			$order_data['payment_address_2'] = $_SESSION[$module_data . '_payment_address']['address_2'];
			$order_data['payment_city'] = $_SESSION[$module_data . '_payment_address']['city'];
			$order_data['payment_postcode'] = $_SESSION[$module_data . '_payment_address']['postcode'];
			$order_data['payment_zone'] = $_SESSION[$module_data . '_payment_address']['zone'];
			$order_data['payment_zone_id'] = $_SESSION[$module_data . '_payment_address']['zone_id'];
			$order_data['payment_country'] = $_SESSION[$module_data . '_payment_address']['country'];
			$order_data['payment_country_id'] = $_SESSION[$module_data . '_payment_address']['country_id'];
			$order_data['payment_address_format'] = $_SESSION[$module_data . '_payment_address']['address_format'];
			$order_data['payment_custom_field'] = $_SESSION[$module_data . '_payment_address']['custom_field'];

			if( isset( $_SESSION[$module_data . '_payment_method']['title'] ) )
			{
				$order_data['payment_method'] = $_SESSION[$module_data . '_payment_method']['title'];
			}
			else
			{
				$order_data['payment_method'] = '';
			}

			if( isset( $_SESSION[$module_data . '_payment_method']['code'] ) )
			{
				$order_data['payment_code'] = $_SESSION[$module_data . '_payment_method']['code'];
			}
			else
			{
				$order_data['payment_code'] = '';
			}

			// Shipping Details
			if( $ProductContent->hasShipping() )
			{
				$order_data['shipping_first_name'] = $_SESSION[$module_data . '_shipping_address']['first_name'];
				$order_data['shipping_last_name'] = $_SESSION[$module_data . '_shipping_address']['last_name'];
				$order_data['shipping_company'] = $_SESSION[$module_data . '_shipping_address']['company'];
				$order_data['shipping_address_1'] = $_SESSION[$module_data . '_shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $_SESSION[$module_data . '_shipping_address']['address_2'];
				$order_data['shipping_city'] = $_SESSION[$module_data . '_shipping_address']['city'];
				$order_data['shipping_postcode'] = $_SESSION[$module_data . '_shipping_address']['postcode'];
				$order_data['shipping_zone'] = $_SESSION[$module_data . '_shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $_SESSION[$module_data . '_shipping_address']['zone_id'];
				$order_data['shipping_country'] = $_SESSION[$module_data . '_shipping_address']['country'];
				$order_data['shipping_country_id'] = $_SESSION[$module_data . '_shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $_SESSION[$module_data . '_shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = $_SESSION[$module_data . '_shipping_address']['custom_field'];

				if( isset( $_SESSION[$module_data . '_shipping_method']['title'] ) )
				{
					$order_data['shipping_method'] = $_SESSION[$module_data . '_shipping_method']['title'];
				}
				else
				{
					$order_data['shipping_method'] = '';
				}

				if( isset( $_SESSION[$module_data . '_shipping_method']['code'] ) )
				{
					$order_data['shipping_code'] = $_SESSION[$module_data . '_shipping_method']['code'];
				}
				else
				{
					$order_data['shipping_code'] = '';
				}
			}
			else
			{
				$order_data['invoice_prefix'] = '';
				$order_data['shipping_first_name'] = '';
				$order_data['shipping_last_name'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}
 
			// Products
			$order_data['products'] = array();

			foreach( $ProductContent->getProducts() as $product )
			{
				$option_data = array();

				foreach( $product['option'] as $option )
				{
					$option_data[] = array(
						'product_option_id' => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id' => $option['option_id'],
						'option_value_id' => $option['option_value_id'],
						'name' => $option['name'],
						'value' => $option['value'],
						'type' => $option['type'] );
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'quantity' => $product['quantity'],
					'subtract' => $product['subtract'],
					'price' => $product['price'],
					'total' => $product['total'],
					'tax' => $ProductTax->getTax( $product['price'], $product['tax_class_id'] ),
					'reward' => $product['reward'] );
			}

			// Gift Voucher
			$order_data['vouchers'] = array();

			if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
			{
				foreach( $_SESSION[$module_data . '_vouchers'] as $voucher )
				{
					$order_data['vouchers'][] = array(
						'description' => $voucher['description'],
						'code' => substr( md5( mt_rand() ), 0, 10 ),
						'to_name' => $voucher['to_name'],
						'to_email' => $voucher['to_email'],
						'from_name' => $voucher['from_name'],
						'from_email' => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message' => $voucher['message'],
						'amount' => $voucher['amount'] );
				}
			}

			// Order Totals
 
			$order_data['totals'] = array();
			$total = 0;
			$taxes = $ProductContent->getTaxes();

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
				${$class}->getTotal( $order_data['totals'], $total, $taxes );

			}
			$sort_order = array();

			foreach( $order_data['totals'] as $key => $value )
			{

				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort( $sort_order, SORT_ASC, $order_data['totals'] );
 
			$order_data['comment'] = $comment;
 

			$order_data['total'] = $total;

			// if( !empty( $affiliate_id ) )
			// {
				// $subtotal = $ProductContent->getSubTotal();

 
				// $affiliate_info = getAffiliate( $affiliate_id );

				// if( $affiliate_info )
				// {
					// $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
					// $order_data['commission'] = ( $subtotal / 100 ) * $affiliate_info['commission'];
				// }
				// else
				// {
					// $order_data['affiliate_id'] = 0;
					// $order_data['commission'] = 0;
				// }
			// }
			// else
			// {
				// $order_data['affiliate_id'] = 0;
				// $order_data['commission'] = 0;
			// }
			
			$order_data['affiliate_id'] = 0;
			
			editOrder( $order_id, $order_data );

			// Set the order history
			if( empty( $order_status_id ) )
			{ 
				$order_status_id = $ProductGeneral->config['config_order_status_id'];
			}
 
			addOrderHistory( $order_id, $order_status_id );

			$json['success'] = $lang_ext['text_order_success'];
		}
	}
	else
	{
		$json['error'] = $lang_ext['error_not_found'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

$page_title = $lang_module['order_title'];

unset( $_SESSION[$module_data . '_customer']['cookie'] );
unset( $_SESSION[$module_data . '_payment_method'] );
unset( $_SESSION[$module_data . '_payment_methods'] );
unset( $_SESSION[$module_data . '_payment_address'] );
unset( $_SESSION[$module_data . '_shipping_address'] );
unset( $_SESSION[$module_data . '_shipping_method'] );
unset( $_SESSION[$module_data . '_shipping_methods'] );
unset( $_SESSION[$module_data . '_coupon'] );
unset( $_SESSION[$module_data . '_voucher'] );
unset( $_SESSION[$module_data . '_vouchers'] );
 
$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
$order_status_id = $nv_Request->get_int( 'order_status_id', 'post,get', 0 );
$token = md5( $global_config['sitekey'] . session_id() . $order_id );

$save = $nv_Request->get_string( 'save', 'post', '' );

$order_info = getSaleOrder( $order_id );

if( empty( $order_info ) ) Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order' );
 
$data = array();
$data['order_id'] = $order_id;
$data['invoice_prefix'] = $order_info['invoice_prefix'];

$customer_group_info = getCustomerGroup();

if( isset( $customer_group_info[$order_info['customer_group_id']] ) )
{
	$data['customer_group'] = $customer_group_info[$order_info['customer_group_id']]['name'];
}
else
{
	$data['customer_group'] = '';
}

if( $order_info['userid'] )
{
	$data['customer_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer&action=edit&userid=' . $order_info['userid'];
}
else
{
	$data['customer_url'] = '';
}
$data['store_id'] = $order_info['store_id'];
$data['userid'] = $order_info['userid'];
$data['customer_group_id'] = $order_info['customer_group_id'];
$data['first_name'] = $order_info['first_name'];
$data['last_name'] = $order_info['last_name'];
$data['full_name'] = ( $global_config['name_show'] ) ? $data['first_name'] . ' ' . $data['last_name'] : $data['last_name'] . ' ' . $data['first_name'];
$data['full_name'] = trim( $data['full_name'] );
$data['email'] = $order_info['email'];
$data['telephone'] = $order_info['telephone'];
$data['fax'] = $order_info['fax'];
$data['comment'] = nv_nl2br( $order_info['comment'] );
$data['payment_code'] = $order_info['payment_code'];
$data['payment_method'] = $order_info['payment_method'];
$data['total'] = $ProductCurrency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'] );
$data['reward'] = $order_info['reward'];
$data['reward_total'] = $db->query( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_' . $module_name . '_reward WHERE order_id = ' . ( int )$order_id )->fetchColumn();
$data['ip'] = $order_info['ip'];
$data['forwarded_ip'] = $order_info['forwarded_ip'];
$data['user_agent'] = $order_info['user_agent'];
$data['accept_language'] = $order_info['accept_language'];
$data['date_added'] = date( 'd/m/Y', $order_info['date_added'] );
$data['date_modified'] = date( 'd/m/Y', $order_info['date_modified'] );
$data['payment_first_name'] = $order_info['payment_first_name'];
$data['payment_last_name'] = $order_info['payment_last_name'];
$data['payment_company'] = $order_info['payment_company'];
$data['payment_address_1'] = $order_info['payment_address_1'];
$data['payment_address_2'] = $order_info['payment_address_2'];
$data['payment_city'] = $order_info['payment_city'];
$data['payment_postcode'] = $order_info['payment_postcode'];
$data['payment_zone'] = $order_info['payment_zone'];
$data['payment_zone_code'] = $order_info['payment_zone_code'];
$data['payment_zone_id'] = $order_info['payment_zone_id'];
$data['payment_country'] = $order_info['payment_country'];
$data['payment_country_id'] = $order_info['payment_country_id'];


$data['shipping_first_name'] = $order_info['shipping_first_name'];
$data['shipping_last_name'] = $order_info['shipping_last_name'];
$data['shipping_company'] = $order_info['shipping_company'];
$data['shipping_address_1'] = $order_info['shipping_address_1'];
$data['shipping_address_2'] = $order_info['shipping_address_2'];
$data['shipping_city'] = $order_info['shipping_city'];
$data['shipping_postcode'] = $order_info['shipping_postcode'];
$data['shipping_zone'] = $order_info['shipping_zone'];
$data['shipping_zone_code'] = $order_info['shipping_zone_code'];
$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
$data['shipping_country'] = $order_info['shipping_country'];
$data['shipping_country_id'] = $order_info['shipping_country_id'];
$data['shipping_method'] = $order_info['shipping_method'];
$data['shipping_code'] = $order_info['shipping_code'];

 
$data['voucher_themes'] = getVoucherThemes();

$data['order_products'] = array();

$getOrderProducts = getOrderProducts( $order_id );

foreach( $getOrderProducts as $product )
{
	$option_data = array();

	$options = getOrderOptions( $order_id, $product['order_product_id'] );

	$data['order_products'][] = array(
		'order_product_id' => $product['order_product_id'],
		'product_id' => $product['product_id'],
		'name' => $product['name'],
		'model' => $product['model'],
		'option' => $options,
		'quantity' => $product['quantity'],
		'price' => $ProductCurrency->format( $product['price'] + ( $ProductGeneral->config['config_tax'] ? $product['tax'] : 0 ), $order_info['currency_code'], $order_info['currency_value'] ),
		'total' => $ProductCurrency->format( $product['total'] + ( $ProductGeneral->config['config_tax'] ? ( $product['tax'] * $product['quantity'] ) : 0 ), $order_info['currency_code'], $order_info['currency_value'] ),
		'href' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product&action=edit&product_id=' . $product['product_id'] );
}

unset( $getOrderProducts );

$getOrderVouchers = getOrderVouchers( $order_id );

foreach( $getOrderVouchers as $voucher )
{
	$data['order_vouchers'][] = array(
		'description' => $voucher['description'],
		'amount' => $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] ),
		'href' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voucher&action=edit&&voucher_id=' . $voucher['voucher_id'] );
}

unset( $getOrderVouchers );

$getOrderTotals = getOrderTotals( $order_id );

foreach( $getOrderTotals as $total )
{
	$data['totals'][] = array(
		'title' => $total['title'],
		'text' => $ProductCurrency->format( $total['value'], $order_info['currency_code'], $order_info['currency_value'] ),
		);
}

unset( $getOrderTotals );

$data['order_status_id'] = $order_info['order_status_id'];

$xtpl = new XTemplate( 'order_edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order" );

$getStores = getStores();
$getStores[0] = array( 'store_id' => 0, 'name' => $lang_module['text_default'] );
if( ! empty( $getStores ) )
{
	foreach( $getStores as $store_id => $store )
	{

		$xtpl->assign( 'STORE', array(
			'key' => $store_id,
			'name' => $store['name'],
			'selected' => ( $store_id == $data['store_id'] ? 'selected="selected"' : '' ) ) );
		$xtpl->parse( 'main.store' );
	}
}

$getCountry = getCountry();
foreach( $getCountry as $country_id => $_value )
{
	$xtpl->assign( 'PCOUNTRY', array(
		'key' => $country_id,
		'name' => nv_htmlspecialchars( $_value['name'] ),
		'selected' => ( $country_id == $data['payment_country_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.pcountry' );

	$xtpl->assign( 'SCOUNTRY', array(
		'key' => $country_id,
		'name' => nv_htmlspecialchars( $_value['name'] ),
		'selected' => ( $country_id == $data['shipping_country_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.scountry' );

}

$getCustomerGroup = getCustomerGroup();
foreach( $getCustomerGroup as $customer_group_id => $value )
{

	$xtpl->assign( 'CGROUP', array(
		'key' => $customer_group_id,
		'name' => nv_htmlspecialchars( $value['name'] ),
		'selected' => ( $customer_group_id == $data['customer_group_id'] ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.customer_group' );
}

foreach( $data['voucher_themes'] as $voucher_theme )
{
	$xtpl->assign( 'VOUCHER_THEME', $voucher_theme );
	$xtpl->parse( 'main.voucher_theme' );
}

if( ! empty( $data['order_products'] ) )
{
	$key = 0;
	foreach( $data['order_products'] as $product )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'PRODUCT', $product );
		$xtpl->parse( 'main.product' );
		++$key;
	}

}
else
{
	$xtpl->parse( 'main.no_results' );
}

$getOrderStatuses = getOrderStatuses();
foreach( $getOrderStatuses as $order_status_id => $value )
{
	$xtpl->assign( 'ORDER_STATUS', array(
		'key' => $order_status_id,
		'name' => nv_htmlspecialchars( $value['name'] ),
		'selected' => ( $order_status_id == $data['order_status_id'] ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.order_status' );
}

$getAddresses = getAddresses( $order_info['userid'] );

foreach( $getAddresses as $address_id => $address )
{
	$xtpl->assign( 'ADDRESS', array( 'key' => $address_id, 'name' => nv_htmlspecialchars( $address['first_name'] . ' ' . $address['last_name'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country'] ) ) );
	$xtpl->parse( 'main.payment_address' );
	$xtpl->parse( 'main.shipping_address' );
}

if( $data['shipping_code'] )
{
	$xtpl->assign( 'SHIPPING', array( 'key' => $data['shipping_code'], 'name' => nv_htmlspecialchars( $data['shipping_method'] ) ) );
	$xtpl->parse( 'main.shipping_method' );
}

if( $data['payment_code'] )
{
	$xtpl->assign( 'PAYMENT', array( 'key' => $data['payment_code'], 'name' => nv_htmlspecialchars( $data['payment_method'] ) ) );
	$xtpl->parse( 'main.payment_method' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
