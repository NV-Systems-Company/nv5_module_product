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

if( ! function_exists( 'product_search_box_blocks' ) )
{
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

 
	function product_search_box_blocks( $block_config )
	{
		global $home, $op, $productRegistry, $site_mods, $my_head, $ProductGeneral, $global_config, $module_name, $module_info, $productCategory, $db, $db_config, $array_cat_shops;
		
		
		$mod_name = $block_config['module'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_file = $site_mods[$mod_name]['module_file'];

		$array_cat_shops = array();

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/block.search_box.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

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
			$array_cat_shops = $ProductGeneral->getdbCache( $sql, 'category', 'category_id');

		}
		else
		{
			$array_cat_shops = $productCategory;
		}
		
		if( ! defined( 'AUTOFILL' ) )
		{
 			$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/".$mod_file."/js/autofill.js\"></script>\n";
			define( 'AUTOFILL', true );
		}
		
		$xtpl = new XTemplate( 'block.search_box.tpl', NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'MODULE_FILE', $mod_file );
		$xtpl->assign( 'JSON_PRODUCT', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $mod_name . '&' . NV_OP_VARIABLE . '=ajax&action=get_product' );
 
		foreach( $array_cat_shops as $category_id => $cat )
		{

			if( $cat['parent_id'] == 0 && $cat['inhome'] == '1' )
			{
				$cat['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias'];
				$xtpl->assign( 'CAT', $cat );
				$xtpl->parse( 'main.cat' );
 
			}
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );

	}

}

if( defined( 'NV_SYSTEM' ) )
{
	$content = product_search_box_blocks( $block_config );
}
