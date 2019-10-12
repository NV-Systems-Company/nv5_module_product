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

// gọi hàm liên quan tới địa chỉ khách hàng
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/address.php';

// lấy thông tin zone
if( ACTION_METHOD == 'zone' )
{
	$info = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );
	$getCountries = getCountries();
	$info = $getCountries[$country_id];

	$sql = 'SELECT zone_id, code, status, name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id=' . $country_id;
	$result = $db->query( $sql );

	while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
	{
		$info['zone'][] = array(
			'code' => $code,
			'country_id' => $country_id,
			'status' => $status,
			'name' => nv_htmlspecialchars( $name ),
			'zone_id' => $_zone_id );

	}

	header( 'Content-Type: application/json' );
	echo json_encode( $info );
	exit();
}

if( $nv_Request->isset_request( 'save', 'get,post' ) )
{
	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

	$getCountries = getCountries();
	$data['first_name'] = $nv_Request->get_title( 'first_name', 'post', '', 1 );
	$data['last_name'] = $nv_Request->get_title( 'last_name', 'post', '', 1 );
	$data['username'] = $data['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	$data['telephone'] = $nv_Request->get_title( 'telephone', 'post', '', 1 );
	$data['fax'] = $nv_Request->get_title( 'fax', 'post', '', 1 );
	$data['company'] = $nv_Request->get_title( 'company', 'post', '', 1 );
	$data['address_1'] = $nv_Request->get_title( 'address_1', 'post', '', 1 );
	$data['address_2'] = $nv_Request->get_title( 'address_2', 'post', '', 1 );
	$data['password'] = $nv_Request->get_title( 'password', 'post', '', 1 );
	$data['confirm'] = $nv_Request->get_title( 'confirm', 'post', '', 1 );
	$data['city'] = $nv_Request->get_title( 'city', 'post', '', 1 );
	$data['postcode'] = $nv_Request->get_title( 'postcode', 'post', '', 1 );
	$data['shipping_address'] = $nv_Request->get_title( 'shipping_address', 'post', '', 1 );
	$data['agree'] = $nv_Request->get_int( 'agree', 'post', 0 );
	$data['newsletter'] = $nv_Request->get_int( 'newsletter', 'post', 0 );
	$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
	$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	
	$json = array();
	
	// Validate if customer is logged in.
	if( ! empty( $user_info ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	}

	// Validate cart has products and has stock.
	if( ( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
	}

	// Check if guest checkout is available.
	if( ! $ProductGeneral->config['config_checkout_guest'] || $ProductGeneral->config['config_customer_price'] )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	}

	if( ! $json )
	{
		if( ( nv_strlen( trim( $data['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['first_name'] ) ) > 32 ) )
		{
			$json['error']['first_name'] = $lang_ext['error_first_name'];
		}

		if( ( nv_strlen( trim( $data['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['last_name'] ) ) > 32 ) )
		{
			$json['error']['last_name'] = $lang_ext['error_last_name'];
		}
 
		if( ( nv_strlen( trim( $data['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $data['address_1'] ) ) > 128 ) )
		{
			$json['error']['address_1'] = $lang_ext['error_address_1'];
		}

		if( ( nv_strlen( trim( $data['city'] ) ) < 2 ) || ( nv_strlen( trim( $data['city'] ) ) > 128 ) )
		{
			$json['error']['city'] = $lang_ext['error_city'];
		}

		if( $getCountries[$data['country_id']] && $getCountries[$data['country_id']]['postcode_required'] && ( nv_strlen( trim( $data['postcode'] ) ) < 2 || nv_strlen( trim( $data['postcode'] ) ) > 10 ) )
		{
			$json['error']['postcode'] = $lang_ext['error_postcode'];
		}

		if( $data['country_id'] == '' )
		{
			$json['error']['country'] = $lang_ext['error_country'];
		}

		if( ! isset( $data['zone_id'] ) || $data['zone_id'] == '' )
		{
			$json['error']['zone'] = $lang_ext['error_zone'];
		}
 
	}

	if( ! $json )
	{
		$_SESSION[$module_data . '_shipping_address']['first_name'] = $data['first_name'];
		$_SESSION[$module_data . '_shipping_address']['last_name'] = $data['last_name'];
		$_SESSION[$module_data . '_shipping_address']['company'] = $data['company'];
		$_SESSION[$module_data . '_shipping_address']['address_1'] = $data['address_1'];
		$_SESSION[$module_data . '_shipping_address']['address_2'] = $data['address_2'];
		$_SESSION[$module_data . '_shipping_address']['postcode'] = $data['postcode'];
		$_SESSION[$module_data . '_shipping_address']['city'] = $data['city'];
		$_SESSION[$module_data . '_shipping_address']['country_id'] = $data['country_id'];
		$_SESSION[$module_data . '_shipping_address']['zone_id'] = $data['zone_id'];

 
		$country_info = $getCountry[$data['country_id']];

		if( $country_info )
		{
			$_SESSION[$module_data . '_shipping_address']['country'] = $country_info['name'];
			$_SESSION[$module_data . '_shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
			$_SESSION[$module_data . '_shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
			$_SESSION[$module_data . '_shipping_address']['address_format'] = $country_info['address_format'];
		}
		else
		{
			$_SESSION[$module_data . '_shipping_address']['country'] = '';
			$_SESSION[$module_data . '_shipping_address']['iso_code_2'] = '';
			$_SESSION[$module_data . '_shipping_address']['iso_code_3'] = '';
			$_SESSION[$module_data . '_shipping_address']['address_format'] = '';
		}
 
		$zone_info = getZoneName( $data['zone_id'] );

		if( $zone_info )
		{
			$_SESSION[$module_data . '_shipping_address']['zone'] = $zone_info['name'];
			$_SESSION[$module_data . '_shipping_address']['zone_code'] = $zone_info['code'];
		}
		else
		{
			$_SESSION[$module_data . '_shipping_address']['zone'] = '';
			$_SESSION[$module_data . '_shipping_address']['zone_code'] = '';
		}

		if( isset( $data['custom_field'] ) )
		{
			$_SESSION[$module_data . '_shipping_address']['custom_field'] = $data['custom_field']['address'];
		}
		else
		{
			$_SESSION[$module_data . '_shipping_address']['custom_field'] = array();
		}

		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( isset( $_SESSION[$module_data . '_shipping_address']['first_name'] ) )
{
	$data['first_name'] = $_SESSION[$module_data . '_shipping_address']['first_name'];
}
else
{
	$data['first_name'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['last_name'] ) )
{
	$data['last_name'] = $_SESSION[$module_data . '_shipping_address']['last_name'];
}
else
{
	$data['last_name'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['company'] ) )
{
	$data['company'] = $_SESSION[$module_data . '_shipping_address']['company'];
}
else
{
	$data['company'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['address_1'] ) )
{
	$data['address_1'] = $_SESSION[$module_data . '_shipping_address']['address_1'];
}
else
{
	$data['address_1'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['address_2'] ) )
{
	$data['address_2'] = $_SESSION[$module_data . '_shipping_address']['address_2'];
}
else
{
	$data['address_2'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['postcode'] ) )
{
	$data['postcode'] = $_SESSION[$module_data . '_shipping_address']['postcode'];
}
else
{
	$data['postcode'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['city'] ) )
{
	$data['city'] = $_SESSION[$module_data . '_shipping_address']['city'];
}
else
{
	$data['city'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['country_id'] ) )
{
	$data['country_id'] = $_SESSION[$module_data . '_shipping_address']['country_id'];
}
else
{
	$data['country_id'] = $ProductGeneral->config['config_country_id'];
}

if( isset( $_SESSION[$module_data . '_shipping_address']['zone_id'] ) )
{
	$data['zone_id'] = $_SESSION[$module_data . '_shipping_address']['zone_id'];
}
else
{
	$data['zone_id'] = '';
}
 
$data['countries'] = getCountry();
$data['information'] = getInformation(); 
 
$contents = checkout_guest_shipping( $data, $lang_ext );

echo $contents;
exit();
