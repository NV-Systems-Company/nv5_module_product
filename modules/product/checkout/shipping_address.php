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

if( $nv_Request->isset_request( 'save', 'get,post' ) )
{
	$json = array();

	// Validate if customer is logged in.
	if( ! $globalUserid )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );

	}

	$ProductCart = new NukeViet\Product\Cart( $productRegistry );

	// Validate if shipping is required. If not the customer should not have reached this page.
	if( ! $ProductCart->hasShipping() )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
	}

	// Validate cart has products and has stock.
	if( ( ! $ProductCart->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductCart->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );

	}

	// Validate minimum quantity requirements.
	$getProducts = $ProductCart->getProducts();

	foreach( $getProducts as $product )
	{
		$product_total = 0;

		foreach( $getProducts as $product_2 )
		{
			if( $product_2['product_id'] == $product['product_id'] )
			{
				$product_total += $product_2['quantity'];
			}
		}

		if( $product['minimum'] > $product_total )
		{
			$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );

			break;
		}
	}

	if( ! $json )
	{
		
		$dataContent['first_name'] = $nv_Request->get_title( 'firstname', 'post', '', 1 );
		$dataContent['last_name'] = $nv_Request->get_title( 'lastname', 'post', '', 1 );
		$dataContent['company'] = $nv_Request->get_title( 'company', 'post', '', 1 );
		$dataContent['address_1'] = $nv_Request->get_title( 'address_1', 'post', '', 1 );
		$dataContent['address_2'] = $nv_Request->get_title( 'address_2', 'post', '', 1 );
		$dataContent['city'] = $nv_Request->get_title( 'city', 'post', '', 1 );
		$dataContent['postcode'] = $nv_Request->get_title( 'postcode', 'post', '', 1 );
		$dataContent['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
		$dataContent['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
		$dataContent['address_id'] = $nv_Request->get_int( 'address_id', 'post', 0 );
		$dataContent['shipping_address'] = $nv_Request->get_string( 'shipping_address', 'post', '' );
		

		if( $dataContent['shipping_address'] == 'existing' )
		{

			if( $dataContent['address_id'] == 0 )
			{
				$json['error']['warning'] = $lang_ext['error_address'];
			}
			elseif( ! in_array( $dataContent['address_id'], array_keys( getAddresses( $globalUserid ) ) ) )
			{
				$json['error']['warning'] = $lang_ext['error_address'];
			}

			if( ! $json )
			{
				$_SESSION[$module_data . '_shipping_address'] = getAddress( $globalUserid, $dataContent['address_id'] );

				unset( $_SESSION[$module_data . '_shipping_method'] );
				unset( $_SESSION[$module_data . '_shipping_methods'] );
			}
		}
		else
		{
			if( ( nv_strlen( trim( $dataContent['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $dataContent['first_name'] ) ) > 32 ) )
			{
				$json['error']['first_name'] = $lang_ext['error_firstname'];
			}

			if( ( nv_strlen( trim( $dataContent['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $dataContent['last_name'] ) ) > 32 ) )
			{
				$json['error']['last_name'] = $lang_ext['error_lastname'];
			}

			if( ( nv_strlen( trim( $dataContent['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $dataContent['address_1'] ) ) > 128 ) )
			{
				$json['error']['address_1'] = $lang_ext['error_address_1'];
			}

			if( ( nv_strlen( trim( $dataContent['city'] ) ) < 2 ) || ( nv_strlen( trim( $dataContent['city'] ) ) > 128 ) )
			{
				$json['error']['city'] = $lang_ext['error_city'];
			}

			$country_info = getCountry( $dataContent['country_id'] );

			if( $country_info && $country_info['postcode_required'] && ( nv_strlen( trim( $dataContent['postcode'] ) ) < 2 || nv_strlen( trim( $dataContent['postcode'] ) ) > 10 ) )
			{
				$json['error']['postcode'] = $lang_ext['error_postcode'];
			}

			if( $dataContent['country_id'] == 0 )
			{
				$json['error']['country'] = $lang_ext['error_country'];
			}

			if( $dataContent['zone_id'] == 0 )
			{
				$json['error']['zone'] = $lang_ext['error_zone'];
			}

			// $custom_fields = getCustomFields( $ProductGeneral->config['config_customer_group_id'] );

			// foreach( $custom_fields as $custom_field )
			// {
			// if( $custom_field['location'] == 'address' )
			// {
			// if( $custom_field['required'] && empty( $nv_Request->get_string('custom_field', 'post', '') [$custom_field['location']][$custom_field['custom_field_id']] ) )
			// {
			// $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf( $lang_ext['error_custom_field' ), $custom_field['name'] );
			// }
			// elseif( ( $custom_field['type'] == 'text' ) && ! empty( $custom_field['validation'] ) && ! filter_var( $nv_Request->get_string( 'custom_field', 'post',][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array( 'options' => array( 'regexp' => $custom_field['validation'] ) ) ) )
			// {
			// $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf( $lang_ext['error_custom_field' ), $custom_field['name'] );
			// }
			// }
			// }

			if( ! $json )
			{
				$address_id = addAddress( $user_info['userid'], $dataContent );

				$_SESSION[$module_data . '_shipping_address'] = getAddress( $globalUserid, $address_id );

				// If no default address ID set we use the last address
				if( ! empty($user_info['address_id']) )
				{

					editAddressId( $user_info['userid'], $address_id );
				}

				unset( $_SESSION[$module_data . '_shipping_method'] );
				unset( $_SESSION[$module_data . '_shipping_methods'] );
			}
		}
	}

	nv_jsonOutput( $json );
}

if( isset( $_SESSION[$module_data . '_shipping_address']['address_id'] ) )
{
	$dataContent['address_id'] = $_SESSION[$module_data . '_shipping_address']['address_id'];
}
else
{
	$dataContent['address_id'] = $user_info['address_id'];
}

$dataContent['addresses'] = getAddresses( $globalUserid , $dataContent['address_id']);
print_r($dataContent['addresses']); 
if( isset( $_SESSION[$module_data . '_shipping_address']['postcode'] ) )
{
	$dataContent['postcode'] = $_SESSION[$module_data . '_shipping_address']['postcode'];
}
else
{
	$dataContent['postcode'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_address']['country_id'] ) )
{
	$dataContent['country_id'] = $_SESSION[$module_data . '_shipping_address']['country_id'];
}
else
{
	$dataContent['country_id'] = $ProductGeneral->config['config_country_id'];
}

if( isset( $_SESSION[$module_data . '_shipping_address']['zone_id'] ) )
{
	$dataContent['zone_id'] = $_SESSION[$module_data . '_shipping_address']['zone_id'];
}
else
{
	$dataContent['zone_id'] = '';
}

$dataContent['countries'] = getCountries();

// Custom Fields
$dataContent['custom_fields'] = array();

// $custom_fields = getCustomFields( $ProductGeneral->config['config_customer_group_id'] );

// foreach( $custom_fields as $custom_field )
// {
// if( $custom_field['location'] == 'address' )
// {
// $dataContent['custom_fields'][] = $custom_field;
// }
// }

if( isset( $_SESSION[$module_data . '_shipping_address']['custom_field'] ) )
{
	$dataContent['shipping_address_custom_field'] = $_SESSION[$module_data . '_shipping_address']['custom_field'];
}
else
{
	$dataContent['shipping_address_custom_field'] = array();
}

$xtpl = new XTemplate( 'ThemeCheckoutShippingAddress.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

if( $dataContent['addresses'] )
{
	foreach( $dataContent['addresses'] as $address )
	{
		$address['selected'] = ( $address['address_id'] == $user_info['address_id'] ) ? 'selected="selected"': '';
		$xtpl->assign( 'ADDRESS', $address );
		$xtpl->parse( 'main.address' );
	}
	
}
  
$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
