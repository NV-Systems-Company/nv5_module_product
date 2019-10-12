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
	if( ! function_exists( 'getLangMod' ) )
	{
		function getLangMod( $mod_file, $block_config )
		{
			if( ! file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' ) )
			{
				trigger_error( "Error! Language variables " . $block_config['block_name'] . " is empty!", 256 );
			}
			require ( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' );

			$lang_mod = $lang_module;

			unset( $lang_module );

			return $lang_mod;
		}
	}
if( ! function_exists( 'nv_check_theme_product' ) )
	{
		if( ! nv_function_exists( 'nv_check_theme_product' ) )
		{
			function nv_check_theme_product( $mod_file )
			{
				global $global_config;

				// kiểm tra theme chứa block nếu theme đang dùng không có sẽ gọi tới block trong theme mặc định (default) của hệ thống
				if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/global.block_category_sub.tpl" ) )
				{
					$block_theme = $global_config['site_theme'];
				}
				else
				{
					$block_theme = "default";
				}
				return $block_theme;
			}

		}

	}

if( ! function_exists( 'product_category_sub_blocks' ) )
{
	

	function nv_block_config_product_sub( $module, $data_block, $lang_block )
	{
		global $db, $db_config, $module_name, $productRegistry, $site_mods, $global_config, $productCategory;

		$mod_name = $module;
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_file = $site_mods[$mod_name]['module_file'];

		$array_cat_shops = array();

		if( $module != $module_name )
		{

			$productRegistry = array(
				'mod_data' => $mod_data,
				'mod_name' => $mod_name,
				'mod_file' => $mod_file,
				'mod_lang' => getLangMod( $mod_file, $block_config ),
				'lang_data' => NV_LANG_DATA,
				);

			require_once ( NV_ROOTDIR . "/modules/" . $mod_file . "/class/shops_global.php" );
			if( empty( $ProductGeneral ) ) $ProductGeneral = new shops_global( $productRegistry );

			$sql = 'SELECT cn.name, cn.alias, cn.description, cn.meta_title, cn.meta_description, cn.meta_keyword, cn.category_id, parent_id, lev, layout, viewcat, numsubcat, subcatid, newday, form, numlinks, inhome, groups_view, image 
			FROM ' . $ProductGeneral->table_prefix . '_category cs 
			LEFT JOIN ' . $ProductGeneral->table_prefix . '_category_description cn 
			ON cs.category_id = cn.category_id
			WHERE cn.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY sort ASC';
			$array_cat_shops = $ProductGeneral->getdbCache( $sql, 'category', 'category_id' );

		}
		else
		{
			$array_cat_shops = $productCategory;
		}

		// Gọi thư viện XTemplate
		$block_theme = nv_check_theme_product( $mod_file );
		$xtpl = new XTemplate( 'global.block_category_sub.tpl', NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
		$xtpl->assign( 'LANG', $lang_block );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'DATA', $data_block );
		$xtpl->assign( 'mod_name', $mod_name );

		foreach( $array_cat_shops as $category_id => $cat )
		{
			if( $cat['parent_id'] == 0 && $cat['inhome'] == '1' )
			{
				$xtpl->assign( 'CATEGORY', array(
					'key' => $category_id,
					'name' => $cat['name'],
					'selected' => ( $category_id == $data_block['category_id'] ) ? 'selected="selected"' : '' ) );
				$xtpl->parse( 'config.category' );

			}
		}
		if( isset( $array_cat_shops[$data_block['category_id']] ) )
		{
			$subcatid = explode( ',', $array_cat_shops[$data_block['category_id']]['subcatid'] );
			foreach( $subcatid as $_category_id )
			{
				if( $array_cat_shops[$_category_id]['inhome'] == '1' )
				{
					$xtpl->assign( 'PARENT', array(
						'key' => $_category_id,
						'name' => $array_cat_shops[$_category_id]['name'],
						'checked' => in_array( $_category_id, $data_block['parent_id'] ) ? 'checked="checked"' : '' ) );
					$xtpl->parse( 'config.parent' );
				}
			}
		}
		$xtpl->parse( 'config' );
		return $xtpl->text( 'config' );
	}

	function nv_block_config_product_sub_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['category_id'] = $nv_Request->get_int( 'config_category_id', 'post', 0 );
		$return['config']['parent_id'] = array_unique( $nv_Request->get_typed_array( 'config_parent_id', 'post', 'int', array() ) );
		return $return;
	}

	function product_category_sub_blocks( $block_config )
	{
		global $home, $op, $site_mods, $ProductGeneral, $productRegistry, $global_config, $module_name, $module_info, $productCategory, $db, $db_config, $array_cat_shops;

		if( ! empty( $block_config['parent_id'] ) )
		{
			$mod_name = $block_config['module'];
			$mod_data = $site_mods[$mod_name]['module_data'];
			$mod_file = $site_mods[$mod_name]['module_file'];

			$array_cat_shops = array();

			if( $mod_name != $module_name )
			{

				$productRegistry = array(
					'mod_data' => $mod_data,
					'mod_name' => $mod_name,
					'mod_file' => $mod_file,
					'mod_lang' => getLangMod( $mod_file, $block_config ),
					'lang_data' => NV_LANG_DATA,
					);

				require_once ( NV_ROOTDIR . "/modules/" . $mod_file . "/class/shops_global.php" );
				if( empty( $ProductGeneral ) ) $ProductGeneral = new shops_global( $productRegistry );

				$sql = 'SELECT cn.name, cn.alias, cn.description, cn.meta_title, cn.meta_description, cn.meta_keyword, cn.category_id, parent_id, lev, layout, viewcat, numsubcat, subcatid, newday, form, numlinks, inhome, groups_view, image 
				FROM ' . $ProductGeneral->table_prefix . '_category cs 
				LEFT JOIN ' . $ProductGeneral->table_prefix . '_category_description cn 
				ON cs.category_id = cn.category_id
				WHERE cn.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY sort ASC';
				$array_cat_shops = nv_db_cache( $sql, 'category_id', $mod_name );

			}
			else
			{
				$array_cat_shops = $productCategory;
			}

			$block_theme = nv_check_theme_product( $mod_file );

			$xtpl = new XTemplate( 'global.block_category_sub.tpl', NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'TEMPLATE', $block_theme );

			foreach( $block_config['parent_id'] as $_category_id )
			{

				$xtpl->assign( 'CAT', $array_cat_shops[$_category_id] );

				$subcatid = explode( ',', $array_cat_shops[$_category_id]['subcatid'] );
				foreach( $subcatid as $__category_id )
				{
					if( isset( $array_cat_shops[$__category_id] ) )
					{
						if( $array_cat_shops[$__category_id]['inhome'] == '1' )
						{
							$subcat = $array_cat_shops[$__category_id];
							$subcat['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $subcat['alias'];

							$xtpl->assign( 'SUBCAT', $subcat );
							$xtpl->parse( 'main.cat.subcat' );
						}
					}
				}
				$xtpl->parse( 'main.cat' );
			}

			$xtpl->parse( 'main' );

			return $xtpl->text( 'main' );

		}

		return true;

	}

}

if( defined( 'NV_SYSTEM' ) )
{
	$content = product_category_sub_blocks( $block_config );
}
