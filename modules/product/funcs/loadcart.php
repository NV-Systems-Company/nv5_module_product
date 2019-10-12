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

$ProductContent = new NukeViet\Product\Product( $productRegistry );
$ProductCart = new NukeViet\Product\Cart( $productRegistry );

$xtotals = array();
$taxes = $ProductCart->getTaxes();
$total = 0;

// Because __call can not keep var references so we put them into an array.
$total_data = array(
	'xtotals' => &$xtotals,
	'taxes' => &$taxes,
	'total' => &$total );

// Display prices
if( defined( 'NV_IS_USER' ) || ! $ProductGeneral->config['config_customer_price'] )
{
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
		
		if( isset( $total_config[$result['code'] . '_status'] ) && $total_config[$result['code'] . '_status'] == 1 )
		{
			//if( $result['code'] == 'sub_total' ) 
				$array_class[] = $result['code'];

		}
	}

	foreach( $array_class as $key => &$class )
	{

		$classMap = 'NukeViet\Product\total\\' . $class;
		${$class} = new $classMap( $productRegistry );
		${$class}->getTotal( $total_data );

	}

	$sort_order = array();

	foreach( $xtotals as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $xtotals );
}

$data['text_items'] = sprintf( $lang_module['checkout_text_items'], $ProductCart->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total, $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );

$data['products'] = array();

foreach( $ProductCart->getProducts() as $product )
{

	if( $product['thumb'] == 1 )
	{
		$product['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $product['image'];
	}
	elseif( $product['thumb'] == 2 )
	{
		$product['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $product['image'];
	}
	elseif( $product['thumb'] == 3 )
	{
		$product['thumb'] = $product['image'];
	}
	else
	{
		$product['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
	}
	$option_data = array();

	foreach( $product['option'] as $option )
	{
		if( $option['type'] != 'file' )
		{
			$value = $option['value'];
		}
		else
		{
			$upload_info = array();

			if( $upload_info )
			{
				$value = $upload_info['name'];
			}
			else
			{
				$value = '';
			}
		}

		$option_data[] = array(
			'name' => $option['name'],
			'value' => ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ),
			'type' => $option['type'] );
	}

	// Display prices
	if( defined( 'NV_IS_USER' ) || ! $ProductGeneral->config['config_customer_price'] )
	{
		$unit_price = $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] );

		$price = $ProductCurrency->format( $unit_price, $nv_Request->get_string( $module_data . '_currency', 'session' ) );
		$total = $ProductCurrency->format( $unit_price * $product['quantity'], $nv_Request->get_string( $module_data . '_currency', 'session' ) );
	}
	else
	{
		$price = false;
		$total = false;
	}

	$data['products'][] = array(
		'cart_id' => $product['cart_id'],
		'thumb' => $product['thumb'],
		'name' => $product['name'],
		'model' => $product['model'],
		'option' => $option_data,
		'recurring' => ( $product['recurring'] ? $product['recurring']['name'] : '' ),
		'quantity' => $product['quantity'],
		'price' => $price,
		'total' => $total,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'] );
}

// Gift Voucher
$data['vouchers'] = array();

if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
{
	foreach( $_SESSION[$module_data . '_vouchers'] as $key => $voucher )
	{
		$data['vouchers'][] = array(
			'key' => $key,
			'description' => $voucher['description'],
			'amount' => $ProductCurrency->format( $voucher['amount'], $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );
	}
}

$data['xtotals'] = array();

foreach( $xtotals as $total )
{
	$data['xtotals'][] = array(
		'title' => $total['title'],
		'text' => $ProductCurrency->format( $total['value'], $nv_Request->get_string( $module_data . '_currency', 'session' ) ),
		);
}

$xtpl = new XTemplate( 'BlockCart.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'LINK_CART', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true ) );
$xtpl->assign( 'LINK_CHECKOUT', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true ) );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );

$xtpl->assign( 'TEXT_ITEMS', $data['text_items'] );

if( $data['products'] || $data['vouchers'] )
{
	if( ! empty( $data['products'] ) )
	{
		foreach( $data['products'] as $product )
		{
			$xtpl->assign( 'PRODUCT', $product );

			if( ! empty( $product['thumb'] ) )
			{
				$xtpl->parse( 'loadcart.data.product.thumb' );

			}
			if( $product['option'] )
			{
				foreach( $product['option'] as $option )
				{
					$xtpl->assign( 'OPTION', $option );
					$xtpl->parse( 'loadcart.data.product.option' );
				}
			}

			$xtpl->parse( 'loadcart.data.product' );
		}
		foreach( $data['vouchers'] as $vouchers )
		{

			$xtpl->assign( 'VOUCHERS', $vouchers );
			$xtpl->parse( 'loadcart.data.vouchers' );
		}

	}

	if( $data['xtotals'] )
	{
		foreach( $data['xtotals'] as $_total )
		{

			$xtpl->assign( 'TOTAL', $_total );
			$xtpl->parse( 'loadcart.data.total' );
		}

	}

	$xtpl->parse( 'loadcart.data' );

}
else
{
	$xtpl->parse( 'loadcart.empty' );
}

$xtpl->parse( 'loadcart' );
$content = $xtpl->text( 'loadcart' );

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
