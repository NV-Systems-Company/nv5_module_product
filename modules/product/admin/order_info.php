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

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );



$page_title = $lang_module['order_info'];



if( ACTION_METHOD == 'history' )
{
	
	$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
	$token = md5( $global_config['sitekey'] . session_id() . $order_id );
	
	
	$xtpl = new XTemplate( 'order_history.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'DATA', $dataContent );
	$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order" );
	$xtpl->assign( 'order_history', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order_history" );
	$xtpl->assign( 'add_history', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_history&action=add_history&token='.$token.'&order_id=' . $order_id );
	$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() . $order_id ) );

	   
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	 
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
 
}




$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
$order_status_id = $nv_Request->get_int( 'order_status_id', 'post,get', 0 );
$token = md5( $global_config['sitekey'] . session_id() . $order_id );
 
$save = $nv_Request->get_string( 'save', 'post', '' );
 
$order_info = $db->query('SELECT *, (SELECT CONCAT(u.first_name, \' \', u.last_name) FROM ' . NV_USERS_GLOBALTABLE . ' u WHERE u.userid = o.userid) AS customer FROM ' . TABLE_PRODUCT_NAME . '_order o WHERE o.order_id = ' . (int)$order_id )->fetch();
 
if( empty( $order_info ) ) Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order' );
 
$order_info['reward'] = 0;

$order_product_query = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . (int)$order_id )->fetchAll();

foreach ( $order_product_query as $product) 
{
	$order_info['reward'] += $product['reward'];
}
 
$country_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_country WHERE country_id = ' . ( int )$order_info['payment_country_id'] )->fetch();

if( !empty( $country_query ) )
{
	$order_info['payment_iso_code_2'] = $country_query['iso_code_2'];
	$order_info['payment_iso_code_3'] = $country_query['iso_code_3'];
}
else
{
	$order_info['payment_iso_code_2'] = '';
	$order_info['payment_iso_code_3'] = '';
}

$zone_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE zone_id = ' . ( int )$order_info['payment_zone_id'] )->fetch();

if( !empty( $zone_query ) )
{
	$order_info['payment_zone_code'] = $zone_query['code'];
}
else
{
	$order_info['payment_zone_code'] = '';
}

$country_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_country WHERE country_id = ' . ( int )$order_info['shipping_country_id'] )->fetch();

if( !empty( $country_query ) )
{
	$order_info['shipping_iso_code_2'] = $country_query['iso_code_2'];
	$order_info['shipping_iso_code_2'] = $country_query['iso_code_3'];
}
else
{
	$order_info['shipping_iso_code_2'] = '';
	$order_info['shipping_iso_code_3'] = '';
}

$zone_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE zone_id = ' . ( int )$order_info['shipping_zone_id'] )->fetch();

if( !empty( $zone_query ) )
{
	$order_info['shipping_zone_code'] = $zone_query['code'];
}
else
{
	$order_info['shipping_zone_code'] = '';
}
 
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

$dataContent = array();
$dataContent['order_id'] = $order_id;
$dataContent['invoice_prefix'] = $order_info['invoice_prefix'];

$customer_group_info = getCustomerGroup( );

if ( isset( $customer_group_info[$order_info['customer_group_id']] ) ) 
{
	$dataContent['customer_group'] = $customer_group_info[$order_info['customer_group_id']]['name'];
} else 
{
	$dataContent['customer_group'] = '';
}

$order_status_info = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id = ' . (int)$order_info['order_status_id'] . ' AND language_id = ' . (int)$ProductGeneral->current_language_id )->fetch();

if ($order_status_info) 
{
	$dataContent['order_status'] = $order_status_info['name'];

} else 
{
	$dataContent['order_status'] = '';
}
if ( $order_info['userid'] ) 
{
	$dataContent['customer_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=users&op=edit&userid=' . $order_info['userid'];
}else
{
	$dataContent['customer_url'] = '';
}
$dataContent['userid'] = $order_info['userid'];
$dataContent['first_name'] = $order_info['first_name'];
$dataContent['last_name'] = $order_info['last_name'];
$dataContent['email'] = $order_info['email'];
$dataContent['telephone'] = $order_info['telephone'];
$dataContent['fax'] = $order_info['fax'];
$dataContent['invoice_prefix'] = $order_info['invoice_prefix'];
$dataContent['store_name'] = $order_info['store_name'];
$dataContent['store_url'] = $order_info['store_url'];
$dataContent['comment'] = nv_nl2br( $order_info['comment'] );
$dataContent['shipping_method'] = $order_info['shipping_method'];
$dataContent['payment_method'] = $order_info['payment_method'];
$dataContent['total'] = $ProductCurrency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'] );
$dataContent['reward'] = $order_info['reward'];
$dataContent['reward_total'] = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_order_reward WHERE order_id = ' . ( int )$order_id )->fetchColumn();
$dataContent['ip'] = $order_info['ip'];
$dataContent['forwarded_ip'] = $order_info['forwarded_ip'];
$dataContent['user_agent'] = $order_info['user_agent'];
$dataContent['accept_language'] = $order_info['accept_language'];
$dataContent['date_added'] = date( 'd/m/Y', $order_info['date_added'] );
$dataContent['date_modified'] = date( 'd/m/Y', $order_info['date_modified'] );
$dataContent['payment_first_name'] = $order_info['payment_first_name'];
$dataContent['payment_last_name'] = $order_info['payment_last_name'];
$dataContent['payment_company'] = $order_info['payment_company'];
$dataContent['payment_address_1'] = $order_info['payment_address_1'];
$dataContent['payment_address_2'] = $order_info['payment_address_2'];
$dataContent['payment_city'] = $order_info['payment_city'];
$dataContent['payment_postcode'] = $order_info['payment_postcode'];
$dataContent['payment_zone'] = $order_info['payment_zone'];
$dataContent['payment_zone_code'] = $order_info['payment_zone_code'];
$dataContent['payment_country'] = $order_info['payment_country'];
$dataContent['shipping_first_name'] = $order_info['shipping_first_name'];
$dataContent['shipping_last_name'] = $order_info['shipping_last_name'];
$dataContent['shipping_company'] = $order_info['shipping_company'];
$dataContent['shipping_address_1'] = $order_info['shipping_address_1'];
$dataContent['shipping_address_2'] = $order_info['shipping_address_2'];
$dataContent['shipping_city'] = $order_info['shipping_city'];
$dataContent['shipping_postcode'] = $order_info['shipping_postcode'];
$dataContent['shipping_zone'] = $order_info['shipping_zone'];
$dataContent['shipping_zone_code'] = $order_info['shipping_zone_code'];
$dataContent['shipping_country'] = $order_info['shipping_country'];

$dataContent['products'] = array();
$products = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_product WHERE order_id = ' . ( int )$order_id )->fetchAll();

foreach( $products as $product )
{
	$option_data = array();

	$options = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_option WHERE order_id = ' . ( int )$order_id . ' AND order_product_id=' . ( int )$product['order_product_id'] )->fetchAll();

	foreach( $options as $option )
	{ 
		if( $option['type'] != 'file' )
		{
			$option_data[] = array(
				'name' => $option['name'],
				'value' => $option['value'],
				'type' => $option['type'] );
		}
		else
		{

			$upload_info = array();
			if( $upload_info )
			{
				$option_data[] = array(
				'name' => $option['name'],
				'value' => $upload_info['name'],
				'type' => $option['type'],
				'href' => '' );
			}
		}
	}
	//print_r( ($product['price'] + ( $ProductGeneral->config['config_tax'] ? $product['tax'] : 0 )) .'/'. $order_info['currency_code'] .'/'. $order_info['currency_value'] );die;
 	$dataContent['products'][] = array(
		'order_product_id' => $product['order_product_id'],
		'product_id' => $product['product_id'],
		'name' => $product['name'],
		'model' => $product['model'],
		'option' => $option_data,
		'quantity' => $product['quantity'],
		'price' => $ProductCurrency->format( $product['price'] + ( $ProductGeneral->config['config_tax'] ? $product['tax'] : 0 ), $order_info['currency_code'], $order_info['currency_value'] ),
		'total' => $ProductCurrency->format( $product['total'] + ( $ProductGeneral->config['config_tax'] ? ( $product['tax'] * $product['quantity'] ) : 0 ), $order_info['currency_code'], $order_info['currency_value'] ),
		'href' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product&action=edit&product_id=' . $product['product_id'] );
}

$vouchers = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_voucher WHERE order_id = ' . ( int )$order_id )->fetchAll();

foreach( $vouchers as $voucher )
{
	$dataContent['vouchers'][] = array(
		'description' => $voucher['description'],
		'amount' => $ProductCurrency->format( $voucher['amount'], $order_info['currency_code'], $order_info['currency_value'] ),
		'href' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voucher&action=edit&&voucher_id=' . $voucher['voucher_id'] );
}

$totals = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_total WHERE order_id = ' . ( int )$order_id . ' ORDER BY sort_order' )->fetchAll();

foreach( $totals as $total )
{
	$dataContent['totals'][] = array(
		'title' => $total['title'],
		'text' => $ProductCurrency->format( $total['value'], $order_info['currency_code'], $order_info['currency_value'] ),
	);
}

$dataContent['order_statuses'] = $db->query( 'SELECT order_status_id, name FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE language_id = ' . ( int )$ProductGeneral->current_language_id . ' ORDER BY name' )->fetchAll();

$dataContent['order_status_id'] = $order_info['order_status_id'];

$lang_ext = getLangAdmin( 'order', 'sale' ); 

$xtpl = new XTemplate( 'order_info.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order" );
$xtpl->assign( 'order_history', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order_history" );
$xtpl->assign( 'add_history', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_history&action=add_history&token='.$token.'&order_id=' . $order_id );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() . $order_id ) );
$xtpl->assign( 'DATA', $dataContent );

if ( $dataContent['comment'] ) 
{
	$xtpl->parse( 'main.comment' );
}

if ( $dataContent['userid'] ) 
{
	$xtpl->parse( 'main.user' );
}
else
{
	$xtpl->parse( 'main.guest' );
	
}
 
if ( $dataContent['shipping_method'] ) 
{
	$xtpl->parse( 'main.shipping_method' );
}
if ( $dataContent['payment_method'] ) 
{
	$xtpl->parse( 'main.payment_method' );
}

if ( $dataContent['customer_url'] && $dataContent['reward_total'] ) 
{
 
	if ( ! $dataContent['reward_total'] ) 
	{
		$xtpl->parse( 'main.reward.add' );
	} 
	else
	{
		$xtpl->parse( 'main.reward.del' );
		$xtpl->parse( 'main.reward' );
	}
	$xtpl->parse( 'main.reward' );
} 

if( !empty( $dataContent['fax'] ) )
{
	$xtpl->parse( 'main.fax' );
}

if( !empty( $dataContent['order_status'] ) )
{
	$xtpl->parse( 'main.order_status' );
}

foreach( $dataContent['products'] as $product )
{
	if( !empty( $product['option'] ) )
	{
		foreach( $product['option'] as $option )
		{
			$xtpl->assign( 'OPTION', $option );
			$xtpl->parse( 'main.product.option' );
		}
	}
	$xtpl->assign( 'PRODUCT', $product );
	$xtpl->parse( 'main.product' );
}

foreach( $dataContent['totals'] as $total )
{
	$xtpl->assign( 'TOTAL', $total );
	$xtpl->parse( 'main.total' );
}

// tab history 
 
foreach( $dataContent['order_statuses'] as $order_statuses )
{
 
	$xtpl->assign( 'ORDER_STATUS', array( 'key'=> $order_statuses['order_status_id'], 'name'=> $order_statuses['name'], 'selected'=> ( $order_statuses['order_status_id'] == $dataContent['order_status_id'] ) ? 'selected="selected"': ''  ) );
	$xtpl->parse( 'main.order_statuses' );
}

$xtpl->assign( 'ORDER_HISTORY', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_history&token='.$token.'&order_id='.$order_id );
$xtpl->assign( 'ORDER_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_edit&token='.$token.'&order_id='.$order_id );
$xtpl->assign( 'LINK_PRINT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=print&order_id=' . $order_info['order_id'] . '&checkss=' . md5( $order_info['order_id'] . $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'URL_ACTIVE_PAY', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active_pay&order_id=' . $order_id );
$xtpl->assign( 'URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_info&order_id=' . $order_id );
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
 
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
