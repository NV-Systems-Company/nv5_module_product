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
 
	if( empty( $ProductContent ) ) $ProductContent = new NukeViet\Product\Product( $productRegistry );
	
	$product_info = $ProductContent->getProduct( $product_id );

	if( $product_info )
	{
		$lang_ext = $ProductGeneral->getLangSite( 'wishlist', 'account' );
		
		$link_product =  nv_url_rewrite( SHOPS_LINK . $productCategory[$product_info['category_id']]['alias'] . '/' . $product_info['alias'] . $global_config['rewrite_exturl'], true );
		$link_wishlist =  nv_url_rewrite( SHOPS_LINK . 'wishlist' . $global_config['rewrite_exturl'], true );
		$link_register =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=register', true );
		$link_login =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login', true );
			 
		if( defined( 'NV_IS_USER' ) )
		{
			$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer_wishlist WHERE customer_id = ' . intval( $globalUserid ) . ' AND product_id = ' . intval( $product_id ) );
			$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_customer_wishlist SET customer_id = ' . intval( $globalUserid ) . ', product_id = ' . intval( $product_id ). ', date_added = ' . NV_CURRENTTIME );
			

			
			$json['success'] = sprintf( $lang_ext['text_success'], $link_product, $product_info['name'], $link_wishlist );
			
			
			$getTotalWishlist = $db->query('SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_customer_wishlist WHERE customer_id = ' . intval( $globalUserid ) )->fetchColumn();

			
			$json['total'] = sprintf( $lang_ext['text_wishlist'], $getTotalWishlist );
			
		}
		else{
			
			if (!isset($_SESSION[$module_data . '_wishlist'])) {
					$_SESSION[$module_data . '_wishlist'] = array();
				}

				$_SESSION[$module_data . '_wishlist'][] = $product_id;

				$_SESSION[$module_data . '_wishlist'] = array_unique($_SESSION[$module_data . '_wishlist']);

				$json['success'] = sprintf( $lang_ext['text_login'], $link_login, $link_register, $link_product, $product_info['name'], $link_wishlist );

				$json['total'] = sprintf($lang_ext['text_wishlist'], (isset($_SESSION[$module_data . '_wishlist']) ? count($_SESSION[$module_data . '_wishlist']) : 0));
			
			
			
		}
 	}
 
	nv_jsonOutput( $json );
	
}
else if( ACTION_METHOD == 'remove' )
{
	nv_jsonOutput( $json );
}
 