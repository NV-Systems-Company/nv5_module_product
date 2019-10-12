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

if( ! nv_function_exists( 'nv_product_sub_by_cat' ) )
{
	if( ! nv_function_exists( 'product_subcat' ) )
	{
		function product_subcat( $productCategory, $list_sub )
		{
			global $global_config, $module_file, $module_name, $module_info;

			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file . '/block.sub_by_cat.tpl' ) )
			{
				$block_theme = $global_config['site_theme'];
			}
			else
			{
				$block_theme = 'default';
			}

			$xtpl = new XTemplate( 'block.sub_by_cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file );

			if( empty( $list_sub ) )
			{
				return '';
			}
			else
			{
				$list = explode( ',', $list_sub );

				foreach( $productCategory as $cat )
				{
					$catid = $cat['category_id'];
					if( in_array( $catid, $list ) )
					{
						$cat = $productCategory[$catid];
						$cat['link'] =  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias'];
						$xtpl->assign( 'MENUTREE', $cat );
						 
						if( ! empty( $productCategory[$catid]['subcatid'] ) )
						{
							$tree = product_subcat( $productCategory, $productCategory[$catid]['subcatid'] );

							$xtpl->assign( 'TREE_CONTENT', $tree );
							$xtpl->parse( 'tree.tree_content' );
						}

						$xtpl->parse( 'tree' );
					}
				}

				return $xtpl->text( 'tree' );
			}
		}
	}
	
	function nv_product_sub_by_cat( $block_config )
	{
		global $module_data, $module_name, $category_id, $module_file, $productCategory, $global_config, $lang_module, $module_config, $module_info;

		$xtpl = new XTemplate( 'block.sub_by_cat.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
		$xtpl->assign( 'lang', $lang_module );
		
		if( isset( $productCategory[$category_id] ) )
		{
			$subcatid = array_filter( explode( ',', $productCategory[$category_id]['subcatid'] ) );
			if( empty( $subcatid ) )
			{
				$_parent_id = $productCategory[$category_id]['parent_id'];
				$subcatid = array_filter( explode( ',', $productCategory[$_parent_id]['subcatid'] ) );
			}
			if( !empty ( $subcatid ) )
			{
				foreach( $subcatid as $_catid )
				{
					$cat = $productCategory[$_catid];
					$cat['link'] =  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias'];
					if( ! empty( $cat['subcatid'] ) )
					{
						$html_content = product_subcat(  $productCategory, $cat['subcatid'] );
						$xtpl->assign( 'HTML_CONTENT', $html_content );
						$xtpl->parse( 'main.cat.loopcat1.cat2' );
					}
					$xtpl->assign( 'CAT', $cat );
					$xtpl->parse( 'main.cat.loopcat1' );
				}
				$xtpl->parse( 'main.cat' );
			}
			
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$module = $block_config['module'];
	$content = nv_product_sub_by_cat( $block_config );
}