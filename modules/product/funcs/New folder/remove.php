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

if( ! isset( $_SESSION[$module_data . '_cart'] ) ) $_SESSION[$module_data . '_cart'] = array();

$key = $nv_Request->get_string( 'key', 'post,get', 0 );

unset( $_SESSION[$module_data . '_cart'][$key] );

if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

$json = array();

$total = 0;

$getProducts = $ProductContent->getProducts();
if( !empty( $ProductContent ) )
{
	if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

	foreach( $getProducts as $product )
	{
		

		if( ( $ProductGeneral->config['config_customer_price'] && ! empty( $user_info ) ) || ! $ProductGeneral->config['config_customer_price'] )
		{
			$total += $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_config_tax'] ) * $product['quantity'];
		}
		else
		{
			$price = false;
		}

	}
	//$ProductTax->clear();
}
 
$countProducts = $ProductContent->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 );
 
$total = $ProductCurrency->format( $total );

$json['total'] = sprintf( $lang_module['cart_items'], $countProducts, $total );
 
header( 'Content-Type: application/json' );
echo json_encode( $json );
exit();
