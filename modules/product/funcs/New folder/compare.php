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

	if( ! isset( $_SESSION[$module_data . '_compare'] ) )
	{
		$_SESSION[$module_data . '_compare'] = array();
	}

	if( empty( $ProductContent ) ) $ProductContent = new NukeViet\Product\Product( $productRegistry );

	$product_info = $ProductContent->getProduct( $product_id );

	if( $product_info )
	{
		$lang_ext = $ProductGeneral->getLangSite( 'compare', 'product' );
		
		$link_product = nv_url_rewrite( SHOPS_LINK . $productCategory[$product_info['category_id']]['alias'] . '/' . $product_info['alias'] . $global_config['rewrite_exturl'], true );
		$link_compare = nv_url_rewrite( SHOPS_LINK . 'compare' . $global_config['rewrite_exturl'], true );
			 		
		if( ! in_array( $product_id, $_SESSION[$module_data . '_compare'] ) )
		{
			if( count( $_SESSION[$module_data . '_compare'] ) >= 4 )
			{
				array_shift( $_SESSION[$module_data . '_compare'] );
			}

			$_SESSION[$module_data . '_compare'][] = $product_id;
		}

		$json['success'] = sprintf( $lang_ext['text_success'], $link_product, $product_info['name'], $link_compare );

		$json['total'] = sprintf( $lang_ext['text_compare'], ( isset( $_SESSION[$module_data . '_compare'] ) ? count( $_SESSION[$module_data . '_compare'] ) : 0 ) );

	}

	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'remove' )
{
	nv_jsonOutput( $json );
}
