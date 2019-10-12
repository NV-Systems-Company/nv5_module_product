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
	
	$dataContent['agree'] = $nv_Request->get_int( 'agree', 'post', 0 );
	$dataContent['comment'] = $nv_Request->get_title( 'comment', 'post', '', 1 );
	$dataContent['payment_method'] = $nv_Request->get_title( 'payment_method', 'post', '', 1 );
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	
	// Validate if payment address has been set.
	if( ! isset( $_SESSION[$module_data . '_payment_address'] ) )
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

	if( ! $dataContent['payment_method'] )
	{
		$json['error']['warning'] = $lang_ext['error_payment'];
	}
	elseif( ! isset( $_SESSION[$module_data . '_payment_methods'][$dataContent['payment_method']] ) )
	{
		$json['error']['warning'] = $lang_ext['error_payment'];
	}

	if( $ProductGeneral->config['config_checkout_id'] )
	{
	 

		$information_info =  getInformation( $ProductGeneral->config['config_checkout_id'] );

		if( $information_info && ! isset( $dataContent['agree'] ) )
		{
			$json['error']['warning'] = sprintf( $lang_ext['error_agree'], $information_info['title'] );
		}
	}

	if( ! $json )
	{
		$_SESSION[$module_data . '_payment_method'] = $_SESSION[$module_data . '_payment_methods'][$dataContent['payment_method']];

		$_SESSION[$module_data . '_comment'] = strip_tags( $dataContent['comment'] );
	}
	nv_jsonOutput($json);

}

$ContentContent = array();

if( isset( $_SESSION[$module_data . '_payment_address'] ) )
{
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );
	
	// Totals
	$totals = array();
	$taxes = $ProductCart->getTaxes();
	$total = 0;

	// Because __call can not keep var references so we put them into an array.
	$total_data = array(
		'totals' => &$totals,
		'taxes' => &$taxes,
		'total' => &$total );

 
	$sort_order = array();

	$results = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'total' ) )->fetchAll();

	foreach( $results as $key => $value )
	{
		$sort_order[$key] = $ProductGeneral->config['total_' . $value['code'] . '_sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $results );

	foreach( $results as $result )
	{
		if( $ProductGeneral->config['total_' . $result['code'] . '_status'] )
		{

			$classMap = 'NukeViet\Product\Total\\' . $class;
			${$class} = new $classMap( $productRegistry );
			${$class}->getTotal( $total_data );

		}
	}

	// Payment Methods
	$method_data = array();

	$results2 = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( 'payment' ) )->fetchAll();
	
	$recurring = $ProductCart->hasRecurringProducts();

	foreach( $results2 as $result )
	{
		$payment_config = $ProductGeneral->getSetting( 'payment_' . $result['code'], $ProductGeneral->store_id );
		if( $payment_config['payment_' . $result['code'] . '_status'] )
		{

			require_once NV_ROOTDIR . '/modules/' . $module_file . '/user_extension/payment/' . $result['code'] . '.php';
			
			$method =  call_user_func( 'getMethod_' . $result['code'], $_SESSION[$module_data . '_payment_address'], $total);

			if( $method )
			{
				// if( $recurring )
				// {
					// $method_data[$result['code']] = $method;	
				
					// if( property_exists( $this->{'model_extension_payment_' . $result['code']}, 'recurringPayments' ) && $this->{'model_extension_payment_' . $result['code']}->recurringPayments() )
					// {
						// $method_data[$result['code']] = $method;
					// }
				//}
				// else
				// {
					 $method_data[$result['code']] = $method;
				// }
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
	$dataContent['error_warning'] = sprintf( $lang_ext['error_no_payment'], '/#information/contact' );
}
else
{
	$dataContent['error_warning'] = '';
}

if( isset( $_SESSION[$module_data . '_payment_methods'] ) )
{
	$dataContent['payment_methods'] = $_SESSION[$module_data . '_payment_methods'];
}
else
{
	$dataContent['payment_methods'] = array();
}

if( isset( $_SESSION[$module_data . '_payment_method']['code'] ) )
{
	$dataContent['code'] = $_SESSION[$module_data . '_payment_method']['code'];
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

 
if( $ProductGeneral->config['config_checkout_id'] )
{
 
	$information_info = getInformation( $ProductGeneral->config['config_checkout_id'] );

	if( $information_info )
	{
		$link_infomation = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info/' . $information_info['alias'] . '-' . $information_info['information_id'] . $global_config['rewrite_exturl'], true );
		$data['text_agree'] = sprintf( $lang_ext['text_agree'], $link_infomation, $information_info['title'], $information_info['title'] );
	}
	else
	{
		$dataContent['text_agree'] = '';
	}
}
else
{
	$dataContent['text_agree'] = '';
}

if( isset( $_SESSION[$module_data . '_agree'] ) )
{
	$dataContent['agree'] = $_SESSION[$module_data . '_agree'];
}
else
{
	$dataContent['agree'] = '';
}

$xtpl = new XTemplate( 'ThemeCheckoutPaymentMethod.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'DATA', $dataContent );

if( $dataContent['payment_methods'] )
{
	foreach($dataContent['payment_methods'] as $key => $payment_methods )
	{
		$payment_methods['checked'] = ( ( $dataContent['code'] == $payment_methods['code'] ) || ( $key == 0 ) ) ? 'checked="checked"': '';
		$xtpl->assign( 'PAYMENT', $payment_methods );
		if(  $payment_methods['terms'] )
		{
			$xtpl->parse( 'main.payment.terms' );
		}
		
		$xtpl->parse( 'main.payment' );
	}	
	
}
 
$xtpl->assign( 'AGREE_CHECKED', ( $dataContent['agree'] == 1 ) ? 'checked="checked"': '' );

if( $dataContent['error_warning'] )
{
	 
	$xtpl->assign( 'ERROR_WARNING', $dataContent['error_warning']);
	$xtpl->parse( 'main.error_warning' );
	 
}


$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
 
