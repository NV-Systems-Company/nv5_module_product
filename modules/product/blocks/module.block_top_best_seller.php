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

if( ! nv_function_exists( 'nv_product_top_best_seller' ) )
{

	function nv_block_config_product_top_best_seller( $module, $data_block, $lang_block )
	{
		global $db, $db_config, $module_info, $module_name, $site_mods, $global_config, $productCategory;

		$mod_name = $module;
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_file = $site_mods[$mod_name]['module_file'];

		// Gọi thư viện XTemplate
		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/block.top_best_seller.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'block.top_best_seller.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'LANG', $lang_block );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'DATA', $data_block );
		$xtpl->assign( 'MOD_NAME', $mod_name );

		$xtpl->parse( 'config' );
		return $xtpl->text( 'config' );
	}

	function nv_block_config_product_top_best_seller_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	function nv_product_top_best_seller( $block_config )
	{
		global $site_mods, $db, $ProductContent, $productRegistry, $ProductGeneral, $ProductGeneral->current_language_id, $productCategory, $global_config, $lang_module, $module_config, $module_info;

		$mod_name = $block_config['module'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_file = $site_mods[$mod_name]['module_file'];

		$limit = ! empty( $block_config['numrow'] ) ? $block_config['numrow'] : 5;

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block.top_best_seller.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'block.top_best_seller.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'LANG', $ProductGeneral->mod_lang );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'MOD_NAME', $mod_name );

		$product_data = array();

		$cache_file = NV_CACHE_PREFIX . '.product.bestseller.' . $ProductGeneral->store_id . '.' . $ProductGeneral->lang_data . '.' . $ProductGeneral->config['config_customer_group_id'] . '.' . $limit . '.cache';
		if( ( $cache = nv_get_cache( $mod_name, $cache_file ) ) != false )
		{
			$data = unserialize( $cache );
		}
		else
		{
			if( empty( $ProductContent ) ) $ProductContent = new $ProductContent( $productRegistry );

			$query = $db->query( 'SELECT op.product_id, SUM(op.quantity) total FROM ' . $ProductGeneral->table_prefix . '_order_product op 
				LEFT JOIN ' . $ProductGeneral->table_prefix . '_order o ON (op.order_id = o.order_id) 
				LEFT JOIN ' . $ProductGeneral->table_prefix . '_product p ON (op.product_id = p.product_id) 
				LEFT JOIN ' . $ProductGeneral->table_prefix . '_product_to_store p2s ON (p.product_id = p2s.product_id) 
				WHERE o.order_status_id > 0 AND p.status = 1 AND p.publtime <= ' . NV_CURRENTTIME . ' AND p2s.store_id = ' . ( int )$ProductGeneral->store_id . ' 
				GROUP BY op.product_id ORDER BY total DESC LIMIT ' . ( int )$limit )->fetchAll();

			foreach( $query as $result )
			{
				$product_data[$result['product_id']] = $ProductContent->getProduct( $result['product_id'] );
			}
			$cache = serialize( $product_data );
			nv_set_cache( $mod_name, $cache_file, $cache );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$module = $block_config['module'];
	$content = nv_product_top_best_seller( $block_config );
}
