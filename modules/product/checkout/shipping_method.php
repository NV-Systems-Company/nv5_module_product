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
	
	$dataContent['comment'] = $nv_Request->get_title( 'comment', 'post', '', 1 );
	$dataContent['shipping_method'] = $nv_Request->get_title( 'shipping_method', 'post', '', 1 );
	
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	
	// Validate if shipping is required. If not the customer should not have reached this page.
	if( ! $ProductCart->hasShipping() )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );

	}

	// Validate if shipping address has been set.
	if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
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

	if( $dataContent['shipping_method'] == '' )
	{
		$json['error']['warning'] = $lang_ext['error_shipping'];
	}
	else
	{
		$shipping = explode( '.', $dataContent['shipping_method'] );
		
		if( ! isset( $shipping[0] ) || ! isset( $shipping[1] ) || ! isset( $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]] ) )
		{
			$json['error']['warning'] = $lang_ext['error_shipping'];
		}
	}

	if( ! $json )
	{
		$_SESSION[$module_data . '_shipping_method'] = $_SESSION[$module_data . '_shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

		$_SESSION[$module_data . '_comment'] = strip_tags( $dataContent['comment'] );
	}

	nv_jsonOutput( $json );
	
}

$dataContent = array();

if( isset( $_SESSION[$module_data . '_shipping_address'] ) )
{
	// Shipping Methods
	$method_data = array();
	$array_class = array();
	$results = $db->query( 'SELECT code FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'shipping' ) )->fetchAll();

	foreach( $results as $result )
	{
		$shipping_config = $ProductGeneral->getSetting( 'shipping_' . $result['code'], $ProductGeneral->store_id );
		if( isset( $shipping_config['shipping_' . $result['code'] . '_status'] ) && $shipping_config['shipping_' . $result['code'] . '_status'] )
		{
			$array_class[] = $result['code'];

		}
		
	}

	foreach( $array_class as &$class )
	{

		$classMap = 'NukeViet\Product\\' . $class;
		${$class} = new $classMap( $productRegistry );
 		
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
	$dataContent['error_warning'] = sprintf( $lang_ext['error_no_shipping'], '#information/contact' );
}
else
{
	$dataContent['error_warning'] = '';
}

if( isset( $_SESSION[$module_data . '_shipping_methods'] ) )
{
	$dataContent['shipping_methods'] = $_SESSION[$module_data . '_shipping_methods'];
}
else
{
	$dataContent['shipping_methods'] = array();
}
 
if( isset( $_SESSION[$module_data . '_shipping_method']['code'] ) )
{
	$dataContent['code'] = $_SESSION[$module_data . '_shipping_method']['code'];
}
else
{
	$dataContent['code'] = '';
}

if( isset( $_SESSION[$module_data . '_comment'] ) )
{
	$dataContent['comment'] = $_SESSION[$module_data . '_comment'];
}
else
{
	$dataContent['comment'] = '';
}

 
$xtpl = new XTemplate( 'ThemeCheckoutShippingMethod.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

if( $dataContent['shipping_methods'] )
{
	foreach($dataContent['shipping_methods'] as $shipping_methods )
	{
		$xtpl->assign( 'SHIPPING', $shipping_methods );
		if( ! $shipping_methods['error'] )
		{
			foreach( $shipping_methods['quote'] as $key =>  $quote )
			{
			
				$quote['checked'] = ( ( $dataContent['code'] == $quote['code'] ) || ($key == 0) ) ? 'checked="checked"' : '';
				$xtpl->assign( 'QUOTE', $quote );
				$xtpl->parse( 'main.shipping.quote' );
			}
		}
		else
		{
			$xtpl->parse( 'main.shipping.error' );
		}
		$xtpl->parse( 'main.shipping' );
	}	
	
}
if( $dataContent['error_warning'] )
{
	 
	$xtpl->assign( 'ERROR_WARNING', $dataContent['error_warning']);
	$xtpl->parse( 'main.error_warning' );
	 
}


$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
  