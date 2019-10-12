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

if( ! function_exists( 'GlobalBlockMenuNavVertical' ) )
{

	/**
	 * ConfigGlobalBlockMenuNavVertical()
	 *
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigGlobalBlockMenuNavVertical( $module, $data_block, $lang_block )
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
	 * ConfigGlobalBlockMenuNavVerticalSubmit()
	 *
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigGlobalBlockMenuNavVerticalSubmit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 0 );
		return $return;
	}

	/**
	 * GlobalBlockMenuNavVertical()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function GlobalBlockMenuNavVertical( $block_config )
	{
		global $ProductGeneral, $productCategory, $productRegistry, $global_config, $module_name, $site_mods, $module_info, $db, $db_config;

		$mod_name = $block_config['module'];
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_upload = $site_mods[$mod_name]['module_upload'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/GlobalBlockMenuNavVertical.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
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

			$ProductGeneral = $ProductGeneral ? $ProductGeneral : new NukeViet\Product\General( $productRegistry ); 
 
			$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_category c
			LEFT JOIN ' . $ProductGeneral->table . '_category_description cd ON (c.category_id = cd.category_id) 
			WHERE cd.language_id = ' . intval( $ProductGeneral->current_language_id ) . '
			ORDER BY sort ASC';
			
			$productCategory = $ProductGeneral->getdbCache( $sql, 'category', 'category_id');

		}
		 
		$xtpl = new XTemplate( 'GlobalBlockMenuNavVertical.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'ID', $block_config['bid'] );
		
		$title_length = $block_config['title_length'];
		$a = 1;
		foreach( $productCategory as $category_id => $cat )
		{ 
			
			if( $cat['parent_id'] == 0 && $cat['inhome'] == '1')
			{
				$cat['link'] =  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias'];
				$xtpl->assign( 'CATEGORY', $cat );
				
				if( ! empty( $cat['subcatid'] ) )
				{
					
					$xtpl->assign( 'DROPDOWN', 'class="dropdown"');
					$xtpl->assign( 'SUBCAT', GlobalBlockMenuNavVerticalSub( $cat['subcatid'], $title_length,  $block_theme ) );
					$xtpl->parse( 'main.category.subcat' );
					$xtpl->parse( 'main.category.caret' );
				}else{
					$xtpl->assign( 'DROPDOWN', '');
				}
				
				$xtpl->parse( 'main.category' );	 
				++$a;
				 
 
			}
		}
		
		$xtpl->parse( 'main' );
		
		$content = $xtpl->text( 'main' );	
		
		return $content;
	}
	
	function GlobalBlockMenuNavVerticalSub( $list_sub, $title_length, $block_theme )
	{
		global $productCategory, $productRegistry;

		if( empty( $list_sub ) )
		{
			return '';
		}
		else
		{
			$xtpl = new XTemplate( 'GlobalBlockMenuNavVertical.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $productRegistry['mod_file'] );

			$list = explode( ',', $list_sub );

			foreach( $list as $category_id )
			{
				$subcat = $productCategory[$category_id];
				$subcat['name_short'] = nv_clean60( $subcat['name'], $title_length );

				$xtpl->assign( 'SUBCAT', $subcat );
				$xtpl->parse( 'subcat.loop' ); 	
			} 
 	
			$xtpl->parse( 'subcat' );
			return $xtpl->text( 'subcat' );
		}

	}

	
 
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = GlobalBlockMenuNavVertical( $block_config );
}