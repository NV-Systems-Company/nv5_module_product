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

$getCountry = getCountry();

if( ACTION_METHOD == 'zone' )
{
	$info = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );

	$info = $getCountry[$country_id];

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


if( ACTION_METHOD == 'add' )
{
	$json = array();
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$recurring_id = $nv_Request->get_int( 'recurring_id', 'post', 0 );
	$quantity = $nv_Request->get_int( 'quantity', 'post', 0 );
	$option = $nv_Request->get_array( 'option', 'post', array() );

	$ProductContent = new NukeViet\Product\Product( $productRegistry );
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	$product_info = $ProductContent->getProduct( $product_id );
	$product_info['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product_info['category_id']]['alias'] . '/' . $product_info['alias'] . $global_config['rewrite_exturl'], true );
	

	if( $product_info )
	{
		if( $quantity && ( $quantity >= $product_info['minimum'] ) )
		{
			$quantity = $quantity;
		}
		else
		{
			$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
		}

		if( $option )
		{
			$option = array_filter( $option );
		}
		else
		{
			$option = array();
		}

		$product_options = $ProductContent->getProductOptions( $product_id );

		foreach( $product_options as $product_option )
		{
			if( $product_option['required'] && empty( $option[$product_option['product_option_id']] ) )
			{
				$json['error']['option'][$product_option['product_option_id']] = sprintf( $lang_module['cart_error_required'], $product_option['name'] );
			}
		}
		
		// $recurrings = $ProductContent->getProfiles( $product_info['product_id'] );

		// if( $recurrings )
		// {
		// $recurring_ids = array();

		// foreach( $recurrings as $recurring )
		// {
		// $recurring_ids[] = $recurring['recurring_id'];
		// }

		// if( ! in_array( $recurring_id, $recurring_ids ) )
		// {
		// $json['error']['recurring'] = $lang_module['error_recurring_required'];
		// }
		// }

		if( ! $json )
		{

			// add to table cart

			$api_id = 0;

			$_total = $db->query( 'SELECT COUNT(*) AS total FROM ' . TABLE_PRODUCT_NAME . '_cart WHERE api_id = ' . intval( $api_id ) . ' 
			AND customer_id = ' . intval( $globalUserid ) . ' 
			AND session_id = ' . $db->quote( $client_info['session_id'] ) . ' 
			AND product_id = ' . intval( $product_id ) . ' 
			AND recurring_id = ' . intval( $recurring_id ) . ' 
			AND option = ' . $db->quote( json_encode( $option ) ) )->fetchColumn();

			if( ! $_total )
			{
				$db->query( 'INSERT ' . TABLE_PRODUCT_NAME . '_cart SET 
				api_id = 0, 
				customer_id = ' . intval( $globalUserid ) . ', 
				session_id = ' . $db->quote( $client_info['session_id'] ) . ', 
				product_id = ' . intval( $product_id ) . ' , 
				recurring_id = ' . ( int )$recurring_id . ', 
				option = ' . $db->quote( json_encode( $option ) ) . ', 
				quantity = ' . ( int )$quantity . ', 
				date_added = ' . NV_CURRENTTIME );
			}
			else
			{
				$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_cart SET 
				quantity = (quantity + ' . ( int )$quantity . ') 
				WHERE api_id = ' . intval( $api_id ) . ' 
				AND customer_id = ' . intval( $globalUserid ) . ' 
				AND session_id = ' . $db->quote( $client_info['session_id'] ) . ' 
				AND product_id = ' . intval( $product_id ) . ' 
				AND recurring_id = ' . intval( $recurring_id ) . '  
				AND option = ' . $db->quote( json_encode( $option ) ) );
			}

			$link_cart = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
			$json['link_cart'] = $link_cart;
	
			$json['success'] = sprintf( $lang_module['cart_success'], $product_info['link'], $product_info['name'], $link_cart );
			// Unset all shipping and payment methods
			unset( $_SESSION[$module_data . '_shipping_method'] );
			unset( $_SESSION[$module_data . '_shipping_methods'] );
			unset( $_SESSION[$module_data . '_payment_method'] );
			unset( $_SESSION[$module_data . '_payment_methods'] );

			// Totals

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
			}

			$json['total'] = sprintf( $lang_module['checkout_text_items'], $ProductCart->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total, $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );
		}
		else
		{
			$json['redirect'] = $product_info['link'];
		}
	}
	
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'update' )
{ 
	$json = array();
	
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	$lang_ext = $ProductGeneral->getLangSite( 'cart', 'checkout' );
	
	$dataContent = $nv_Request->get_typed_array( 'quantity', 'post', 'int', array() );
	
	if( !empty( $dataContent ) )
	{
		foreach ( $dataContent as $key => $value )
		{
			$ProductCart->update( $key, $value );
		}
		$_SESSION[$module_data . '_success'] = $lang_ext['text_remove'];

		unset($_SESSION[$module_data . '_shipping_method']);
		unset($_SESSION[$module_data . '_shipping_methods']);
		unset($_SESSION[$module_data . '_payment_method']);
		unset($_SESSION[$module_data . '_payment_methods']);
		unset($_SESSION[$module_data . '_reward']);

		$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart';

		$base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );
		
		Header( 'Location: ' . $base_url_rewrite );
		die();
		
		
	}
 
	
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'remove' )
{
	$json = array();
	$cart_id = $nv_Request->get_int( 'key', 'post', 0 );

	if( $cart_id )
	{
		$ProductCart = new NukeViet\Product\Cart( $productRegistry );

		$ProductCart->remove( $cart_id );

		unset( $_SESSION[$module_data . '_vouchers'][$cart_id] );
		unset( $_SESSION[$module_data . '_shipping_method'] );
		unset( $_SESSION[$module_data . '_shipping_methods'] );
		unset( $_SESSION[$module_data . '_payment_method'] );
		unset( $_SESSION[$module_data . '_payment_methods'] );
		unset( $_SESSION[$module_data . '_reward'] );

		$json['success'] = $lang_module['cart_modified_success'];

		// Totals

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
		}

		$json['total'] = sprintf( $lang_module['checkout_text_items'], $ProductCart->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total, $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );

	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'coupon' )
{
 
	$json = array();

	$lang_ext = $ProductGeneral->getLangSite( 'coupon' , 'total' );

	$coupon = $nv_Request->get_string( 'coupon', 'post', '' );
	
	
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	$ProductCoupon = new NukeViet\Product\Total\coupon( $productRegistry );
	
	$coupon_info = $ProductCoupon->getCoupon( $coupon );

	if( empty( $coupon ) )
	{
		$json['error'] = $lang_ext['error_empty'];

		unset(  $_SESSION[$module_data . '_coupon'] );
	}
	elseif( $coupon_info )
	{
		$_SESSION[$module_data . '_coupon'] = $coupon;

		$_SESSION[$module_data . '_success'] = $lang_ext['text_success'];
 
		$json['redirect'] =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );
 
	}
	else
	{
		$json['error'] = $lang_ext['error_coupon'];
	}
 
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'voucher' )
{
 
	$json = array();

	$lang_ext = $ProductGeneral->getLangSite( 'voucher' , 'total' );

	$voucher = $nv_Request->get_string( 'voucher', 'post', '' );
	
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	$ProductVoucher = new NukeViet\Product\Total\voucher( $productRegistry );
	
	$voucher_info = $ProductVoucher->getVoucher( $voucher );
	
	if( empty( $voucher ) )
	{
		$json['error'] = $lang_ext['error_empty'];

		unset(  $_SESSION[$module_data . '_voucher'] );
	}
	elseif( $voucher_info )
	{
		$_SESSION[$module_data . '_voucher'] = $voucher;

		$_SESSION[$module_data . '_success'] = $lang_ext['text_success'];
 
		$json['redirect'] =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart', true );
 
	}
	else
	{
		$json['error'] = $lang_ext['error_voucher'];
	}
 
	nv_jsonOutput( $json );
}

 
$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart';

$base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );
if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
	Header( 'Location: ' . $base_url_rewrite );
	die();
}

$page_title = $lang_module['cart_title'];

$dataContentContent = array();

$ProductCart = new NukeViet\Product\Cart( $productRegistry );
$ProductWeight = new NukeViet\Product\Weight( $productRegistry );

$lang_ext = $ProductGeneral->getLangSite( 'cart', 'checkout' );


if( $ProductCart->hasProducts() || ! empty( $_SESSION[$module_data . '_vouchers'] ) )
{
	

	if( ! $ProductCart->hasStock() && ( ! $ProductGeneral->config['config_stock_checkout'] || $ProductGeneral->config['config_stock_warning'] ) )
	{
		$dataContent['error_warning'] = $lang_ext['error_stock'];
	}
	elseif( isset( $_SESSION[$module_data . '_error'] ) )
	{
		$dataContent['error_warning'] = $_SESSION[$module_data . '_error'];

		unset( $_SESSION[$module_data . '_error'] );
	}
	else
	{
		$dataContent['error_warning'] = '';
	}

	$link_login = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login', true );
	$link_register = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=register', true );

	if( $ProductGeneral->config['config_customer_price'] && ! defined( 'NV_IS_USER' ) )
	{
		$dataContent['attention'] = sprintf( $lang_ext['text_login'], $link_login, $link_register );
	}
	else
	{
		$dataContent['attention'] = '';
	}

	if( isset( $_SESSION[$module_data . '_success'] ) )
	{
		$dataContent['success'] = $_SESSION[$module_data . '_success'];

		unset( $_SESSION[$module_data . '_success'] );
	}
	else
	{
		$dataContent['success'] = '';
	}

	if( $ProductGeneral->config['config_cart_weight'] )
	{
		$dataContent['weight'] = $ProductWeight->format( $ProductCart->getWeight(), $ProductGeneral->config['config_weight_class_id'], $lang_module['currency_decimal_point'], $lang_module['currency_thousand_point'] );
	}
	else
	{
		$dataContent['weight'] = '';
	}

	$dataContent['products'] = array();

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
			$dataContent['error_warning'] = sprintf( $lang_ext['error_minimum'], $product['name'], $product['minimum'] );
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
				$upload_info = ''; //getUploadByCode( $option['value'] );

				if( $upload_info )
				{
					$value = $upload_info['name'];
				}
				else
				{
					$value = '';
				}
			}

			$option_data[] = array( 'name' => $option['name'], 'value' => ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ) );
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

		$recurring = '';

		if( $product['recurring'] )
		{
			$frequencies = array(
				'day' => $lang_ext['text_day'],
				'week' => $lang_ext['text_week'],
				'semi_month' => $lang_ext['text_semi_month'],
				'month' => $lang_ext['text_month'],
				'year' => $lang_ext['text_year'],
				);

			if( $product['recurring']['trial'] )
			{
				$recurring = sprintf( $lang_ext['text_trial_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration'] ) . ' ';
			}

			if( $product['recurring']['duration'] )
			{
				$recurring .= sprintf( $lang_ext['text_payment_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
			}
			else
			{
				$recurring .= sprintf( $lang_ext['text_payment_cancel'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get( 'config_tax' ) ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
			}
		}

		$dataContent['products'][] = array(
			'cart_id' => $product['cart_id'],
			'thumb' => $product['thumb'],
			'name' => $product['name'],
			'model' => $product['model'],
			'option' => $option_data,
			'recurring' => $recurring,
			'quantity' => $product['quantity'],
			'stock' => $product['stock'] ? true : ! ( ! $ProductGeneral->config['config_stock_checkout'] || $ProductGeneral->config['config_stock_warning'] ),
			'reward' => ( $product['reward'] ? sprintf( $lang_ext['text_points'], $product['reward'] ) : '' ),
			'price' => $price,
			'total' => $total,
			'link' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'], true ) );
	}

	// Gift Voucher
	$dataContent['vouchers'] = array();

	if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
	{
		foreach( $_SESSION[$module_data . '_vouchers'] as $key => $voucher )
		{
			$dataContent['vouchers'][] = array(
				'key' => $key,
				'description' => $voucher['description'],
				'amount' => $ProductCurrency->format( $voucher['amount'], $nv_Request->get_string( $module_data . '_currency', 'session' ) ),
				'remove' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart&action=remove&key=' . $key );
		}
	}

	$xtotals = array();
	$taxes = $ProductCart->getTaxes();
	$total = 0;
 
	$total_data = array(
		'xtotals' => &$xtotals,
		'taxes' => &$taxes,
		'total' => &$total );

	// Display prices
	if( defined( 'NV_IS_USER' ) || ! $ProductGeneral->config['config_customer_price'] )
	{
		$sort_order = array();

		$results = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_extension WHERE type = ' . $db->quote( 'total' ) )->fetchAll();

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
	}

	$dataContent['text_items'] = sprintf( $ProductGeneral->mod_lang['checkout_text_items'], $ProductCart->countProducts() + ( isset( $_SESSION[$module_data . '_vouchers'] ) ? count( $_SESSION[$module_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total, $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );

	$dataContent['products'] = array();

	foreach( $getProducts as $product )
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

		//Hien thi gia
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

		$dataContent['products'][] = array(
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

	$dataContent['xtotals'] = array();

	foreach( $xtotals as $total )
	{
		$dataContent['xtotals'][] = array(
			'title' => $total['title'],
			'value' => $ProductCurrency->format( $total['value'], $nv_Request->get_string( $module_data . '_currency', 'session' ) ),
		);
	}
	
	$files = glob( NV_ROOTDIR . '/modules/'. $module_file  .'/user_extension/total/*.php');

	if ( $files ) 
	{
		foreach ( $files as $file ) 
		{
			$result = basename( $file, '.php');
			if ( $result ) 
			{
				$dataContent['modules'][] = $result;
			}
		}
	}
	
	
	$dataContent['checkout'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout';
	$dataContent['continue'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
			
	$contents = ThemeProductViewCart( $dataContent );
}
else
{
	$dataContent['heading_title'] = $lang_ext['heading_title'];

	$dataContent['button_continue'] = $lang_module['button_continue'];

	$dataContent['continue'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	
	$dataContent['text_error'] = $lang_ext['text_empty'];

	unset( $_SESSION[$module_data . '_success'] );
	
	$contents = ThemeProductErrorNotFound( $dataContent );
	
}
 
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
