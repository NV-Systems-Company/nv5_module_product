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

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );


define( 'NV_IS_MOD_PRODUCT', true );

define( 'ACTION_METHOD', $nv_Request->get_string( 'action', 'get,post', '' ) );

define( 'TABLE_CUSTOMER_NAME', $db_config['prefix'] . '_customer' );
define( 'TABLE_PRODUCT_NAME', $db_config['prefix'] . '_' . $module_data );

define( 'SHOPS_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' );

global $productRegistry, $ProductGeneral, $ProductCurrency, $globalUserid;

$globalUserid = ( isset( $user_info['userid'] ) ) ? $user_info['userid'] : 0;

$productRegistry = array(
	'mod_data' => $module_data,
	'mod_data' => $module_data,
	'mod_name' => $module_name,
	'mod_file' => $module_file,
	'mod_lang' => $lang_module,
	'lang_data' => NV_LANG_DATA,
);

$ProductGeneral = new NukeViet\Product\General( $productRegistry );
$ProductCurrency = new NukeViet\Product\Currency( $productRegistry );
$ProductTax = new NukeViet\Product\Tax( $productRegistry );
 
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/global.functions.php';
require_once NV_ROOTDIR . '/modules/' . $module_file .'/global/function_order.php';
 
if( isset( $_SESSION[$module_data . '_shipping_address'] ) )
{
	$ProductTax->setShippingAddress( $_SESSION[$module_data . '_shipping_address']['country_id'], $_SESSION[$module_data . '_shipping_address']['zone_id'] );
}
else
{

	$ProductTax->setShippingAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
}

if( isset( $_SESSION[$module_data . '_payment_address'] ) )
{
	$ProductTax->setPaymentAddress( $_SESSION[$module_data . '_payment_address']['country_id'], $_SESSION[$module_data . '_payment_address']['zone_id'] );
}
elseif( $ProductGeneral->config['config_tax_default'] == 'payment' )
{
	$ProductTax->setPaymentAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
}

$ProductTax->setStoreAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
 
$getCurrencies = getCurrencies(); 
$currencyCode ='';
if( $nv_Request->get_string( $module_data . '_currency', 'session' ) )
{
	$currencyCode = $nv_Request->get_string( $module_data . '_currency', 'session' );
}

if( $nv_Request->get_string( $module_data . '_currency', 'cookie' ) && ! array_key_exists( $currencyCode, $getCurrencies ) )
{
	$currencyCode = $nv_Request->get_string( $module_data . '_currency', 'cookie' );
}

if( ! array_key_exists( $currencyCode, $getCurrencies ) )
{
	$currencyCode = $ProductGeneral->config['config_currency'];
}

if( ! $nv_Request->get_string( $module_data . '_currency', 'session' ) || $nv_Request->get_string( $module_data . '_currency', 'session' ) != $currencyCode )
{
	$nv_Request->set_Session( $module_data . '_currency', $currencyCode );
}

if( ! $nv_Request->get_string( $module_data . '_currency', 'cookie' ) || $nv_Request->get_string( $module_data . '_currency', 'cookie' ) != $currencyCode )
{
	$nv_Request->set_Cookie( $module_data . '_currency', $currencyCode, NV_LIVE_COOKIE_TIME );
}
 
 
// $ProductWeight = new NukeViet\Product\Weight( $productRegistry );
// $ProductLength = new NukeViet\Product\Length( $productRegistry );
// $ProductCart = new NukeViet\Product\Cart( $productRegistry );
// $ProductEncryption = new NukeViet\Product\Encryption( $productRegistry );



$getWishlistProductId = array();
$arr_cat_title = array();
$category_id = 0;
$parent_id = 0;
$set_viewcat = '';
$alias_cat_url = isset( $array_op[0] ) ? $array_op[0] : '';

$array_displays = array(
	'0' => $lang_module['displays_new'],
	'1' => $lang_module['displays_price_asc'],
	'2' => $lang_module['displays_price_desc'] );

foreach( $productCategory as $row )
{
	$productCategory[$row['category_id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

	if( $alias_cat_url == $row['alias'] )
	{
		$category_id = $row['category_id'];
		$parent_id = $row['parent_id'];
	}
}

$page = 1;
$per_page = $ProductGeneral->config['config_per_page'];

if( $op == 'main' )
{
	if( empty( $category_id ) )
	{
		if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[0] ) ? $array_op[0] : '' ), $m ) )
		{
			$page = ( int )$m[1];
		}
	}
	else
	{
		if( sizeof( $array_op ) == 2 and ! preg_match( '/^page\-([0-9]+)$/', $array_op[1], $m ) )
		{
			$alias_url = $array_op[1];

			$op = 'detail';
		}
		else
		{
			if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[1] ) ? $array_op[1] : '' ), $m ) )
			{
				$page = ( int )$m[1];
			}

			$op = 'viewcat';
		}
		$parent_id = $category_id;
		while( $parent_id > 0 )
		{
			$array_cat_i = $productCategory[$parent_id];
			$array_mod_title[] = array(
				'category_id' => $parent_id,
				'title' => $array_cat_i['name'],
				'link' => $array_cat_i['link'] );
			$parent_id = $array_cat_i['parent_id'];
		}
		sort( $array_mod_title, SORT_NUMERIC );
	}
}

/**
 * GetDataIn()
 *
 * @param mixed $result
 * @param mixed $category_id
 * @return
 */
function GetDataIn( $result, $category_id )
{
	global $productCategory, $module_name, $module_upload, $db, $link, $module_info, $global_config;
	$dataContent = array();
	$data = array();
	while( $rows = $result->fetch() )
	{
		if( $rows['thumb'] == 1 )
		{
			$rows['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $rows['image'];
		}
		elseif( $rows['thumb'] == 2 )
		{
			$rows['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rows['image'];
		}
		elseif( $rows['thumb'] == 3 )
		{
			$rows['thumb'] = $rows['image'];
		}
		else
		{
			$rows['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
		}

		$rows['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$rows['category_id']]['alias'] . '/' . $rows['alias'] . '-' . $rows['product_id'] . $global_config['rewrite_exturl'];
		$rows['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;product_id=' . $rows['product_id'];

		$data[] = $rows;
	}

	$dataContent['category_id'] = $category_id;
	$dataContent['name'] = $productCategory[$category_id]['name'];
	$dataContent['image'] = $productCategory[$category_id]['image'];
	$dataContent['content'] = $data;
	$dataContent['alias'] = $productCategory[$category_id]['alias'];

	return $dataContent;
}

/**
 * SetSessionProView()
 *
 * @param mixed $id
 * @param mixed $title
 * @param mixed $alias
 * @param mixed $addtime
 * @param mixed $link
 * @param mixed $thumb
 * @return
 */
function SetSessionProView( $id, $title, $alias, $addtime, $link, $thumb )
{
	global $module_data;
	if( ! isset( $_SESSION[$module_data . '_proview'] ) ) $_SESSION[$module_data . '_proview'] = array();
	if( ! isset( $_SESSION[$module_data . '_proview'][$id] ) )
	{
		$_SESSION[$module_data . '_proview'][$id] = array(
			'title' => $title,
			'alias' => $alias,
			'addtime' => $addtime,
			'link' => $link,
			'thumb' => $thumb );
	}
}
