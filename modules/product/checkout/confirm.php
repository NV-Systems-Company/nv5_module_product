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


if( $payment_code = $nv_Request->get_string( 'payment', 'get,post', '' ) )
{ 
	$json = array();
	if( is_file( NV_ROOTDIR . '/modules/' . $module_file . '/user_extension/payment/' . $payment_code . '.php' ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/user_extension/payment/' . $payment_code . '.php';
		
		if( nv_function_exists( 'confirm_' . $payment_code ) )
		{
			$json = call_user_func( 'confirm_' . $payment_code );
		}			
		
	}

	nv_jsonOutput( $json );
}

$ProductCart = new NukeViet\Product\Cart( $productRegistry );
// $ProductCurrency = new NukeViet\Product\Currency( $productRegistry );//Da duoc goi tai file functions.php
// $ProductTax = new NukeViet\Product\Tax( $productRegistry ); //Da duoc goi tai file functions.php

$dataContent = array();

$dataContent['redirect'] = '';
$dataContent['payment'] = '';
if( $ProductCart->hasShipping() )
{
	// Validate if shipping address has been set.
	if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
	{
		$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
	}

	// Validate if shipping method has been set.
	if( ! isset( $_SESSION[$module_data . '_shipping_method'] ) )
	{
		$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
	}
}
else
{
	unset( $_SESSION[$module_data . '_shipping_address'] );
	unset( $_SESSION[$module_data . '_shipping_method'] );
	unset( $_SESSION[$module_data . '_shipping_methods'] );
}

// Validate if payment address has been set.
if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
{
	$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
}

// Validate if payment method has been set.
if( ! isset( $_SESSION[$module_data . '_payment_method'] ) )
{
	$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout', true );
}

// Validate cart has products and has stock.
if( ( ! $ProductCart->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductCart->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
{
	$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );
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
		$dataContent['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );

		break;
	}
}

if( ! $dataContent['redirect'] )
{
	$order_data = array();

	$xtotals = array();
	$taxes = $ProductCart->getTaxes();
	$total = 0;

	// Because __call can not keep var references so we put them into an array.
	$total_data = array(
		'xtotals' => &$xtotals,
		'taxes' => &$taxes,
		'total' => &$total );

	 
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

		$classMap = 'NukeViet\Product\Total\\' . $class;
		${$class} = new $classMap( $productRegistry );
		${$class}->getTotal( $total_data );

	}

	$sort_order = array();

	foreach( $xtotals as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $xtotals );

	$order_data['totals'] = $xtotals;
	
	$result = $db->query("SHOW TABLE STATUS WHERE Name='" . TABLE_PRODUCT_NAME . "_order'");
	$item = $result->fetch();
	$result->closeCursor();
 
	$order_data['invoice_prefix'] = vsprintf( $ProductGeneral->config['config_format_order_id'], $item['auto_increment']);
	$order_data['store_id'] =  $ProductGeneral->config['config_store_id'];
	$order_data['store_name'] =  $ProductGeneral->config['config_name'];

	if( $order_data['store_id'] )
	{
		$order_data['store_url'] =  $ProductGeneral->config['config_url'];
	}
	else
	{
		$order_data['store_url'] = '#url';
	}


	if( $globalUserid > 0 )
	{

		$order_data['userid'] = $globalUserid;
		$order_data['customer_group_id'] = $user_info['customer_group_id'];
		$order_data['first_name'] = $user_info['first_name'];
		$order_data['last_name'] = $user_info['last_name'];
		$order_data['email'] = $user_info['email'];
		$order_data['telephone'] = $user_info['telephone'];
		//$order_data['custom_field'] = json_decode( $user_info['custom_field'], true );
	}
	elseif( isset( $_SESSION[$module_data . '_guest'] ) )
	{
		$order_data['userid'] = 0;
		$order_data['customer_group_id'] = $_SESSION[$module_data . '_guest']['customer_group_id'];
		$order_data['first_name'] = $_SESSION[$module_data . '_guest']['first_name'];
		$order_data['last_name'] = $_SESSION[$module_data . '_guest']['last_name'];
		$order_data['email'] = $_SESSION[$module_data . '_guest']['email'];
		$order_data['telephone'] = $_SESSION[$module_data . '_guest']['telephone'];
		$order_data['custom_field'] = $_SESSION[$module_data . '_guest']['custom_field'];
	}

	$order_data['payment_first_name'] = $_SESSION[$module_data . '_payment_address']['first_name'];
	$order_data['payment_last_name'] = $_SESSION[$module_data . '_payment_address']['last_name'];
	$order_data['payment_company'] = $_SESSION[$module_data . '_payment_address']['company'];
	$order_data['payment_address_1'] = $_SESSION[$module_data . '_payment_address']['address_1'];
	$order_data['payment_address_2'] = $_SESSION[$module_data . '_payment_address']['address_2'];
	$order_data['payment_city'] = $_SESSION[$module_data . '_payment_address']['city'];
	$order_data['payment_postcode'] = $_SESSION[$module_data . '_payment_address']['postcode'];
	$order_data['payment_zone'] = $_SESSION[$module_data . '_payment_address']['zone'];
	$order_data['payment_zone_id'] = $_SESSION[$module_data . '_payment_address']['zone_id'];
	$order_data['payment_country'] = $_SESSION[$module_data . '_payment_address']['country'];
	$order_data['payment_country_id'] = $_SESSION[$module_data . '_payment_address']['country_id'];
	$order_data['payment_address_format'] = $_SESSION[$module_data . '_payment_address']['address_format'];
	$order_data['payment_custom_field'] = ( isset( $_SESSION[$module_data . '_payment_address']['custom_field'] ) ? $_SESSION[$module_data . '_payment_address']['custom_field'] : array() );
 
	if( isset( $_SESSION[$module_data . '_payment_method']['title'] ) )
	{
		$order_data['payment_method'] = $_SESSION[$module_data . '_payment_method']['title'];
	}
	else
	{
		$order_data['payment_method'] = '';
	}

	if( isset( $_SESSION[$module_data . '_payment_method']['code'] ) )
	{
		$order_data['payment_code'] = $_SESSION[$module_data . '_payment_method']['code'];
	}
	else
	{
		$order_data['payment_code'] = '';
	}

	if( $ProductCart->hasShipping() )
	{
		$order_data['shipping_first_name'] = $_SESSION[$module_data . '_shipping_address']['first_name'];
		$order_data['shipping_last_name'] = $_SESSION[$module_data . '_shipping_address']['last_name'];
		$order_data['shipping_company'] = $_SESSION[$module_data . '_shipping_address']['company'];
		$order_data['shipping_address_1'] = $_SESSION[$module_data . '_shipping_address']['address_1'];
		$order_data['shipping_address_2'] = $_SESSION[$module_data . '_shipping_address']['address_2'];
		$order_data['shipping_city'] = $_SESSION[$module_data . '_shipping_address']['city'];
		$order_data['shipping_postcode'] = $_SESSION[$module_data . '_shipping_address']['postcode'];
		$order_data['shipping_zone'] = $_SESSION[$module_data . '_shipping_address']['zone'];
		$order_data['shipping_zone_id'] = $_SESSION[$module_data . '_shipping_address']['zone_id'];
		$order_data['shipping_country'] = $_SESSION[$module_data . '_shipping_address']['country'];
		$order_data['shipping_country_id'] = $_SESSION[$module_data . '_shipping_address']['country_id'];
		$order_data['shipping_address_format'] = $_SESSION[$module_data . '_shipping_address']['address_format'];
		$order_data['shipping_custom_field'] = ( isset( $_SESSION[$module_data . '_shipping_address']['custom_field'] ) ? $_SESSION[$module_data . '_shipping_address']['custom_field'] : array() );

		if( isset( $_SESSION[$module_data . '_shipping_method']['title'] ) )
		{
			$order_data['shipping_method'] = $_SESSION[$module_data . '_shipping_method']['title'];
		}
		else
		{
			$order_data['shipping_method'] = '';
		}

		if( isset( $_SESSION[$module_data . '_shipping_method']['code'] ) )
		{
			$order_data['shipping_code'] = $_SESSION[$module_data . '_shipping_method']['code'];
		}
		else
		{
			$order_data['shipping_code'] = '';
		}
	}
	else
	{
		$order_data['shipping_first_name'] = '';
		$order_data['shipping_last_name'] = '';
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = '';
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_city'] = '';
		$order_data['shipping_postcode'] = '';
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_country'] = '';
		$order_data['shipping_country_id'] = '';
		$order_data['shipping_address_format'] = '';
		$order_data['shipping_custom_field'] = array();
		$order_data['shipping_method'] = '';
		$order_data['shipping_code'] = '';
	}

	$order_data['products'] = array();

	foreach( $getProducts as $product )
	{
		$option_data = array();

		foreach( $product['option'] as $option )
		{
			$option_data[] = array(
				'product_option_id' => $option['product_option_id'],
				'product_option_value_id' => $option['product_option_value_id'],
				'option_id' => $option['option_id'],
				'option_value_id' => $option['option_value_id'],
				'name' => $option['name'],
				'value' => $option['value'],
				'type' => $option['type'] );
		}

		$order_data['products'][] = array(
			'product_id' => $product['product_id'],
			'name' => $product['name'],
			'model' => $product['model'],
			'option' => $option_data,
			'download' => $product['download'],
			'quantity' => $product['quantity'],
			'subtract' => $product['subtract'],
			'price' => $product['price'],
			'total' => $product['total'],
			'tax' => $ProductTax->getTax( $product['price'], $product['tax_class_id'] ),
			'reward' => $product['reward'] );
	}

	// Gift Voucher
	$order_data['vouchers'] = array();

	if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
	{
		foreach( $_SESSION[$module_data . '_vouchers'] as $voucher )
		{
			$order_data['vouchers'][] = array(
				'description' => $voucher['description'],
				'code' => token( 10 ),
				'to_name' => $voucher['to_name'],
				'to_email' => $voucher['to_email'],
				'from_name' => $voucher['from_name'],
				'from_email' => $voucher['from_email'],
				'voucher_theme_id' => $voucher['voucher_theme_id'],
				'message' => $voucher['message'],
				'amount' => $voucher['amount'] );
		}
	}

	$order_data['comment'] = $_SESSION[$module_data . '_comment'];
	$order_data['total'] = $total_data['total'];

	if( $nv_Request->get_string('tracking', 'cookie', '' ))
	{
		$order_data['tracking'] = $nv_Request->get_string('tracking', 'cookie', '' );

		$subtotal = $ProductCart->getSubTotal();

		// Affiliate
		//$affiliate_info = getAffiliateByTracking( $nv_Request->get_string('tracking', 'cookie', '' ) );
		$affiliate_info = '';
		if( $affiliate_info )
		{
			$order_data['affiliate_id'] = $affiliate_info['userid'];
			$order_data['commission'] = ( $subtotal / 100 ) * $affiliate_info['commission'];
		}
		else
		{
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
		}

		// Marketing
		// $this->load->model( 'checkout/marketing' );

		//$marketing_info = getMarketingByCode( $nv_Request->get_string('tracking', 'cookie', '' ) );
		$marketing_info = '';
		if( $marketing_info )
		{
			$order_data['marketing_id'] = $marketing_info['marketing_id'];
		}
		else
		{
			$order_data['marketing_id'] = 0;
		}
	}
	else
	{
		$order_data['affiliate_id'] = 0;
		$order_data['commission'] = 0;
		$order_data['marketing_id'] = 0;
		$order_data['tracking'] = '';
	}

	$order_data['language_id'] = $ProductGeneral->config['config_language_id'];
	$order_data['currency_id'] = $ProductCurrency->getId( $nv_Request->get_string( $module_data . '_currency', 'session' ) );
	$order_data['currency_code'] = $nv_Request->get_string( $module_data . '_currency', 'session' );
	$order_data['currency_value'] = $ProductCurrency->getValue( $nv_Request->get_string( $module_data . '_currency', 'session' ) );
	$order_data['ip'] = $client_info['ip'];

	$order_data['forwarded_ip'] = $client_info['ip'];

	if( NV_USER_AGENT )
	{
		$order_data['user_agent'] = NV_USER_AGENT;
	}
	else
	{
		$order_data['user_agent'] = '';
	}

	if( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) )
	{
		$order_data['accept_language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	}
	else
	{
		$order_data['accept_language'] = '';
	}

	
 
	$_SESSION[$module_data . '_order_id'] =  addOrder( $order_data );
 
	$dataContent['products'] = array();

	foreach( $getProducts as $product )
	{
		$option_data = array();

		foreach( $product['option'] as $option )
		{
			if( $option['type'] != 'file' )
			{
				$value = $option['value'];
			}
			else
			{
				$value = '';
			}
			// else
			// {
				// $upload_info = getUploadByCode( $option['value'] );

				// if( $upload_info )
				// {
					// $value = $upload_info['name'];
				// }
				// else
				// {
					// $value = '';
				// }
			// }

			$option_data[] = array( 'name' => $option['name'], 'value' => ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ) );
		}

		$recurring = '';

		if( $product['recurring'] )
		{
			$frequencies = array(
				'day' => $lang_ext['text_day'],
				'week' => $lang_ext['text_week'],
				'semi_month' => $lang_ext['text_semi_month'],
				'month' => $lang_ext['text_month'],
				'year' =>$lang_ext['text_year'],
			);

			if( $product['recurring']['trial'] )
			{
				$recurring = sprintf( $lang_ext['text_trial_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $_SESSION[$module_data . '_currency'] ), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration'] ) . ' ';
			}

			if( $product['recurring']['duration'] )
			{
				$recurring .= sprintf( $lang_ext['text_payment_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $_SESSION[$module_data . '_currency'] ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
			}
			else
			{
				$recurring .= sprintf( $lang_ext['text_payment_cancel'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $_SESSION[$module_data . '_currency'] ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
			}
		}

		$dataContent['products'][] = array(
			'cart_id' => $product['cart_id'],
			'product_id' => $product['product_id'],
			'name' => $product['name'],
			'model' => $product['model'],
			'option' => $option_data,
			'recurring' => $recurring,
			'quantity' => $product['quantity'],
			'subtract' => $product['subtract'],
			'price' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $_SESSION[$module_data . '_currency'] ),
			'total' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) * $product['quantity'], $_SESSION[$module_data . '_currency'] ),
			'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'] );
	}

	// Gift Voucher
	$dataContent['vouchers'] = array();

	if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
	{
		foreach( $_SESSION[$module_data . '_vouchers'] as $voucher )
		{
			$dataContent['vouchers'][] = array( 'description' => $voucher['description'], 'amount' => $ProductCurrency->format( $voucher['amount'], $_SESSION[$module_data . '_currency'] ) );
		}
	}

	$dataContent['totals'] = array();

	foreach( $order_data['totals'] as $total )
	{
		$dataContent['totals'][] = array( 'title' => $total['title'], 'text' => $ProductCurrency->format( $total['value'], $_SESSION[$module_data . '_currency'] ) );
	}
	
	if( isset( $_SESSION[$module_data . '_payment_method']['code'] ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/user_extension/payment/' . $_SESSION[$module_data . '_payment_method']['code'] . '.php';
			
		$dataContent['payment'] = call_user_func( 'index_' . $_SESSION[$module_data . '_payment_method']['code'] );
	}
  
}
else
{
	$dataContent['redirect'] = $dataContent['redirect'];
}


$xtpl = new XTemplate( 'ThemeCheckoutConfirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

if( $dataContent['redirect'] )
{
	$xtpl->assign( 'REDIRECT', $dataContent['redirect']); 
	$xtpl->parse( 'main.redirect' );
	 
}
else
{
	if( $dataContent['products'] )
	{
		foreach( $dataContent['products'] as $product )
		{
			$xtpl->assign( 'PRODUCT', $product); 
			
			if( $product['option'] )
			{
				foreach( $dataContent['products'] as $product )
				{
					$xtpl->parse( 'main.data.product.option' );
				}
			}
			if( $product['recurring'] )
			{
				$xtpl->parse( 'main.data.product.recurring' );
			}
 
			$xtpl->parse( 'main.data.product' );
			
		}
		
	}
	if( $dataContent['vouchers'] )
	{
		foreach( $dataContent['vouchers'] as $voucher )
		{
			$xtpl->assign( 'VOUCHER', $voucher); 
			$xtpl->parse( 'main.data.voucher' );
			
		}
		
	}

	if( $dataContent['totals'] )
	{
		foreach( $dataContent['totals'] as $total )
		{
			$xtpl->assign( 'TOTAL', $total); 
			$xtpl->parse( 'main.data.total' );
			
		}
		
	}
 
	$xtpl->parse( 'main.data' );
}
if( $dataContent['error_warning'] )
{
	 
	$xtpl->assign( 'ERROR_WARNING', $dataContent['error_warning']);
	$xtpl->parse( 'main.error_warning' );
	 
}


$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
