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
 

$ProductCart = new NukeViet\Product\Cart( $productRegistry );
$ProductTax = new NukeViet\Product\Tax( $productRegistry );

$data = array();

$redirect = '';

if( $ProductCart->hasShipping() )
{
	// Validate if shipping address has been set.
	if( ! isset( $_SESSION[$module_data . '_shipping_address'] ) )
	{
		$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout';
	}

	// Validate if shipping method has been set.
	if( ! isset( $_SESSION[$module_data . '_shipping_method'] ) )
	{
		$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout';
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
	$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout';
}

// Validate if payment method has been set.
if( ! isset( $_SESSION[$module_data . '_payment_method'] ) )
{
	$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout';
}

// Validate cart has products and has stock.
if( ( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
{
	$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart';

}

if( ! $redirect )
{
	$order_data = array();

	$order_data['totals'] = array();
	$total = 0;
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
		${$class}->getTotal( $order_data['totals'], $total, $taxes );

	}
	$sort_order = array();

	foreach( $order_data['totals'] as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $order_data['totals'] );

	if( defined( 'NV_IS_USER' ) )
	{

		$order_data['userid'] = $user_info['userid'];
		$order_data['customer_group_id'] = $user_info['customer_group_id'];
		$order_data['first_name'] = $user_info['first_name'];
		$order_data['last_name'] = $user_info['last_name'];
		$order_data['email'] = $user_info['email'];
		$order_data['telephone'] = $user_info['telephone'];
		$order_data['fax'] = $user_info['fax'];
		$order_data['custom_field'] = '';
	}
	elseif( isset( $_SESSION[$module_data . '_guest'] ) )
	{
		$order_data['userid'] = 0;
		$order_data['customer_group_id'] = $_SESSION[$module_data . '_guest']['customer_group_id'];
		$order_data['first_name'] = $_SESSION[$module_data . '_guest']['first_name'];
		$order_data['last_name'] = $_SESSION[$module_data . '_guest']['last_name'];
		$order_data['email'] = $_SESSION[$module_data . '_guest']['email'];
		$order_data['telephone'] = $_SESSION[$module_data . '_guest']['telephone'];
		$order_data['fax'] = $_SESSION[$module_data . '_guest']['fax'];
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
	$order_data['payment_custom_field'] = $_SESSION[$module_data . '_payment_address']['custom_field'];

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

	if( $ProductContent->hasShipping() )
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
		$order_data['shipping_custom_field'] = $_SESSION[$module_data . '_shipping_address']['custom_field'];

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

	foreach( $ProductContent->getProducts() as $product )
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
			'alias' => $product['alias'],
			'model' => $product['model'],
			'category_id' => $product['category_id'],
			'option' => $option_data,
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
				'code' => substr( md5( mt_rand() ), 0, 10 ),
				'to_name' => $voucher['to_name'],
				'to_email' => $voucher['to_email'],
				'from_name' => $voucher['from_name'],
				'from_email' => $voucher['from_email'],
				'voucher_theme_id' => $voucher['voucher_theme_id'],
				'message' => $voucher['message'],
				'amount' => $voucher['amount'] );
		}
	}

	//store
	//$getStores = getStores( );

	$order_data['store_id'] = $ProductGeneral->store_id;
	$order_data['store_name'] = $ProductGeneral->config['config_name'];

	if( $order_data['store_id'] )
	{
		$order_data['store_url'] = $ProductGeneral->config['config_url'];
	}
	else
	{
		$order_data['store_url'] = NV_MY_DOMAIN;
	}

	// chinh sach dai ly
	$order_data['affiliate_id'] = 0;
	$order_data['commission'] = '';
	$order_data['marketing_id'] = '';
	$order_data['tracking'] = '';
	$order_data['forwarded_ip'] = '';
	$order_data['user_agent'] = NV_USER_AGENT;
	$order_data['accept_language'] = NV_LANG_DATA;

	$order_data['comment'] = $_SESSION[$module_data . '_comment'];
	$order_data['total'] = $total;

	$order_data['language_id'] = $ProductGeneral->current_language_id;
	$order_data['currency_id'] = $ProductCurrency->getId();
	$order_data['currency_code'] = $ProductCurrency->getCode();
	$order_data['currency_value'] = $ProductCurrency->getValue( $ProductCurrency->getCode() );
	$order_data['ip'] = $client_info['ip'];

	/*insert order*/
	$order_data['custom_field'] = isset( $order_data['custom_field'] ) ? serialize( $order_data['custom_field'] ) : '';
	$order_data['payment_custom_field'] = isset( $order_data['payment_custom_field'] ) ? serialize( $order_data['payment_custom_field'] ) : '';
	$order_data['shipping_custom_field'] = isset( $order_data['shipping_custom_field'] ) ? serialize( $order_data['shipping_custom_field'] ) : '';

	$result = $db->query( "SHOW TABLE STATUS WHERE Name='" . TABLE_PRODUCT_NAME . "_order'" );
	$item = $result->fetch();
	$result->closeCursor();

	$order_data['invoice_prefix'] = vsprintf( $ProductGeneral->config['config_format_order_id'], ( int )$item['auto_increment'] );

	try
	{
		$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order SET
			invoice_prefix = ' . $db->quote( $order_data['invoice_prefix'] ) . ',
			userid = ' . intval( $order_data['userid'] ) . ',
			customer_group_id =' . intval( $order_data['customer_group_id'] ) . ',
			store_id =' . intval( $order_data['store_id'] ) . ',
			store_name = ' . $db->quote( $order_data['store_name'] ) . ',
			store_url = ' . $db->quote( $order_data['store_url'] ) . ',
			first_name = ' . $db->quote( $order_data['first_name'] ) . ',
			last_name = ' . $db->quote( $order_data['last_name'] ) . ',
			email = ' . $db->quote( $order_data['email'] ) . ',
			telephone = ' . $db->quote( $order_data['telephone'] ) . ',
			fax = ' . $db->quote( $order_data['fax'] ) . ',
			custom_field = ' . $db->quote( $order_data['custom_field'] ) . ',
			payment_first_name= ' . $db->quote( $order_data['payment_first_name'] ) . ',
			payment_last_name= ' . $db->quote( $order_data['payment_last_name'] ) . ',
			payment_company= ' . $db->quote( $order_data['payment_company'] ) . ',
			payment_address_1= ' . $db->quote( $order_data['payment_address_1'] ) . ',
			payment_address_2= ' . $db->quote( $order_data['payment_address_2'] ) . ',
			payment_city= ' . $db->quote( $order_data['payment_city'] ) . ',
			payment_postcode= ' . $db->quote( $order_data['payment_postcode'] ) . ',
			payment_country= ' . $db->quote( $order_data['payment_country'] ) . ',
			payment_country_id= ' . intval( $order_data['payment_country_id'] ) . ',
			payment_zone= ' . $db->quote( $order_data['payment_zone'] ) . ',
			payment_zone_id= ' . intval( $order_data['payment_zone_id'] ) . ',
			payment_address_format= ' . $db->quote( $order_data['payment_address_format'] ) . ',
			payment_custom_field= ' . $db->quote( $order_data['payment_custom_field'] ) . ',
			payment_method= ' . $db->quote( $order_data['payment_method'] ) . ',
			payment_code= ' . $db->quote( $order_data['payment_code'] ) . ',
			shipping_first_name= ' . $db->quote( $order_data['shipping_first_name'] ) . ',
			shipping_last_name = ' . $db->quote( $order_data['shipping_last_name'] ) . ',
			shipping_company= ' . $db->quote( $order_data['shipping_company'] ) . ',
			shipping_address_1= ' . $db->quote( $order_data['shipping_address_1'] ) . ',
			shipping_address_2= ' . $db->quote( $order_data['shipping_address_2'] ) . ',
			shipping_city= ' . $db->quote( $order_data['shipping_city'] ) . ',
			shipping_postcode= ' . $db->quote( $order_data['shipping_postcode'] ) . ',
			shipping_country= ' . $db->quote( $order_data['shipping_country'] ) . ',
			shipping_country_id= ' . intval( $order_data['shipping_country_id'] ) . ',
			shipping_zone= ' . $db->quote( $order_data['shipping_zone'] ) . ',
			shipping_zone_id= ' . intval( $order_data['shipping_zone_id'] ) . ',
			shipping_address_format= ' . $db->quote( $order_data['shipping_address_format'] ) . ',
			shipping_custom_field= ' . $db->quote( $order_data['shipping_custom_field'] ) . ',
			shipping_method= ' . $db->quote( $order_data['shipping_method'] ) . ',
			shipping_code= ' . $db->quote( $order_data['shipping_code'] ) . ',
			comment= ' . $db->quote( $order_data['comment'] ) . ',
			total= ' . $db->quote( $order_data['total'] ) . ',
			affiliate_id= ' . intval( $order_data['affiliate_id'] ) . ',
			commission= ' . $db->quote( $order_data['commission'] ) . ',
			marketing_id= ' . intval( $order_data['marketing_id'] ) . ',
			tracking= ' . $db->quote( $order_data['tracking'] ) . ',
			language_id=' . intval( $order_data['language_id'] ) . ',
			currency_id=' . intval( $order_data['currency_id'] ) . ',
			currency_code= ' . $db->quote( $order_data['currency_code'] ) . ',
			currency_value= ' . $db->quote( $order_data['currency_value'] ) . ',
			ip= ' . $db->quote( $order_data['ip'] ) . ',
			forwarded_ip= ' . $db->quote( $order_data['forwarded_ip'] ) . ',
			user_agent= ' . $db->quote( $order_data['user_agent'] ) . ',
			accept_language= ' . $db->quote( $order_data['accept_language'] ) . ',
			date_added=' . intval( NV_CURRENTTIME ) . ',
			date_modified=' . intval( NV_CURRENTTIME ) );

		if( $stmt->execute() )
		{
			$_SESSION[$module_data . '_order_id'] = $order_id = $db->lastInsertId();

			foreach( $order_data['products'] as $product )
			{
				$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_product SET 
							order_id = ' . ( int )$order_id . ', 
							product_id = ' . ( int )$product['product_id'] . ', 
							name = ' . $db->quote( $product['name'] ) . ', 
							model = ' . $db->quote( $product['model'] ) . ', 
							quantity = ' . ( int )$product['quantity'] . ',
							price = ' . ( float )$product['price'] . ', 
							total = ' . ( float )$product['total'] . ', 
							tax = ' . ( float )$product['tax'] . ', 
							reward = ' . ( int )$product['reward'] );

				$order_product_id = $db->lastInsertId();

				foreach( $product['option'] as $option )
				{
					$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_option SET 
								order_id = ' . ( int )$order_id . ', 
								order_product_id = ' . ( int )$order_product_id . ', 
								product_option_id = ' . ( int )$option['product_option_id'] . ', 
								product_option_value_id = ' . ( int )$option['product_option_value_id'] . ', 
								name = ' . $db->quote( $option['name'] ) . ', 
								value = ' . $db->quote( $option['value'] ) . ', 
								type = ' . $db->quote( $option['type'] ) );
				}

			}
			// Totals
			foreach( $order_data['totals'] as $total )
			{
				$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_total SET 
							order_id = ' . ( int )$order_id . ', 
							code = ' . $db->quote( $total['code'] ) . ',
							title = ' . $db->quote( $total['title'] ) . ', 
							value = ' . ( float )$total['value'] . ', 
							sort_order = ' . ( int )$total['sort_order'] );
			}

		}
		$stmt->closeCursor();
	}
	catch ( PDOException $e )
	{
		$error['warning'] = $lang_module['checkout_error_save'];
		//var_dump($e);
	}

	if( empty( $error ) )
	{

		// $data['text_recurring_item'] = $lang_module['checkout_text_recurring_item'];
		// $data['text_payment_recurring'] = $lang_module['checkout_text_payment_recurring'];

		$data['products'] = array();

		foreach( $ProductContent->getProducts() as $product )
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
					//$upload_info = $this->model_tool_upload->getUploadByCode( $option['value'] );
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

				$option_data[] = array( 'name' => $option['name'], 'value' => ( nv_strlen( $value ) > 20 ? utf8_substr( $value, 0, 20 ) . '..' : $value ) );
			}

			$recurring = '';

			if( $product['recurring'] )
			{
				$frequencies = array(
					'day' => $lang_module['checkout_text_day'],
					'week' => $lang_module['checkout_text_week'],
					'semi_month' => $lang_module['checkout_text_semi_month'],
					'month' => $lang_module['checkout_text_month'],
					'year' => $lang_module['checkout_text_year'],
					);

				if( $product['recurring_trial'] )
				{
					$recurring = sprintf( $lang_module['checkout_text_trial_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) ), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration'] ) . ' ';
				}

				if( $product['recurring_duration'] )
				{
					$recurring .= sprintf( $lang_module['checkout_text_payment_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
				}
				else
				{
					$recurring .= sprintf( $lang_module['checkout_text_payment_until_canceled_description'], $ProductCurrency->format( $ProductTax->calculate( $product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) ), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration'] );
				}
			}

			$data['products'][] = array(
				'key' => $product['key'],
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'alias' => $product['alias'],
				'model' => $product['model'],
				'thumb' => $product['thumb'],
				'image' => $product['image'],
				'model' => $product['model'],
				'category_id' => $product['category_id'],
				'option' => $option_data,
				'recurring' => $recurring,
				'quantity' => $product['quantity'],
				'subtract' => $product['subtract'],
				'price' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) ),
				'total' => $ProductCurrency->format( $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] ) * $product['quantity'] ) );
		}

		// Gift Voucher
		$data['vouchers'] = array();

		if( ! empty( $_SESSION[$module_data . '_vouchers'] ) )
		{
			foreach( $_SESSION[$module_data . '_vouchers'] as $voucher )
			{
				$data['vouchers'][] = array( 'description' => $voucher['description'], 'amount' => $ProductCurrency->format( $voucher['amount'] ) );
			}
		}

		$data['totals'] = array();

		foreach( $order_data['totals'] as $total )
		{
			$data['totals'][] = array(
				'title' => $total['title'],
				'text' => $ProductCurrency->format( $total['value'] ),
				);
		}
		$order_id = $_SESSION[$module_data . '_order_id'];
		$token = md5( $order_id . $_SESSION[$module_data . '_payment_method']['code'] . $global_config['sitekey'] . session_id() );
		$data['payment'] = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&action=checkout&order_id=' . $order_id . '&method=' . $_SESSION[$module_data . '_payment_method']['code'] . '&token=' . $token;

	}
}
else
{
	$data['redirect'] = $redirect;
}

$contents = checkout_confirm_method( $data, $lang_ext );
echo $contents;
exit();
