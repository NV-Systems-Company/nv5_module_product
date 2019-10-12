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
 
$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );

if( $method = nv_strtolower( $nv_Request->get_string( 'method', 'get,post', '' ) ) )
{
	if( is_file( NV_ROOTDIR . '/modules/' . $module_file . '/checkout/'. $method .'.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/'. $method .'.php';
	}
	
}
elseif( isset( $array_op[1] ) && $array_op[1] == 'success')
{
	if( is_file( NV_ROOTDIR . '/modules/' . $module_file . '/checkout/'. $array_op[1] .'.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/checkout/'. $array_op[1] .'.php';
	}
}
 
 
$base_url_rewrite = nv_url_rewrite(  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
	Header( 'Location: ' . $base_url_rewrite );
	die();
}

$page_title = $lang_module['cart_title'];

$dataContent = array();

$ProductCart = new NukeViet\Product\Cart( $productRegistry );
$ProductWeight = new NukeViet\Product\Weight( $productRegistry );


 

 
if( ( ! $ProductCart->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductCart->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) ) 
{
 
	Header( 'Location: ' . nv_url_rewrite(  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true ));
	die();

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
		Header( 'Location: ' . nv_url_rewrite(  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true ));
		die();
	}
}
 

 
$dataContent['text_checkout_option'] = sprintf($lang_ext['text_checkout_option'], 1);
$dataContent['text_checkout_account'] = sprintf($lang_ext['text_checkout_account'], 2);
$dataContent['text_checkout_payment_address'] = sprintf($lang_ext['text_checkout_payment_address'], 2);
$dataContent['text_checkout_shipping_address'] = sprintf($lang_ext['text_checkout_shipping_address'], 3);
$dataContent['text_checkout_shipping_method'] = sprintf($lang_ext['text_checkout_shipping_method'], 4); 
 
if( $ProductCart->hasShipping() )
{
	$dataContent['text_checkout_payment_method'] = sprintf( $lang_ext['text_checkout_payment_method'], 5 );
	$dataContent['text_checkout_confirm'] = sprintf( $lang_ext['text_checkout_confirm'], 6 );
}
else
{
	$dataContent['text_checkout_payment_method'] = sprintf( $lang_ext['text_checkout_payment_method'], 3 );
	$dataContent['text_checkout_confirm'] = sprintf( $lang_ext['text_checkout_confirm'], 4 );
}

if( isset( $_SESSION[$module_data . '_error'] ) )
{
	$dataContent['error_warning'] = $_SESSION[$module_data . '_error'];
	unset( $_SESSION[$module_data . '_error'] );
}
else
{
	$dataContent['error_warning'] = '';
}

 
if( isset( $_SESSION[$module_data . '_account'] ) )
{
	$dataContent['account'] = $_SESSION[$module_data . '_account'];
}
else
{
	$dataContent['account'] = '';
}

 

$dataContent['shipping_required'] = $ProductCart->hasShipping();
 



$contents = ThemeProductViewCheckout( $dataContent );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';


