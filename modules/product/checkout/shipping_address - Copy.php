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
 
$getCountry = getCountry();
 
if( $nv_Request->isset_request( 'save', 'get,post' ) )
{
	$json = array();

	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry )  ;

	if( empty( $user_info ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	}

	if( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
	}

	if( ! $json )
	{
		 
		if( $nv_Request->get_title( 'shipping_address', 'post', '', 1 ) == 'existing' )
		{

			$address_id = $nv_Request->get_int( 'address_id', 'post', 0 );
			if( empty( $address_id ) )
			{
				$json['error']['warning'] = $lang_ext['error_address'];
			}
			elseif( ! in_array( $nv_Request->get_int( 'address_id', 'post', 0 ), array_keys( getAddresses() ) ) )
			{
				$json['error']['warning'] = $lang_ext['error_address'];
			}

			if( ! $json )
			{
				// Default Shipping Address

				$_SESSION[$module_data . '_shipping_address'] = getAddress( $address_id );
 
				unset( $_SESSION[$module_data . '_shipping_method'] );
				unset( $_SESSION[$module_data . '_shipping_methods'] );
			}
		}
		else
		{
			$data['address_1'] = $nv_Request->get_title( 'address_1', 'post', '', 1 );
			$data['address_2'] = $nv_Request->get_title( 'address_2', 'post', '', 1 );
			$data['city'] = $nv_Request->get_title( 'city', 'post', '', 1 );
			$data['company'] = $nv_Request->get_title( 'company', 'post', '', 1 );
			$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
			$data['first_name'] = $nv_Request->get_title( 'first_name', 'post', '', 1 );
			$data['last_name'] = $nv_Request->get_title( 'last_name', 'post', '', 1 );

			if( ( strlen( trim( $data['first_name'] ) ) < 1 ) || ( strlen( trim( $data['first_name'] ) ) > 32 ) )
			{
				$json['error']['first_name'] = $lang_ext['error_first_name'];
			}

			if( ( strlen( trim( $data['last_name'] ) ) < 1 ) || ( strlen( trim( $data['last_name'] ) ) > 32 ) )
			{
				$json['error']['last_name'] = $lang_ext['error_last_name'];
			}

			if( ( strlen( trim( $data['address_1'] ) ) < 3 ) || ( strlen( trim( $data['address_1'] ) ) > 128 ) )
			{
				$json['error']['address_1'] = $lang_ext['error_address_1'];
			}

			if( ( strlen( $data['city'] ) < 2 ) || ( strlen( $data['city'] ) > 32 ) )
			{
				$json['error']['city'] = $lang_ext['error_city'];
			}
 
			$country_info = $getCountry[$data['country_id']];

			if( $country_info && $country_info['postcode_required'] && ( strlen( trim( $data['postcode'] ) ) < 2 || strlen( trim( $data['postcode'] ) ) > 10 ) )
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

			// Custom field validation chua lam
			//$custom_fields = array();

			// foreach( $custom_fields as $custom_field )
			// {
			// if( ( $custom_field['location'] == 'address' ) && $custom_field['required'] && empty( $_SESSION[$module_data . '_custom_field'][$custom_field['custom_field_id']] ) )
			// {
			// $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf( $lang_ext['error_custom_field'], $custom_field['name'] );
			// }
			// }

			if( ! $json )
			{

				$address_id = addAddress( $data );

				$_SESSION[$module_data . '_shipping_address'] = getAddress( $address_id );

				unset( $_SESSION[$module_data . '_payment_method'] );
				unset( $_SESSION[$module_data . '_payment_methods'] );

			}
		}
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

$data = array();

if( isset( $_SESSION[$module_data . '_shipping_address']['address_id'] ) )
{
	$data['address_id'] = $_SESSION[$module_data . '_shipping_address']['address_id'];
}
else
{
	$data['address_id'] = $user_info['address_id'];
}
 
$data['addresses'] = getAddresses();

if( isset( $_SESSION[$module_data . '_shipping_address']['postcode'] ) )
{
	$data['postcode'] = $_SESSION[$module_data . '_shipping_address']['postcode'];
}
else
{
	$data['postcode'] = '';
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

$data['countries'] = $getCountry;

// Custom Fields chưa xây dựng
$data['custom_fields'] = array();

if( isset( $_SESSION[$module_data . '_shipping_address']['custom_field'] ) )
{
	$data['shipping_address_custom_field'] = $_SESSION[$module_data . '_shipping_address']['custom_field'];
}
else
{
	$data['shipping_address_custom_field'] = array();
}

 
$xtpl = new XTemplate( 'ThemeCheckoutShippingAddress.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
 