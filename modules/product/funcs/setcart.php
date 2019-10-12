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

if( ACTION_METHOD == 'add' )
{

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
				$json['error']['pdoption'][$product_option['product_option_id']] = sprintf( $lang_module['cart_error_required'], $product_option['name'] );
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
			AND pdoption = ' . $db->quote( json_encode( $option ) ) )->fetchColumn();

			if( ! $_total )
			{
				$db->query( 'INSERT ' . TABLE_PRODUCT_NAME . '_cart SET 
				api_id = 0, 
				customer_id = ' . intval( $globalUserid ) . ', 
				session_id = ' . $db->quote( $client_info['session_id'] ) . ', 
				product_id = ' . intval( $product_id ) . ' , 
				recurring_id = ' . ( int )$recurring_id . ', 
				pdoption = ' . $db->quote( json_encode( $option ) ) . ', 
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
				AND pdoption = ' . $db->quote( json_encode( $option ) ) );
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
					if( isset( $total_config[$result['code'] . '_status'] ) && $total_config[$result['code'] . '_status'] )
					{
						$array_class[] = $result['code'];

					}
				}

				foreach( $array_class as $key => &$class )
				{

					$classMap = 'NukeViet\Product\Total\\' . $class;
					//print_r($classMap);die;
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
}
elseif( ACTION_METHOD == 'update' )
{

}
elseif( ACTION_METHOD == 'remove' )
{
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

}

nv_jsonOutput( $json );
