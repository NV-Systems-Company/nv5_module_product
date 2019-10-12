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

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

if( $nv_Request->get_title( 'action', 'get', '' ) == 'zone' )
{
	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );
 
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

if( $nv_Request->get_title( 'action', 'get,post', '' ) == 'quote' )
{
 
	$country_id = $nv_Request->get_int( 'country_id', 'post', 0 );
	$zone_id = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$postcode = $nv_Request->get_title( 'postcode', 'post', '' );

	$json = array();
	
	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );
	
	if( ! $ProductContent->hasProducts() )
	{
		$json['error']['warning'] = $lang_module['error_product'];
	}

	if( ! $ProductContent->hasShipping() )
	{
		$json['error']['warning'] = sprintf( $lang_module['shipping_error_no_shipping'], '/contact' );
	}

	if( $country_id == 0 )
	{
		$json['error']['country'] = $lang_module['shipping_error_country'];
	}

	if( ! isset( $zone_id ) || $zone_id == '' )
	{
		$json['error']['zone'] = $lang_module['shipping_error_zone'];
	}

	$getCountry = getCountry();

	$country_info = $getCountry[$country_id];

	if( $country_info && $country_info['postcode_required'] && ( nv_strlen( trim( $postcode ) ) < 2 || nv_strlen( trim( $postcode ) ) > 10 ) )
	{
		$json['error']['postcode'] = $lang_module['shipping_error_postcode'];
	}

	if( ! $json )
	{
		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );
		
		$ProductTax->setShippingAddress( $country_id, $zone_id );

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
 
		$zone_info = getZoneName( $zone_id );

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
			'first_name' => '',
			'last_name' => '',
			'company' => '',
			'address_1' => '',
			'address_2' => '',
			'postcode' => $postcode,
			'city' => '',
			'zone_id' => $zone_id,
			'zone' => $zone,
			'zone_code' => $zone_code,
			'country_id' => $country_id,
			'country' => $country,
			'iso_code_2' => $iso_code_2,
			'iso_code_3' => $iso_code_3,
			'address_format' => $address_format );

		$quote_data = array();
			
		//sort array
		$results = $db->query('SELECT code FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'shipping' ) )->fetchAll();
		
		foreach ($results as $key => $value) 
		{
			$sort_order[$key] = isset( $ProductGeneral->config[$value['code'] . '_sort_order'] ) ? $ProductGeneral->config[$value['code'] . '_sort_order']: 0;
			
		}

		array_multisort($sort_order, SORT_ASC, $results);
		
		$array_class = array();
		
		foreach( $results as $result )
		{
			if ( isset( $ProductGeneral->config[$result['code'] . '_status'] ) && $ProductGeneral->config[$result['code'] . '_status'] ) 
			{
				$array_class[] = $result['code'];
			}
		}

		// gọi class theo mảng truyền ngược lại biến global $total_data $total, $taxes
		foreach( $array_class as &$class )
		{
			${$class} = new $class( $productRegistry );
			$quote = ${$class}->getQuote( $_SESSION[$module_data . '_shipping_address'] );

			if( $quote )
			{
				$quote_data[$quote['code']] = array(
					'title' => $quote['title'],
					'quote' => $quote['quote'],
					'sort_order' => $quote['sort_order'],
					'error' => $quote['error'] );
			}
		}
 
		$sort_order = array();

		foreach( $quote_data as $key => $value )
		{
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort( $sort_order, SORT_ASC, $quote_data );

		$_SESSION[$module_data . '_shipping_methods'] = $quote_data;

		if( $_SESSION[$module_data . '_shipping_methods'] )
		{
			$json['shipping_method'] = $_SESSION[$module_data . '_shipping_methods'];
			
		}
		else
		{
			$json['error']['warning'] = sprintf( $lang_module['shipping_error_no_shipping'], '/contact' );
		}
		
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->get_title( 'action', 'get,post', '' ) == 'shipping' )
{
	$json = array();
	
	$shipping_method = $nv_Request->get_string( 'shipping_method', 'post', '' );
	$shipping_methods = $_SESSION[$module_data . '_shipping_methods'];
		
	if( ! empty( $shipping_method ) )
	{
		$shipping = explode( '.', $shipping_method );
 
		if( ! isset( $shipping[0] ) || ! isset( $shipping[1] ) || ! isset( $shipping_methods[$shipping[0]]['quote'][$shipping[1]] ) )
		{
			$json['warning'] = $lang_module['shipping_error_shipping'];
		}
	}
	else
	{
		$json['warning'] = $lang_module['shipping_error_shipping'];
	}

	if( ! $json )
	{
		$shipping = explode( '.', $shipping_method );
		
		// tao session shipping khi đặt hàng
		$_SESSION[$module_data . '_shipping_method'] = $shipping_methods[$shipping[0]]['quote'][$shipping[1]];
		
		// tạo session thông báo thành công
		$_SESSION[$module_data . '_success'] = $lang_module['shipping_text_success'];
		
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}