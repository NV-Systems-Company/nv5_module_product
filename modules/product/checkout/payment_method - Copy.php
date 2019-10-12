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

	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

	// if( empty( $user_info ) )
	// {
		// $json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	// }

 
	if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
	{
		$json['redirect'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout';
	}

	if( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart';
	}

	if( ! $json )
	{
		$payment_method = $nv_Request->get_string( 'payment_method', 'post', '' );
		$agree = $nv_Request->get_int( 'agree', 'post', 0 );
		$comment = $nv_Request->get_string( 'comment', 'post', '' );

		if( empty( $payment_method ) )
		{
			$json['error']['warning'] = $lang_module['checkout_error_payment'];
		}
		elseif( ! isset( $_SESSION[$module_data . '_payment_methods'][$payment_method] ) )
		{
			$json['error']['warning'] = $lang_module['checkout_error_payment'];
		}

		if( ! empty( $ProductGeneral->config['config_checkout_id'] ) )
		{

			$getInformation = getInformation();
			$information_info = isset( $getInformation[$ProductGeneral->config['config_checkout_id']] ) ? $getInformation[$ProductGeneral->config['config_checkout_id']] : array();

			if( $information_info && empty( $agree ) )
			{
				$json['error']['warning'] = sprintf( $lang_module['checkout_error_agree'], $information_info['title'] );
			}
		}
		if( ! $json )
		{
			$_SESSION[$module_data . '_payment_method'] = $_SESSION[$module_data . '_payment_methods'][$payment_method];

			$_SESSION[$module_data . '_comment'] = strip_tags( $comment );
		}
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

$data = array();

if( isset( $_SESSION[$module_data . '_payment_address'] ) )
{
	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

	$total = $ProductContent->getSubTotal();
	$recurring = $ProductContent->hasRecurringProducts();
	// Payment Methods
	$method_data = array();

	$results = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'payment' ) )->fetchAll();
	
	foreach( $results as $result )
	{
		$payment_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );
		 
		if( isset( $payment_config[$result['code'] . '_status'] ) && $payment_config[$result['code'] . '_status'] )
		{
			if( $recurring )
			{
				// call method recurringPayments if exist
				//$method_data[$result['code']] = $method;

			}
			else
			{
				//gọi ngôn ngữ payment extension
				$lang_plug = $ProductGeneral->getLangSite( $result['code'], 'payment' );
				
				$method_data[$result['code']] = array(
					'code' => $result['code'],
					'title' => $lang_plug['heading_title'],
					'sort_order' => $payment_config[$result['code'] . '_sort_order'],
					'terms' => '' );
			}
		}
	}

	$sort_order = array();

	foreach( $method_data as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $method_data );

	$_SESSION[$module_data . '_payment_methods'] = $method_data;
}

if( empty( $_SESSION[$module_data . '_payment_methods'] ) )
{
	$data['error_warning'] = sprintf( $lang_module['error_no_payment'], '/contact' );
}
else
{
	$data['error_warning'] = '';
}

if( isset( $_SESSION[$module_data . '_payment_methods'] ) )
{
	$data['payment_methods'] = $_SESSION[$module_data . '_payment_methods'];
}
else
{
	$data['payment_methods'] = array();
}

if( isset( $_SESSION[$module_data . '_payment_method']['code'] ) )
{
	$data['code'] = $_SESSION[$module_data . '_payment_method']['code'];
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
 
if( $ProductGeneral->config['config_checkout_id'] )
{
	$getInformation = getInformation();
	$information_info = isset( $getInformation[$ProductGeneral->config['config_checkout_id']] ) ? $getInformation[$ProductGeneral->config['config_checkout_id']] : array();

	if( ! empty( $information_info ) )
	{
		$link_infomation = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info/' . $information_info['alias'] . '-' . $information_info['information_id'] . $global_config['rewrite_exturl'], true );
		$data['text_agree'] = sprintf( $lang_ext['text_agree'], $link_infomation, $information_info['title'], $information_info['title'] );
 
	}
	else
	{
		$data['text_agree'] = '';
	}
}
else
{
	$data['text_agree'] = '';
}
 
if( isset( $_SESSION[$module_data . '_agree'] ) )
{
	$data['agree'] = $_SESSION[$module_data . '_agree'];
}
else
{
	$data['agree'] = '';
}
$contents = checkout_payment_method( $data, $lang_ext );
 
echo $contents;
exit();
