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

if( ! function_exists( 'nv_shops_currency' ) )
{
 
	/**
	 * nv_shops_currency()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_shops_currency( $block_config )
	{
		global $site_mods, $db_config, $db, $module_name, $module_info, $ProductGeneral->config;
		
		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];
		 
		$ProductGeneral->config = $module_config[$module]; 
		
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block.currency.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "block.currency.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		
		
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_shops_currency( $block_config );
}