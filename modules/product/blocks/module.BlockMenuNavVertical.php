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

if ( ! function_exists( 'getLangMod' ) )
{
	function getLangMod( $mod_file, $block_config )
	{
		if( ! file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' ) )
		{
			trigger_error( 'Error! Language variables '. $block_config['block_name'] .' is empty!', 256 );
		}
		require ( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' );
	 
		$lang_mod = $lang_module;
	
		unset( $lang_module );
			
		return $lang_mod;
	}
}
if( ! function_exists( 'BlockMenuNavVertical' ) )
{

	/**
	 * ConfigBlockMenuNavVertical()
	 *
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigBlockMenuNavVertical( $module, $data_block, $lang_block )
	{
		global $db, $language_array, $db_config;
		$html = '';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['title_length'] . '</td>';
		$html .= '<td>';
		$html .= "<select name=\"config_title_length\" class=\"form-control w200\">\n";
		$html .= "<option value=\"\">" . $lang_block['title_length'] . "</option>\n";
		for( $i = 0; $i < 100; ++$i )
		{
			$html .= "<option value=\"" . $i . "\" " . ( ( $data_block['title_length'] == $i ) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
		}
		$html .= "</select>\n";
		$html .= '</td>';
		$html .= '</tr>';
		
		return $html;
	}

	/**
	 * ConfigBlockMenuNavVerticalSubmit()
	 *
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigBlockMenuNavVerticalSubmit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 0 );
		return $return;
	}

	/**
	 * BlockMenuNavVertical()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function BlockMenuNavVertical( $block_config )
	{
		global $ProductGeneral, $productCategory, $productRegistry, $category_id, $global_config, $module_name, $site_mods, $module_info, $db, $db_config;

		$mod_name = $block_config['module'];
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_upload = $site_mods[$mod_name]['module_upload'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/BlockMenuNavVertical.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'BlockMenuNavVertical.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'ID', $block_config['bid'] );
		
		$categoryFirst = getCatidInParent( $category_id, $tempCat  );
 
		$title_length = $block_config['title_length'];
		$a = 1;
		foreach( $productCategory as $_category_id => $cat )
		{ 
			
			if( $cat['parent_id'] == 0 )
			{
				$cat['link'] =  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias'];
				$cat['active'] = ( $categoryFirst ==  $_category_id ) ? 'active' : '';
 
				$xtpl->assign( 'CATEGORY', $cat );
				if( $categoryFirst == $_category_id )
				{
					if( ! empty( $cat['subcatid'] ) )
					{					
						$xtpl->assign( 'SUBCAT', BlockMenuNavVerticalSub( $cat['subcatid'], $title_length,  $block_theme ) );
						$xtpl->parse( 'main.category.subcat' );
					} 
				}
				
				$xtpl->parse( 'main.category' );	 
				++$a;
			}
		}
		
		$xtpl->parse( 'main' );
		
		$content = $xtpl->text( 'main' );	
		
		return $content;
	}
 
	function BlockMenuNavVerticalSub( $list_sub, $title_length, $block_theme )
	{
		global $productCategory, $productRegistry, $category_id;

		if( empty( $list_sub ) )
		{
			return '';
		}
		else
		{
			$xtpl = new XTemplate( 'BlockMenuNavVertical.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $productRegistry['mod_file'] );

			$list = explode( ',', $list_sub );
			$count = count( $list );
			$i = 1;
			foreach( $list as $_category_id )
			{
				$subcat = $productCategory[$_category_id];
				$subcat['name_short'] = nv_clean60( $subcat['name'], $title_length );
				$subcat['active'] = ( $category_id ==  $_category_id ) ? 'active' : '';
				$xtpl->assign( 'SUBCAT', $subcat );	

				if( ! empty( $subcat['subcatid'] ) )
				{
					$xtpl->assign( 'SUB', BlockMenuNavVerticalSub( $subcat['subcatid'], $title_length, $block_theme ) );
					$xtpl->parse( 'subcat.loop.sub' );
				}
				
				$xtpl->parse( 'subcat.loop' ); 	
				++$i;	
			} 
			$xtpl->parse( 'subcat' );
			return $xtpl->text( 'subcat' );
		}

	}
 
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = BlockMenuNavVertical( $block_config );
}