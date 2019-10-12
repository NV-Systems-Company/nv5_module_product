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
 
if( $nv_Request->isset_request( 'save', 'get,post' ) )
{ 
	$json = array();

	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

	// if( empty( $user_info ) )
	// {
		// $json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	// }

 
	if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	}

	if( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
	}

	if( ! $json )
	{
		$shipping_method = $nv_Request->get_string( 'shipping_method', 'post', '' );
		$comment = $nv_Request->get_string( 'comment', 'post', '' );
 
		if(  empty( $shipping_method ) )
		{
			$json['error']['warning'] = $lang_module['checkout_error_shipping'];
		}
		else
		{
			$shipping = explode( '.', $shipping_method );

			if( ! isset( $shipping[0] ) || ! isset( $shipping[1] ) || ! isset( $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]] ) )
			{
				$json['error']['warning'] = $lang_module['checkout_error_shipping'];
			}
			
		}

		if( ! $json )
		{
			$_SESSION[$module_data . '_shipping_method'] = $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$_SESSION[$module_data . '_comment'] = strip_tags( $comment );
		}
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

$data = array();

if( isset( $_SESSION[$module_data . '_shipping_address'] ) )
{
	// Shipping Methods
	$method_data = array();
	$array_class = array();
	$results = $db->query( 'SELECT code FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'shipping' ) )->fetchAll();
	
	foreach( $results as $result )
	{
		$shipping_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );
		if( isset( $shipping_config[$result['code'] . '_status'] ) && $shipping_config[$result['code'] . '_status'] )
		{
			$array_class[] = $result['code'];

		}
	}
	foreach( $array_class as &$class )
	{
		${$class} = new $class( $productRegistry );
		$quote = ${$class}->getQuote( $_SESSION[$module_data . '_shipping_address'] );

		if( $quote )
		{
			$method_data[$quote['code']] = array(
				'title' => $quote['title'],
				'quote' => $quote['quote'],
				'sort_order' => $quote['sort_order'],
				'error' => $quote['error'] );
		}
	}

	$sort_order = array();

	foreach( $method_data as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $method_data );

	$_SESSION[$module_data . '_shipping_methods'] = $method_data;
}

if( empty( $_SESSION[$module_data . '_shipping_methods'] ) )
{
	$data['error_warning'] = sprintf( $lang_module['checkout_error_no_shipping'], '/contact' );
}
else
{
	$data['error_warning'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_methods'] ) )
{
	$data['shipping_methods'] = $_SESSION[$module_data . '_shipping_methods'];
}
else
{
	$data['shipping_methods'] = array();
}

if( isset( $_SESSION[$module_data . '_shipping_method']['code'] ) )
{
	$data['code'] = $_SESSION[$module_data . '_shipping_method']['code'];
}
else
{
	$data['code'] = '';
}

if( isset( $_SESSION[$module_data . '_comment'] ) )
{
	$data['comment'] = $_SESSION[$module_data . '_comment'];
}
else
{
	$data['comment'] = '';
}
 
$xtpl = new XTemplate( 'ThemeCheckoutShippingMethod.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
 
exit();
