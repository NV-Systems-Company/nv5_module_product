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

$json = array();

if( ! isset( $_SESSION[$module_data . '_cart'] ) ) $_SESSION[$module_data . '_cart'] = array();

$product_id = $nv_Request->get_int( 'product_id', 'post,get', 0 );
$quantity = $nv_Request->get_int( 'quantity', 'post,get', 0 );
$option = $nv_Request->get_array( 'option', 'post', array() );



$ProductContent = new NukeViet\Product\Product( $productRegistry );
$product_info = $ProductContent->getProduct( $product_id );

if( $product_info )
{
	$quantity = ! empty( $quantity ) ? $quantity : 1;

	$option = array_filter( $option );

	$product_options = $ProductContent->getProductOptions( $product_id );

	foreach( $product_options as $product_option )
	{
		if( $product_option['required'] && empty( $option[$product_option['product_option_id']] ) )
		{
			$json['error']['option'][$product_option['product_option_id']] = sprintf( $lang_module['cart_error_required'], $product_option['name'] );
		}
	}
	$link_product = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product_info['category_id']]['alias'] . '/' . $product_info['alias'] . $global_config['rewrite_exturl'], true );

	if( ! $json )
	{
		$product['product_id'] = ( int )$product_id;

		if( $option )
		{
			$product['option'] = $option;
		}

		$key = base64_encode( serialize( $product ) );

		if( ( int )$quantity && ( ( int )$quantity > 0 ) )
		{
			if( ! isset( $_SESSION[$module_data . '_cart'][$key] ) )
			{
				$_SESSION[$module_data . '_cart'][$key] = ( int )$quantity;
			}
			else
			{
				$_SESSION[$module_data . '_cart'][$key] += ( int )$quantity;
			}
		}

		$link_cart = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );

		$json['success'] = sprintf( $lang_module['cart_success'], $link_product, $product_info['name'], $link_cart );

		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
		unset( $_SESSION[$module_data . '_payment_method'] );
		unset( $_SESSION[$module_data . '_payment_methods'] );

		$total_data = array();
		$total = 0;
		$taxes = array();
		
		
		
		if( ( $ProductGeneral->config['config_customer_price'] && defined( 'NV_IS_USER' ) ) || ! $ProductGeneral->config['config_customer_price'] )
		{

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

			$json['total'] = sprintf( $lang_module['checkout_text_items'], $ProductContent->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total ) );

		}

		$json['total'] = sprintf( $lang_module['cart_items'], $ProductContent->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total ) );

	}
	else
	{

		$json['redirect'] = nv_url_rewrite( $link_product, true );
	}

}
else
{
	$json['error']['product'] = $lang_module['product_not_exist'];
}

header( 'Content-Type: application/json' );
echo json_encode( $json );
exit();
